<?php
    include 'header.php';
    include 'dbConnection.php';

    session_start();
    if(isset($_SESSION['admin_id'])) {
        header('location:AdminView.php');
        exit(); 
    }

    if(isset($_SESSION['user_id'])){
        header('location:MainnView.php');
        exit();
    }

    $message = '';

    if(isset($_POST['login_button']))
    {
        $formdata = array();

        if(empty($_POST["userEmail"]))
        {
            $message .= '<li>Email Address is Required</li>';
        }
        else{
            if(!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL))
		        {
			    $message .= '<li>Invalid Email Address</li>';
		        }
		    else
		    {
			    $formdata['userEmail'] = trim($_POST['userEmail']);
		    }
            
        }

        if(empty($_POST['userPassword']))
	        {
		    $message .= '<li>Password is required</li>';
	        }
	    else
	        {
                $salt = 'WebDevLibrary12345$()';
                $salted = trim($_POST['userPassword']).$salt;
		        $formdata['userPassword'] = md5($salted);
	        }


        if($message == '')
        {
            $data = array(
                ':userEmail' => $formdata['userEmail']
            );

            $query = "
            SELECT * FROM generaluser
            WHERE email = :userEmail
            ";
            $statement = $conn->prepare($query);
            $statement->execute($data);

            if($statement->rowCount() > 0)
		        {
			    foreach($statement->fetchAll() as $row)
			        {
				    if($row['password'] == $formdata['userPassword'])
				        {
                            session_start();
					        $_SESSION['user_id'] = $row['email'];
                            echo $_SESSION['email'];
                    
					        header('location:MainView.php');
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
    <div class="container">
    <h1 class="text-center mt-5">Welcome to Our Library!</h1>
    <a href="adminLogin.php" class="btn btn-outline-info ml-auto" >Admin Login</a>

    <!-- User Login/Signup Section -->
    <div class="row login-section">
        <div class="col-md-6 offset-md-3">
            <?php 
		    if($message != '')
		    {
    			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
	    	}
		    ?>
            <h3 class="section-title text-center">User Login</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="userEmail">Email address</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password">
                </div>
                <button type="submit" name="login_button" class="btn btn-primary btn-block">Login</button>
                <a href="userSignUp.php" class="btn btn-secondary btn-block">Signup</a>
            </form>
        </div>
    </div>

<?php
    include 'footer.php'
?>

