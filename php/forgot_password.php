<?php
require_once('header.php');
require_once('dbConnection.php');


$message = '';

if (isset($_POST['reset_password'])) {
    $formdata = array();

    if (empty($_POST["username"])) {
        $message .= '<li>Username is Required</li>';
    } else {
        $formdata['username'] = trim($_POST['username']);
    }

    if (empty($_POST["userEmail"])) {
        $message .= '<li>Email Address is Required</li>';
    } else {
        if (!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
            $message .= '<li>Invalid Email Address</li>';
        } else {
            $formdata['userEmail'] = trim($_POST['userEmail']);
        }
    }

    if ($message == '') {
        $query = "SELECT * FROM user WHERE username = :username AND email = :email";
        $statement = $conn->prepare($query);
        $statement->execute([':username' => $formdata['username'], ':email' => $formdata['userEmail']]);

        if ($statement->rowCount() > 0) {
            $newPassword = $_POST['new_password']; 

            $salt = 'WebDevLibrary12345$()';
            $salted = $newPassword . $salt;
            $hashedPassword = md5($salted);

            $updateQuery = "UPDATE user SET password = :password WHERE username = :username AND email = :email";
            $updateStatement = $conn->prepare($updateQuery);
            $updateStatement->execute([':password' => $hashedPassword, ':username' => $formdata['username'], ':email' => $formdata['userEmail']]);

            $message = 'Your password has been reseted successfully.';
        } else {
            $message = 'Cannot reset password if username and email are not confirmed.';
        }
    }
}

?>

<div class="container">
    <h1 class="text-center mt-5">Forgot Password</h1>
    <?php
    if ($message != '') {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <a href="index.php">Go Back</a>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST">
                <div class="form-group">
                    <label for="username">Enter your Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="userEmail">Enter your Email Address</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="new_password">Enter new password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter username">
                </div>
                <button type="submit" name="reset_password" class="btn btn-primary btn-block">Reset Password</button>
                <a href="index.php" class="btn btn-primary btn-block">Login</a></br>
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>