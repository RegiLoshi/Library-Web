<?php
session_start();

// Setting content type to JSON
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    session_destroy();
    echo json_encode(['status' => 'error', 'message' => 'You need to be logged in to lend a book']);
    exit();
}

if (!isset($_POST['isbn'])) {
    echo json_encode(['status' => 'error', 'message' => 'No ISBN provided']);
    exit();
}

$username = $_SESSION['user_id'];
$isbn = $_POST['isbn'];

require_once("config.php");

$query = "SELECT * FROM Book WHERE ISBN = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $isbn);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $bookId = $userData['BookId'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Book not found']);
    exit();
}

// Get UserID
$query = "SELECT * FROM User WHERE username = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $username);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $userId = $userData['UserId'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

// Check if user has already borrowed this book
$query = "SELECT * FROM borrows WHERE BookId = ? AND UserId = ?";
$statement = $conn->prepare($query);
$statement->bind_param('ii', $bookId, $userId);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You already have borrowed this Book']);
    exit();
}

$borrowedDate = date('Y-m-d');
$status = 'requested';
$query = "INSERT INTO borrows (BorrowedDate, Status, BookId, UserId) VALUES (?, ?, ?, ?)";
$statement = $conn->prepare($query);
$statement->bind_param('ssii', $borrowedDate, $status, $bookId, $userId);
$statement->execute();

if ($statement->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Book request for lending sent']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error in request']);
}
?>
