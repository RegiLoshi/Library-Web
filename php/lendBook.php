<?php
session_start();
if (!isset($_SESSION['user_id'])){
    session_destroy();
    header('location:index.php');
}
if (!isset($_POST['isbn'])){
    echo "Error";
    exit();
}
$username = $_SESSION['user_id'];
$isbn = $_POST['isbn'];

require_once("config.php");

//get bookID
$query = "SELECT * FROM Book WHERE ISBN = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $isbn);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();

    $bookId = $userData['BookId'];
} else {
    echo "Error";
    exit();
}

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

//check if user has already borrowed this book
$query = "SELECT * FROM borrows WHERE BookId = ? AND UserId = ? ";
$statement = $conn->prepare($query);
$statement->bind_param('ii', $bookId,$userId);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    echo "You already have borrowed this Book";
    exit();
}

// Insert borrow request
$borrowedDate = date('Y-m-d');
$status = 'requested';
$query = "INSERT INTO borrows (BorrowedDate, Status, BookId, UserId) VALUES (?, ?, ?, ?)";
$statement = $conn->prepare($query);
$statement->bind_param('ssii', $borrowedDate, $status, $bookId, $userId);
$statement->execute();

if ($statement->affected_rows > 0) {
    echo "Book Request for Lending sent";
} else {
    echo "Error in request";
    exit();
}
?>