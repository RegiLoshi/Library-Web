<?php
// handleAction.php
session_start();
// Check if the form is submitted and the necessary data is provided
// Perform the database query based on the button clicked
require_once('dbConnection.php');
require_once('config.php'); // Include your database connection script here
var_dump($_POST);

if(isset($_POST['user_id']) && isset($_POST['book_id']) && isset($_POST['status'])) {
    echo "Everything set";
    $userId = $_POST['user_id'];
    $bookId = $_POST['book_id'];
    $status = $_POST['status'];

    if ($status == "requested") {
     
    } elseif ($status == "returned") {
        $sql = "DELETE FROM borrows WHERE UserId = ? AND BookId = ?";

$stmt = $conn->prepare($sql);


$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Record deleted successfully.";
} else {
    echo "Error: Record not found or could not be deleted.";
}

$stmt->close();
echo "Borrowed status block executed.";
    } elseif ($status == "overdue") {
        
    } elseif ($status == "borrowed") {
        
    }
    header('Location: manageBookRequests.php');
    exit();
} else {

    exit();
}
?>
