<?php
include 'header.php';
include 'CheckAdminLogin.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header('location:index.php');
    exit();
}
//MODIFY THE VISUALS HOWEVER YOU LIKE REGI :P
//needed it just for texting and loging out
?>

<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">User Panel</h4>
        <a class="nav-link text-light" href="MainView.php">Homepage</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <div class="flex-grow-1 p-3">
        <h1>Welcome to the User Panel</h1>
        <p>Select an option from the sidebar to manage the library.</p>
    </div>
</div>

<?php
include 'footer.php';
?>