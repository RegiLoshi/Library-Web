<?php
include 'header.php';
include 'CheckAdminLogin.php';
session_start();

if(is_admin_login() == false)
{
    header('location:adminLogin.php');
}



?>
<div class="d-flex" id="navbar">
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

    <div class="flex-grow-1 p-3">
        <h1>Welcome to the Admin Panel</h1>
        <p>Select an option from the sidebar to manage the library.</p>
    </div>
    </div>

<?php
include 'footer.php';
?>