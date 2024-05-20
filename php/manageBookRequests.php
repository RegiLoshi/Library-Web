<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

$query = "
    SELECT * FROM borrows
    ";

$statement = $conn->prepare($query);
$statement->execute();

?>
<div class="d-flex">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Librarian Panel</h4>
        <a class="nav-link text-light active" href="librarianProfile.php">Profile</a> 
        <a class="nav-link text-light" href="manageBookRequests.php">Book requests</a>
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
            <tbody>
                <?php
               if ($statement->rowCount() > 0) {
                foreach ($statement->fetchAll() as $row) {
                    // Fetch additional information from books and users tables
                    $book_query = "SELECT title FROM book WHERE BookId = ?";
                    $book_statement = $conn->prepare($book_query);
                    $book_statement->execute([$row["BookId"]]);
                    $book_title = $book_statement->fetchColumn();
            
                    $user_query = "SELECT email FROM user WHERE Userid = ?";
                    $user_statement = $conn->prepare($user_query);
                    $user_statement->execute([$row["UserId"]]);
                    $user_email = $user_statement->fetchColumn();
            
                    // Determine button and button color based on status
                    $button_text = '';
                    $button_class = '';
                    if ($row["Status"] == "requested") {
                        $button_text = 'Approve';
                        $button_class = 'btn btn-warning btn-sm';
                    } elseif ($row["Status"] == "returned") {
                        $button_text = 'Remove';
                        $button_class = 'btn btn-success btn-sm';
                    }elseif ($row["Status"] == "overdue") {
                        $button_text = 'Contact';
                        $button_class = 'btn btn-danger btn-sm';
                    }elseif ($row["Status"] == "borrowed") {
                        $button_text = 'Returned?';
                        $button_class = 'btn btn-warning btn-sm';
                        $button_style = 'style="background-color: #99ccff'; 
                    }
                    echo '
                    <tr>
                        <td>' . $user_email . '</td>
                        <td>' . $book_title . '</td>
                        <td>' . $row["BorrowedDate"] . '</td>
                        <td>' . $row["Status"] . '</td>
                        <td>
                            <!-- Wrap each button in a form -->
                            <form method="POST" action="modifyBorrow.php">
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

<?php
include 'footer.php';
?>