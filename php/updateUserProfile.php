<?php
require("config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $username = $_SESSION['user_id'];

    $query = "UPDATE User SET firstName=?, lastName=?, email=?, gender=? WHERE username=?";
    $statement = $conn->prepare($query);
    $statement->bind_param('sssss', $firstName, $lastName, $email, $gender, $username);
    $statement->execute();

    $statement->close();
    $conn->close();
    header('location:userProfile.php');
    exit();
} else {
    header('location:index.php');
    exit();
}
?>
