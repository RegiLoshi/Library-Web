<?php
    require_once('header.php');
    require_once('dbConnection.php');
    
    session_start();
    if(!isset($_SESSION['admin_id'])) {
        header('location:adminLogin.php');
        exit();
    }
    
    $query = "
    SELECT * FROM user
    WHERE username = '" . $_SESSION["admin_id"] . "'
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
        foreach($result as $row)
        if($_POST['admin_password']==$row['password']){
            $formdata['admin_password'] = $_POST['admin_password'];
        } else{
            $salt = 'WebDevLibrary12345$()';
            $salted = $_POST['admin_password'] . $salt;
            $formdata['admin_password'] = md5($salted);
        }
    }

    if ($error == '') {
        $admin_username = $_SESSION['admin_id'];

        $data = array(
            ':admin_email' => $formdata['admin_email'],
            ':admin_password' => $formdata['admin_password'],
            ':admin_username' => $admin_username
        );

        $query = "
		    UPDATE user
            SET email = :admin_email,
            password = :admin_password 
            WHERE username = :admin_username
		";

        $statement = $conn->prepare($query);

        $statement->execute($data);

        $message = 'User Data Edited';
        $query = "
        SELECT * FROM user
        WHERE username = '" . $_SESSION["admin_id"] . "'
        ";
        $result = $conn->query($query);
    }
}



?>
<div class="d-flex">
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
                
                <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="admin_username" id="admin_username" class="form-control"
                            value="<?php echo $row['username']; ?>" readonly />
                </div>
                <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="admin_name" id="admin_name" class="form-control"
                            value="<?php echo $row['firstName']; ?>" readonly />
                </div>
                <div class="mb-3">
                        <label class="form-label">Surname</label>
                        <input type="text" name="admin_surname" id="admin_surname" class="form-control"
                            value="<?php echo $row['lastName']; ?>" readonly />
                </div>
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