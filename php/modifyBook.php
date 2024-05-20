<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

$ISBN = '';
if (isset($_POST['ISBN'])) {
    $ISBN = $_POST['ISBN'];
} elseif (isset($_POST['edit_book'])) { 
    $ISBN = $_POST['book_ISBN']; 
} else {
    header('location:adminBookManage.php');
    exit();
}

$query = "SELECT * FROM book WHERE ISBN = :ISBN";
$result = $conn->prepare($query);
$result->execute([':ISBN' => $ISBN]);

$message='';
$error = '';

///////////////TO BE EDITED////////////////////////

if (isset($_POST['delete'])) {

}

if (isset($_POST['edit_librarian'])){

}
////////////////////////////////////////////////////
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
    <div class="card mb-4" style="width: 800px;">
        <div class="card-header">
            <i class="fas fa-user-edit"></i> Edit Book
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
                    <!--TO BE EDITED-->
                    <div class="mt-4 mb-0">
                        <input type="submit" name="edit_book" class="btn btn-primary" value="Edit" />
                        <input type="submit" name="delete" class="btn btn-danger" value="Delete" />
                        <input type="hidden" name="book_ISBN" value="<?php echo $row['ISBN']; ?>">
                    </div>
                </form>
            <?php }  ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>