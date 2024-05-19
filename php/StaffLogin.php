<?php
    require_once('dbConnection.php');
    require_once('header.php');

    // Check if a session already exists
    session_start();
    if(isset($_SESSION['admin_id'])) {
        header('location:AdminView.php');
        exit(); 
    }

    if(isset($_SESSION['user_id'])){
        header('location:userProfile.php');
        exit();
    }

    if(isset($_SESSION['librarian_id'])){
        header('location:librarianProfile.php');
        exit();
    }

    $message = '';

    if(isset($_POST['login_button']))
    {
        $formdata = array();

        if(empty($_POST["email"]))
        {
            $message .= '<li>Email Address is Required</li>';
        }
        else{
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		        {
			    $message .= '<li>Invalid Email Address</li>';
		        }
		    else
		    {
			    $formdata['email'] = trim($_POST['email']);
		    }
            
        }

        if(empty($_POST['password']))
	        {
		    $message .= '<li>Password is required</li>';
	        }
	    else
	        {
                $salt = 'WebDevLibrary12345$()';
                $salted = trim($_POST['password']).$salt;
		        $formdata['password'] = md5($salted);
	        }


        if($message == '')
        {
            $data = array(
                ':email' => $formdata['email']
            );

            $query = "
            SELECT * FROM user
            WHERE email = :email
            ";
            $statement = $conn->prepare($query);
            $statement->execute($data);

            if($statement->rowCount() > 0)
		        {
			    foreach($statement->fetchAll() as $row)
			        {
				    if($row['password'] == $formdata['password'] && $row['Role']=='admin')
				        {
                            session_start();
					        $_SESSION['admin_id'] = $row['username'];
                            echo $_SESSION['admin_id'];
                    
					        header('location:AdminView.php');
                            exit();
				        }
                    else if($row['password'] == $formdata['password'] && $row['Role']=='librarian'){
                        session_start();
                        $_SESSION['librarian_id'] = $row['username'];
                        echo $_SESSION['librarian_id'];
                
                        header('location:librarianProfile.php');
                        exit();
                    }
				    else
				    {
					$message = '<li>Wrong Password</li>';
				    }
			}
		}	
		else
		{
			$message = '<li>Wrong Email Address</li>';
		}
        }    
    }
?>
<a href="index.php" class="btn btn-outline-info ml-auto"  >User Login</a>
<div class="d-flex align-items-center justify-content-center" style="min-height:700px; margin-top: -100px;">
    <div class="col-md-6">

        <?php 
		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}
		?>
        <div class="card">
            <div class="card-header">Staff Login</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="text" name="email" id="email" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" />
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <input type="submit" name="login_button" class="btn btn-primary" value="Login" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require_once("footer.php");
?>