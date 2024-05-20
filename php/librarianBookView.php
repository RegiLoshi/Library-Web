<?php
require_once('header.php');
require_once('config.php');
session_start();

if(!isset($_SESSION['librarian_id'])){
    header('location:StaffLogin.php');
    exit();
}

$username = $_SESSION['librarian_id'];

?>
<html>
<head>
    <title>View Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="d-flex">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Librarian Panel</h4>
        <a class="nav-link text-light active" href="librarianProfile.php">Profile</a>
        <a class="nav-link text-light" href="librarianBookView.php">View Books</a>
        <a class="nav-link text-light" href="manageBookRequests.php">Book requests</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1 class="my-4">View Books</h1>
        <!-- SEARCH -->
        <div class="container py-3">
            <form action="librarianBookView.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for books..." name="search">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row font-weight-bold border-bottom mb-2">
                    <div class="col-md-2">Image</div>
                    <div class="col-md-2">Title</div>
                    <div class="col-md-2">ISBN</div>
                    <div class="col-md-1">Quantity</div>
                    <div class="col-md-2">Category</div>
                    <div class="col-md-2">Author</div>
                    <div class="col-md-1">Actions</div>
                </div>
                <?php
                // Number of books per page
                $booksPerPage = 10;

                // Get the current page number from the URL parameter
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Calculate the offset based on the current page and number of books per page
                $offset = ($page - 1) * $booksPerPage;

                // Get books data from db using LIMIT and OFFSET for pagination
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $sql = "SELECT
                            Book.*,
                            BookCategory.Category AS CategoryName,
                            CONCAT(Author.firstName, ' ', Author.lastName) AS AuthorName
                        FROM
                            Book
                        JOIN
                            belongsTo ON Book.BookId = belongsTo.BookId
                        JOIN
                            BookCategory ON belongsTo.BookCategoryId = BookCategory.BookCategoryId
                        JOIN
                            hasWritten ON Book.BookId = hasWritten.BookId
                        JOIN
                            Author ON hasWritten.authorId = Author.authorId
                        WHERE
                            Book.Quantity > 0
                            AND Book.title LIKE '%$search%'
                        LIMIT $offset, $booksPerPage";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $isbn = $row["ISBN"];
                        $book_name = $row["title"];
                        $description = $row["description"];
                        $image = $row["bookURL"];
                        $quantity = $row["Quantity"];
                        $category = $row["CategoryName"];
                        $author_name = $row["AuthorName"];
                        ?>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <img src="<?php echo $image; ?>" class="img-fluid" style="max-width: 100%; max-height: 100px;" alt="<?php echo $book_name; ?>">
                            </div>
                            <div class="col-md-2"><?php echo $book_name; ?></div>
                            <div class="col-md-2"><?php echo $isbn; ?></div>
                            <div class="col-md-1"><?php echo $quantity; ?></div>
                            <div class="col-md-2"><?php echo $category; ?></div>
                            <div class="col-md-2"><?php echo $author_name; ?></div>
                            <div class="col-md-1">
                                <button class="btn btn-info editBook" data-isbn="<?php echo $isbn; ?>">Edit Book</button>
                                <button class="btn btn-danger deleteBook" data-isbn="<?php echo $isbn; ?>">Delete Book</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='row'><div class='col-12'><p>No books found.</p></div></div>";
                }
                ?>
            </div>
        </div>
        <!-- Pagination -->
        <div class="pagination">
            <?php
            // Total number of pages
            $totalPagesSql = "SELECT CEIL(COUNT(*) / $booksPerPage) AS totalPages FROM Book";
            $totalPagesResult = $conn->query($totalPagesSql);
            $totalPages = $totalPagesResult->fetch_assoc()['totalPages'];

            // Pagination links
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = $page == $i ? 'active' : '';
                echo "<a class='$activeClass' href='?page=$i'>$i</a>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
