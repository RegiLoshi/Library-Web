<?php
require_once('header.php');
require_once('dbConnection.php');
require_once('CheckAdminLogin.php');

session_start();

if (!is_admin_login()) {
    header('location:StaffLogin.php');
    exit();
}

$username = '';
if (isset($_POST['username'])) {
    $username = $_POST['username'];
} elseif (isset($_POST['edit_librarian'])) {
    $username = $_POST['librarian_username'];
} else {
    header('location:manageLibrarians.php');
    exit();
}

$query = "SELECT * FROM user WHERE username = :username";
$result = $conn->prepare($query);
$result->execute([':username' => $username]);

$message = '';
$error = '';

if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM user WHERE username = :username";
    $deleteStatement = $conn->prepare($deleteQuery);
    $deleteStatement->execute([':username' => $username]);
    $message = 'Librarian deleted successfully.';
    header('location: manageLibrarians.php');
    exit();
}

if (isset($_POST['edit_librarian'])) {
    $formdata = array();
    if (empty($_POST['librarian_email'])) {
        $error .= '<li>Email Address is required</li>';
    } elseif (!filter_var($_POST["librarian_email"], FILTER_VALIDATE_EMAIL)) {
        $error .= '<li>Invalid Email Address</li>';
    } else {
        $formdata['librarian_email'] = $_POST['librarian_email'];
    }

    if (empty($_POST['librarian_password'])) {
        $error .= '<li>Password is required</li>';
    } else {
        foreach($result as $row)
        if($_POST['librarian_password']==$row['password']){
            $formdata['librarian_password'] = $_POST['librarian_password'];
        } else{
            $salt = 'WebDevLibrary12345$()';
            $salted = $_POST['librarian_password'] . $salt;
            $formdata['librarian_password'] = md5($salted);
        }
        
    }

    if ($error == '') {
        
        $data = array(
            ':librarian_email' => $formdata['librarian_email'],
            ':librarian_password' => $formdata['librarian_password'],
            ':librarian_username' => $username
        );

        $updateQuery = "
            UPDATE user
            SET email = :librarian_email,
                password = :librarian_password
            WHERE username = :librarian_username
        ";

        $updateStatement = $conn->prepare($updateQuery);
        $updateStatement->execute($data);

        $message = 'User Data Edited';
        $result = $conn->prepare($query);
        $result->execute([':username' => $username]);
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
        <a class="nav-link text-light" href="adminBookRequests.php">Requests</a>
        <a class="nav-link text-light" href="manageLibrarians.php">Librarian</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <div class="card mb-4" style="width: 800px;">
        <div class="card-header">
            <i class="fas fa-user-edit"></i> Edit Librarian
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
                        <label class="form-label">Username</label>
                        <input type="text" name="librarian_username" id="librarian_username" class="form-control" 
                            value="<?php echo $row['username']; ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="librarian_name" id="librarian_name" class="form-control" 
                            value="<?php echo $row['firstName']; ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Surname</label>
                        <input type="text" name="librarian_surname" id="librarian_surname" class="form-control" 
                            value="<?php echo $row['lastName']; ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="text" name="librarian_email" id="librarian_email" class="form-control" 
                            value="<?php echo $row['email']; ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="librarian_password" id="librarian_password" class="form-control"
                            value="<?php echo $row['password']; ?>" />
                    </div>
                    <div class="mt-4 mb-0">
                        <input type="submit" name="edit_librarian" class="btn btn-primary" value="Edit" />
                        <input type="submit" name="delete" class="btn btn-danger" value="Delete" />
                        <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                    </div>
                </form>
            <?php }  ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>