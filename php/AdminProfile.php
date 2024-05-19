<?php
include 'header.php';
include 'dbConnection.php';
include 'CheckAdminLogin.php';
session_start();
if (!is_admin_login()) {
    header('location:adminLogin.php');
}
$query = "
    SELECT * FROM personnel
    WHERE PersonnelId = '" . $_SESSION["admin_id"] . "'
    ";
$result = $conn->query($query);
$message = '';
$error = '';
if (isset($_POST['edit_admin'])) {
    $formdata = array();
    if (empty($_POST['admin_email'])) {
        $error .= '<li>Email Address is required</li>';
    } else {
        if (!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL)) {
            $error .= '<li>Invalid Email Address</li>';
        } else {
            $formdata['admin_email'] = $_POST['admin_email'];
        }
    }

    if (empty($_POST['admin_password'])) {
        $error .= '<li>Password is required</li>';
    } else {
        $formdata['admin_password'] = $_POST['admin_password'];
    }

    if ($error == '') {
        $admin_id = $_SESSION['admin_id'];
        
        $salt = 'WebDevLibrary12345$()';
        $salted = $formdata['admin_password'].$salt;
		$formdata['admin_password'] = md5($salted);

        $data = array(
            ':admin_email' => $formdata['admin_email'],
            ':admin_password' => $formdata['admin_password'],
            ':admin_id' => $admin_id
        );

        $query = "
		    UPDATE personnel
            SET email = :admin_email,
            password = :admin_password 
            WHERE PersonnelId = :admin_id
		";

        $statement = $conn->prepare($query);

        $statement->execute($data);

        $message = 'User Data Edited';
    }


}



?>
<div class="d-flex">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Admin Panel</h4>
        <a class="nav-link text-light active" href="AdminProfile.php">Profile</a> 
        <a class="nav-link text-light" href="category.php">Category</a>
        <a class="nav-link text-light" href="#">Author</a>
        <a class="nav-link text-light" href="#">Book</a>
        <a class="nav-link text-light" href="#">User</a>
        <a class="nav-link text-light" href="#">Settings</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <div class="card mb-4" style="width: 800px;">
        <div class="card-header">
            <i class="fas fa-user-edit"></i> Edit Profile Details
        </div>
        <div class="card-body">
            <?php

            if ($error != '') {
                echo '<div class="alert alert-danger alert-dismissible fade show d-flex" role="alert"><ul class="list-unstyled">' . $error . '</ul></div>';
            }

            if ($message != '') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $message . '</div>';
            }

            ?>

            <?php

            foreach ($result as $row) {
                ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="text" name="admin_email" id="admin_email" class="form-control"
                            value="<?php echo $row['email']; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="admin_password" id="admin_password" class="form-control"
                            value="<?php echo $row['password']; ?>" />
                    </div>
                    <div class="mt-4 mb-0">
                        <input type="submit" name="edit_admin" class="btn btn-primary" value="Edit" />
                    </div>
                </form>

                <?php
            }

            ?>

        </div>
    </div>
</div>

<?php
include 'footer.php';
?>