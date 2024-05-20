<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

$message = '';

    if (isset($_POST["register_button"])) {
        $formdata = array();

        if (empty($_POST['user_name'])) {
            $message .= '<li>Name is required</li>';
        } else {
            $formdata['user_name'] = trim($_POST['user_name']);
        }

        if (empty($_POST['user_surname'])) {
            $message .= '<li>Surname is required</li>';
        } else {
            $formdata['user_surname'] = trim($_POST['user_surname']);
        }

        if (empty($_POST['gender'])) {
            $message .= '<li>Gender is required</li>';
        } else {
            $formdata['gender'] = $_POST['gender'];
        }

        if (empty($_POST['username'])) {
            $message .= '<li>Username is required</li>';
        } else {
            $formdata['username'] = trim($_POST['username']);
        }

        if (empty($_POST["user_email_address"])) {
            $message .= '<li>Email is required</li>';
        } else {
            if (!filter_var($_POST["user_email_address"], FILTER_VALIDATE_EMAIL)) {
                $message .= '<li>Invalid Email Address</li>';
            } else {
                $formdata['user_email_address'] = trim($_POST['user_email_address']);
            }
        }

        if (empty($_POST["user_password"])) {
            $message .= '<li>Password is required</li>';
        } else {
            $salt = 'WebDevLibrary12345$()';
            $salted = trim($_POST['user_password']) . $salt;
            $formdata['user_password'] = md5($salted);
        }

        if (empty($_POST["verify_password"])) {
            $message .= '<li>Verifying the password is required</li>';
        } else {
            $salt = 'WebDevLibrary12345$()';
            $salted = trim($_POST['verify_password']) . $salt;
            $formdata['verify_password'] = md5($salted);
        }

        if ($message == '') {

            if ($formdata['user_password'] != $formdata['verify_password']) {
                $message .= '<li>Passwords do not match</li>';
            }

            $data = array(
                ':user_email_address' => $formdata['user_email_address']
            );

            $query = "
            SELECT * FROM user
            WHERE email = :user_email_address
            ";

            $statement = $conn->prepare($query);
            $statement->execute($data);

            if ($statement->rowCount() > 0) {
                $message .= '<li>Email Already Registered</li>';
            }

            $data = array(
                ':username' => $formdata['username']
            );

            $query = "
            SELECT * FROM user
            WHERE username = :username
            ";
            $statement = $conn->prepare($query);
            $statement->execute($data);

            if ($statement->rowCount() > 0) {
                $message .= '<li>Username Already Exists</li>';
            }

            if ($message == '') {
                $data = array(
				    ':user_name'			=>	$formdata['user_name'],
				    ':user_surname'			=>	$formdata['user_surname'],
				    ':gender'		        =>	$formdata['gender'],
				    ':username'			    =>	$formdata['username'],
				    ':user_email_address'	=>	$formdata['user_email_address'],
				    ':user_password'		=>	$formdata['user_password'],
                    ':user_role'            =>  'librarian'
			    );

                $query = "
			    INSERT INTO user 
                (firstName, lastName, email, username, password, gender, Role) 
                VALUES (:user_name, :user_surname, :user_email_address, :username, :user_password, :gender, :user_role)
			    ";

                $statement = $conn->prepare($query);
			    $statement->execute($data);
                $message = 'Registered successfully!';
            }
        }
    }

?>
<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Admin Panel</h4>
        <a class="nav-link text-light active" href="AdminProfile.php">Profile</a>
        <a class="nav-link text-light" href="category.php">Category</a>
        <a class="nav-link text-light" href="adminAuthorManage.php">Author</a>
        <a class="nav-link text-light" href="#">Book</a>
        <a class="nav-link text-light" href="adminBookRequests.php">Requests</a>
        <a class="nav-link text-light" href="manageLibrarians.php">Librarian</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>
    <div class="card mb-4" style=" width:800px">
        <div class="card-header">
            <div class="row">
                <div class="col col-md-6">
                    <i class="fas fa-table me-1"></i> New Librarian
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if ($message != '') {
                echo '<div class="alert alert-info">' . $message . '</div>';
            }
            ?>
            <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Surname</label>
                        <input type="text" name="user_surname" class="form-control" id="user_surname" value="<?php echo isset($_POST['user_surname']) ? htmlspecialchars($_POST['user_surname']) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="user_email_address" id="user_email_address" class="form-control" value="<?php echo isset($_POST['user_email_address']) ? htmlspecialchars($_POST['user_email_address']) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verify Password</label>
                        <input type="password" name="verify_password" id="verify_password" class="form-control" />
                    </div>
                    <div class="text-center mt-4 mb-2">
                        <input type="submit" name="register_button" class="btn btn-primary" value="Register" />
                    </div>
                </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>