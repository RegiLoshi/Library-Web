<?php
require_once('header.php');
require_once('dbConnection.php');

$message = '';

if (isset($_POST['reset_password'])) {
    $formdata = array();

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
        $newPassword = generateRandomPassword(10); 

        $salt = 'WebDevLibrary12345$()';
        $salted = $newPassword . $salt;
        $hashedPassword = md5($salted);

        $query = "UPDATE user SET password = :password WHERE email = :email";
        $statement = $conn->prepare($query);
        $statement->execute([':password' => $hashedPassword, ':email' => $formdata['userEmail']]);

        // ---SENDING MAIL TO BE FIXED ----
        ini_set('SMTP', 'smtp.gmail.com');
        ini_set('smtp_port', 587);
        ini_set('smtp_auth', 'true');
        ini_set('smtp_secure', 'tls');
        ini_set('smtp_username', '123gachagames123@gmail.com'); 
        ini_set('smtp_password', 'LibraryPhpProject'); 

        $from = '123gachagames123@gmail.com'; 
        $to = $formdata['userEmail'];
        $headers = 'From: ' . $from . "\r\n";
        $headers .= 'Reply-To: ' . $from . "\r\n";
        $subject = 'Reset password';
        $messageBody = 'Your new password is: ' . $newPassword; 

        $mailSent = mail($to, $subject, $messageBody, $headers);
        
        if ($mailSent) {
            $message = 'Password reset successful. Check your email for the new password.';
        } else {
            $message = 'Failed to send email. Please try again later.';
        }

        //----------------------------------------------
    }
}

// Function to generate a random password
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

<div class="container">
    <h1 class="text-center mt-5">Forgot Password</h1>
    <?php
    if ($message != '') {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST">
                <div class="form-group">
                    <label for="userEmail">Enter your Email Address</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Enter email">
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