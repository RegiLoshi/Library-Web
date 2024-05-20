<?php
session_start();

require_once('dbConnection.php');
require_once('config.php');

if (isset($_POST['user_id']) && isset($_POST['book_id']) && isset($_POST['status'])) {
    $userId = $_POST['user_id'];
    $bookId = $_POST['book_id'];
    $status = $_POST['status'];

    //depending on status the query will be used
    if ($status == "requested") {
        $new_status = "borrowed";
        $sql = "UPDATE borrows SET Status = ? WHERE UserId = ? AND BookId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $new_status, $userId, $bookId);
    } elseif ($status == "returned") {
        $sql = "DELETE FROM borrows WHERE UserId = ? AND BookId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $bookId);
    } elseif ($status == "overdue") {
        $stmt = null;
    } elseif ($status == "borrowed") {
        $new_status = "returned";
        $sql = "UPDATE borrows SET Status = ? WHERE UserId = ? AND BookId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $new_status, $userId, $bookId);
    }

    if ($stmt) {
        $stmt->execute();
        $stmt->close();
    }
    
    header('Location: manageBookRequests.php');
    exit();
} else {
    exit();
}
?>
