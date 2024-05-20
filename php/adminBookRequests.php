<?php
require_once('header.php');
require_once('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:StaffLogin.php');
    exit();
}

$query = "SELECT * FROM borrows";
$statement = $conn->prepare($query);
$statement->execute();

$currentDate = date("Y-m-d");
$updateQuery = "
    UPDATE borrows
    SET Status = 'overdue'
    WHERE UserId = :user_id AND BookId = :book_id
";
$updateStatement = $conn->prepare($updateQuery);

foreach ($statement->fetchAll() as $row) {
    if($row['Status']=='borrowed'){
        $interval = strtotime($currentDate)- strtotime($row["BorrowedDate"]); 
        $interval = abs(round($interval / 86400));
        if ($interval > 14) {
            $updateStatement->execute([
                ':user_id' => $row['UserId'],
                ':book_id' => $row['BookId']
            ]);
        }
    }
}

$statement = $conn->prepare($query);
$statement->execute();

$message = '';

if (isset($_POST['modify_button'])) {
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];

    if ($status == 'returned') {
        $data = array(
            ':user_id' => $user_id,
            ':book_id' => $book_id
        );
        $query = "
        DELETE FROM borrows
        WHERE UserId = :user_id AND BookId = :book_id
        ";
        $statement = $conn->prepare($query);

        if ($statement->execute($data)) {
            $message = 'Entry deleted successfully.';
        } else {
            $message = 'Failed to delete entry.';
        }

    } else {
        if ($status == 'requested') {
            $status = 'borrowed';
            $data = array(
                ':user_id' => $user_id,
                ':book_id' => $book_id,
                ':date' => $currentDate,
                ':status' => $status
            );
            $query = "
            UPDATE borrows
            SET Status = :status, BorrowedDate = :date
            WHERE UserId = :user_id AND BookId = :book_id
            ";
            $statement = $conn->prepare($query);
    
            if ($statement->execute($data)) {
                $message = 'Status modified successfully.';
            } else {
                $message = 'Failed to modify status.';
            }
        } elseif ($status == 'borrowed' || $status == 'overdue') {
            $status = 'returned';
            $data = array(
                ':status' => $status,
                ':user_id' => $user_id,
                ':book_id' => $book_id
            );
    
            $query = "
            UPDATE borrows
            SET Status = :status
            WHERE UserId = :user_id AND BookId = :book_id
            ";
            $statement = $conn->prepare($query);
    
            if ($statement->execute($data)) {
                $message = 'Status modified successfully.';
            } else {
                $message = 'Failed to modify status.';
            }
        }        
    }

    $query = "SELECT * FROM borrows";
    $statement = $conn->prepare($query);
    $statement->execute();
}
?>

<div class="d-flex">
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
            <div class="row">
                <div class="col col-md-6">
                    <i class="fas fa-table me-1"></i> Book Requests Management
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if ($message != '') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $message . '</div>';
            }
            ?>
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Book Title</th>
                        <th>Borrowed Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Username</th>
                        <th>Book Title</th>
                        <th>Borrowed Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ($statement->rowCount() > 0) {
                        foreach ($statement->fetchAll() as $row) {
                            $book_query = "SELECT title FROM book WHERE BookId = ?";
                            $book_statement = $conn->prepare($book_query);
                            $book_statement->execute([$row["BookId"]]);
                            $book_title = $book_statement->fetchColumn();

                            $user_query = "SELECT email FROM user WHERE Userid = ?";
                            $user_statement = $conn->prepare($user_query);
                            $user_statement->execute([$row["UserId"]]);
                            $user_email = $user_statement->fetchColumn();

                            $button_text = '';
                            $button_class = '';
                            $text = '';
                            if ($row["Status"] == "requested") {
                                $button_text = 'Approve';
                                $button_class = 'btn btn-warning btn-sm';
                                $text = $row["Status"];
                            } elseif ($row["Status"] == "returned") {
                                $button_text = 'Remove';
                                $button_class = 'btn btn-success btn-sm';
                                $text = $row["Status"];
                            } elseif ($row["Status"] == "overdue") {
                                $button_text = 'Returned?';
                                $button_class = 'btn btn-danger btn-sm';
                                $text = $row["Status"] . ' (Contact!)';
                            } elseif ($row["Status"] == "borrowed") {
                                $button_text = 'Returned?';
                                $button_class = 'btn btn-warning btn-sm';
                                $text = $row["Status"];
                            }
                            echo '
                            <tr>
                                <td>' . $user_email . '</td>
                                <td>' . $book_title . '</td>
                                <td>' . $row["BorrowedDate"] . '</td>
                                <td>' . $text . '</td>
                                <td>
                                    <!-- Wrap each button in a form -->
                                    <form method="POST" action="">
                                        <input type="hidden" name="user_id" value="' . $row["UserId"] . '">
                                        <input type="hidden" name="book_id" value="' . $row["BookId"] . '">
                                        <input type="hidden" name="status" value="' . $row["Status"] . '">
                                        <button type="submit" name="modify_button" class="' . $button_class . '">
                                            ' . $button_text . '
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        ';
                        }
                    } else {
                        echo '
                        <tr>
                            <td colspan="5" class="text-center">No Data Found</td>
                        </tr>
                    ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>