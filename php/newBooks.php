<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

///////////////////TO BE EDITED//////////////
    $message = '';

    if (isset($_POST["register_button"])){

    }
////////////////////////////////////////////
?>
<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Admin Panel</h4>
        <a class="nav-link text-light active" href="AdminProfile.php">Profile</a>
        <a class="nav-link text-light" href="category.php">Category</a>
        <a class="nav-link text-light" href="adminAuthorManage.php">Author</a>
        <a class="nav-link text-light" href="adminBookManage.php">Book</a>
        <a class="nav-link text-light" href="adminBookRequests.php">Requests</a>
        <a class="nav-link text-light" href="manageLibrarians.php">Librarian</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>
    <div class="card mb-4" style=" width:800px">
        <div class="card-header">
            <div class="row">
                <div class="col col-md-6">
                    <i class="fas fa-table me-1"></i> New Book
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
                <!--TO BE EDITED-->
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