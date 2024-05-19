<?php
require("config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
    exit();
}

$username = $_SESSION['user_id'];

//get UserID
$query = "SELECT * FROM User WHERE username = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $username);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();

    $userId = $userData['UserId'];
} else {
    echo "Error";
    exit();
}

//check if there are borrowed books
$query = "SELECT * FROM borrows WHERE UserId = ? AND Status = ?";
$statement = $conn->prepare($query);

$status = 'requested';

$statement->bind_param('is', $userId, $status);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    echo "Account cannot be deleted while having borrowed books";
    exit();
}

// Delete user from borrows table
$query = "DELETE FROM borrows WHERE UserId = ?";
$statement = $conn->prepare($query);
$statement->bind_param('i', $userId);
$statement->execute();



// delete user from the User table
$query = "DELETE FROM User WHERE UserId = ?";
$statement = $conn->prepare($query);
$statement->bind_param('i', $userId);
$statement->execute();

// Check if user was successfully deleted
if ($statement->affected_rows > 0) {
    echo "User deleted successfully";
} else {
    echo "Error deleting user";
}

// Close connections and statements
$statement->close();
$conn->close();
?>