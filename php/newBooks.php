<?php
require_once('header.php');
require_once('dbConnection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

// Fetch all authors
$query = "SELECT authorId, CONCAT(firstName, ' ', lastName) AS fullName FROM Author";
$result = $conn->prepare($query);
$result->execute();
$authors = $result->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories
$query = "SELECT BookCategoryId, Category FROM BookCategory";
$result = $conn->prepare($query);
$result->execute();
$categories = $result->fetchAll(PDO::FETCH_ASSOC);

$message = '';

if (isset($_POST["register_button"])) {
    $isbn = $_POST['book_ISBN'];
    $title = $_POST['book_title'];
    $description = $_POST['description'];
    $url = $_POST['book_url'];
    $supplier = $_POST['book_supplier'];
    $quantity = $_POST['book_quantity'];
    $selectedAuthors = $_POST['authors'];
    $selectedCategories = $_POST['categories'];

    if ($isbn == '' || $title == '' || $description == '' || $url == '' || $supplier == '' || $quantity == '' || empty($selectedAuthors) || empty($selectedCategories)) {
        $message = 'All fields are required.';
    } else {
        try {
            $conn->beginTransaction();

            $insertBookQuery = "
                INSERT INTO book (ISBN, title, description, bookURL, supplierName, Quantity)
                VALUES (:ISBN, :title, :description, :bookURL, :supplierName, :quantity)
            ";
            $stmt = $conn->prepare($insertBookQuery);
            $stmt->execute([
                ':ISBN' => $isbn,
                ':title' => $title,
                ':description' => $description,
                ':bookURL' => $url,
                ':supplierName' => $supplier,
                ':quantity' => $quantity,
            ]);

            $bookId = $conn->lastInsertId();

            foreach ($selectedAuthors as $authorId) {
                $insertAuthorQuery = "INSERT INTO hasWritten (BookId, authorId) VALUES (:bookId, :authorId)";
                $stmt = $conn->prepare($insertAuthorQuery);
                $stmt->execute([
                    ':bookId' => $bookId,
                    ':authorId' => $authorId,
                ]);
            }

            foreach ($selectedCategories as $categoryId) {
                $insertCategoryQuery = "INSERT INTO belongsTo (BookId, BookCategoryId) VALUES (:bookId, :categoryId)";
                $stmt = $conn->prepare($insertCategoryQuery);
                $stmt->execute([
                    ':bookId' => $bookId,
                    ':categoryId' => $categoryId,
                ]);
            }

            $conn->commit();
            $message = 'Book added successfully.';
        } catch (Exception $e) {
            $conn->rollBack();
            $message = 'Failed to add the book: ' . $e->getMessage();
        }
    }
}
?>
<div class="d-flex" id="navbar">
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
                    <i class="fas fa-table me-1"></i> New Book
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if ($message != '') {
                echo '<div class="alert alert-info">' . $message . '</div>';
            }
            ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="book_ISBN" id="book_ISBN" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Book Title</label>
                    <input type="text" name="book_title" id="book_title" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Book Description</label>
                    <textarea name="description" id="book_description" class="form-control" style="height: 120px;"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Book URL</label>
                    <input type="url" name="book_url" id="book_url" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Book Supplier</label>
                    <input type="text" name="book_supplier" id="book_supplier" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Book Quantity</label>
                    <input type="number" name="book_quantity" id="book_quantity" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Authors</label>
                    <select name="authors[]" id="authors" class="form-control" multiple>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= htmlspecialchars($author['authorId']); ?>"><?= htmlspecialchars($author['fullName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categories</label>
                    <select name="categories[]" id="categories" class="form-control" multiple>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['BookCategoryId']); ?>"><?= htmlspecialchars($category['Category']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-center mt-4 mb-2">
                    <input type="submit" name="register_button" class="btn btn-primary" value="Register" />
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>