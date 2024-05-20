<?php
require("config.php");
include("header.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
    exit();
}
$username = $_SESSION['user_id'];

// Get the user's ID
$query = "SELECT UserId FROM User WHERE username = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $username);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $userId = $userData['UserId'];
} else {
    echo "Error: User not found";
    exit();
}
?>



<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">User Panel</h4>
        <a class="nav-link text-light" href="MainView.php">Homepage</a>
        <a class="nav-link text-light" href="userProfile.php">User Profile</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <!-- Borrowed Books -->
    <section style="background-color: #eee; width:800px;">
        <div class="text-center container py-5">
            <h4 class="mt-4 mb-5"><strong>Your Borrowed Books</strong></h4>
            <div class="row">
                <?php
                $sql = "SELECT
                            Book.*,
                            BookCategory.Category AS CategoryName,
                            CONCAT(Author.firstName, ' ', Author.lastName) AS AuthorName,
                            borrows.BorrowedDate,
                            borrows.Status
                        FROM
                            Book
                        JOIN
                            borrows ON Book.BookId = borrows.BookId
                        JOIN
                            belongsTo ON Book.BookId = belongsTo.BookId
                        JOIN
                            BookCategory ON belongsTo.BookCategoryId = BookCategory.BookCategoryId
                        JOIN
                            hasWritten ON Book.BookId = hasWritten.BookId
                        JOIN
                            Author ON hasWritten.authorId = Author.authorId
                        WHERE
                            borrows.UserId = ?";

                $statement = $conn->prepare($sql);
                $statement->bind_param('i', $userId);
                $statement->execute();
                $result = $statement->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $isbn = $row["ISBN"];
                        $book_name = $row["title"];
                        $description = $row["description"];
                        $image = $row["bookURL"];
                        $category = $row["CategoryName"];
                        $author_name = $row["AuthorName"];
                        $borrowed_date = $row["BorrowedDate"];
                        $status = $row["Status"];
                ?>
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light" data-mdb-ripple-color="light">
                            <img src="<?php echo $image; ?>" class="w-100" style="max-width: 100%; max-height: 500px;" />
                            <div class="mask">
                                <div class="d-flex justify-content-start align-items-end h-100">
                                    <span class="badge rounded-pill bg-primary" style="color: white;">Borrowed</span>
                                </div>
                            </div>
                            <div class="hover-overlay">
                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>#<?php echo $isbn; ?></p>
                            <a href="#" class="text-reset">
                                <h5 class="card-title mb-3"><?php echo $book_name; ?></h5>
                            </a>
                            <a href="#" class="text-reset">
                                <p>Category: <?php echo $category; ?></p>
                            </a>
                            <h6 class="mb-3">Written by: <?php echo $author_name; ?></h6>
                            <p>Borrowed Date: <?php echo $borrowed_date; ?></p>
                            <p>Status: <?php echo ucfirst($status); ?></p>
                            <!-- View Details and Lend buttons are hidden since these are borrowed books -->
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No borrowed books found.</p>";
                }
                ?>
            </div>
        </div>
    </section>
</div>


<?php
$statement->close();
$conn->close();
require_once('footer.php');
?>
