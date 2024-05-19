<?php
include 'header.php';
include 'CheckAdminLogin.php';
session_start();

if(!isset($_SESSION['librarian_id'])){
    header('location:StaffLogin.php');
    exit();
}

//TO be edited
?>

<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Librarian Panel</h4>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <div class="flex-grow-1 p-3">
        <h1>Welcome to the Librarian Panel</h1>
        <p>Select an option from the sidebar to manage the library.</p>
    </div>
</div>

<?php
include 'footer.php';
?>