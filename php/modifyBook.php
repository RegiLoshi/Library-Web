<?php
require_once('header.php');
require_once('dbConnection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

$ISBN = '';
if (isset($_POST['ISBN'])) {
    $ISBN = $_POST['ISBN'];
} elseif (isset($_POST['edit_book'])) { 
    $ISBN = $_POST['book_ISBN']; 
} else {
    header('location:adminBookManage.php');
    exit();
}

// Fetch book details
$query = "SELECT * FROM book WHERE ISBN = :ISBN";
$result = $conn->prepare($query);
$result->execute([':ISBN' => $ISBN]);
$book = $result->fetch(PDO::FETCH_ASSOC);

// Fetch authors
$query = "
    SELECT CONCAT(a.firstName, ' ', a.lastName) AS fullName
    FROM book b
    JOIN hasWritten hw ON b.BookId = hw.BookId
    JOIN Author a ON hw.authorId = a.authorId
    WHERE b.ISBN = :ISBN
";
$result2 = $conn->prepare($query);
$result2->execute([':ISBN' => $ISBN]);
$authors = $result2->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$query = "
    SELECT bc.Category
    FROM book b
    JOIN belongsTo bt ON b.BookId = bt.BookId
    JOIN BookCategory bc ON bt.BookCategoryId = bc.BookCategoryId
    WHERE b.ISBN = :ISBN 
"; 
$result3 = $conn->prepare($query);
$result3->execute([':ISBN' => $ISBN]);
$categories = $result3->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$error = '';

if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM book WHERE ISBN = :ISBN";
    $deleteStatement = $conn->prepare($deleteQuery);
    if ($deleteStatement->execute([':ISBN' => $ISBN])) {
        $message = 'Book deleted successfully.';
        header('location: adminBookManage.php');
        exit();
    } else {
        $error = 'Failed to delete the book.';
    }
}

if (isset($_POST['edit_book'])) {
    $description = $_POST['description'];
    $url = $_POST['book_url'];
    $supplier = $_POST['book_supplier'];
    $quantity = $_POST['book_quantity'];

    if ($description == '' || $url == '' || $supplier == '' || $quantity == '') {
        $error = 'All fields are required.';
    } else {
        $updateQuery = "
            UPDATE book 
            SET description = :description, bookURL = :bookURL, supplierName = :supplierName, Quantity = :quantity
            WHERE ISBN = :ISBN
        ";
        $updateStatement = $conn->prepare($updateQuery);
        $updateParams = [
            ':description' => $description,
            ':bookURL' => $url,
            ':supplierName' => $supplier,
            ':quantity' => $quantity,
            ':ISBN' => $ISBN
        ];

        if ($updateStatement->execute($updateParams)) {
            $message = 'Book details updated successfully.';
            // Refresh the page to fetch updated details
            header("Location: ".$_SERVER['PHP_SELF']."?ISBN=".$ISBN);
            exit();
        } else {
            $error = 'Failed to update book details.';
        }
    }
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
            <i class="fas fa-user-edit"></i> Edit Book
        </div>
        <div class="card-body">
            <?php
            if ($error != '') {
                echo '<div class="alert alert-danger alert-dismissible fade show d-flex" role="alert"><ul class="list-unstyled">' . $error . '</ul></div>';
            }

            if ($message != '') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $message . '</div>';
            }
            ?>

            <?php if ($book): ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="book_ISBN" id="book_ISBN" class="form-control" 
                            value="<?= htmlspecialchars($book['ISBN']); ?>" readonly />
                    </div>
                    <div class="d-flex mb-3">
                        <div class="mb-2">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="book_title" id="book_title" class="form-control" style="width:600px"
                                value="<?= htmlspecialchars($book['title']); ?>" readonly />
                        </div>
                        <img src="<?= htmlspecialchars($book['bookURL']); ?>" alt="" style="height:70px; width:50px; margin-left:100px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book Description</label>
                        <textarea name="description" id="book_description" class="form-control" style="height:120px;"><?= htmlspecialchars($book['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book URL</label>
                        <input type="url" name="book_url" id="book_url" class="form-control" 
                            value="<?= htmlspecialchars($book['bookURL']); ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book Supplier</label>
                        <input type="text" name="book_supplier" id="book_supplier" class="form-control" 
                            value="<?= htmlspecialchars($book['supplierName']); ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book Quantity</label>
                        <input type="number" name="book_quantity" id="book_quantity" class="form-control" 
                            value="<?= htmlspecialchars($book['Quantity']); ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors:</label>
                        <?php foreach ($authors as $author): ?>
                            <span><?= htmlspecialchars($author['fullName']); ?></span><br>
                        <?php endforeach; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categories:</label>
                        <?php foreach ($categories as $category): ?>
                            <span><?= htmlspecialchars($category['Category']); ?></span><br>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4 mb-0">
                        <input type="submit" name="edit_book" class="btn btn-primary" value="Edit" />
                        <input type="submit" name="delete" class="btn btn-danger" value="Delete" />
                        <input type="hidden" name="ISBN" value="<?= htmlspecialchars($book['ISBN']); ?>">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>