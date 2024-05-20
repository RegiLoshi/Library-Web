<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
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

$query = "SELECT * FROM book WHERE ISBN = :ISBN";
$result = $conn->prepare($query);
$result->execute([':ISBN' => $ISBN]);


$query = "SELECT *
FROM Book b
JOIN hasWritten hw ON b.BookId = hw.BookId
JOIN Author a ON hw.authorId = a.authorId
WHERE b.ISBN = :ISBN ";
$result2 = $conn->prepare($query);
$result2->execute([':ISBN' => $ISBN]);

$query = "SELECT bc.Category
FROM Book b
JOIN belongsTo bt ON b.BookId = bt.BookId
JOIN BookCategory bc ON bt.BookCategoryId = bc.BookCategoryId
WHERE b.ISBN = :ISBN "; 

$result3 =$conn->prepare($query);
$result3->execute([':ISBN' => $ISBN]);

$message='';
$error = '';
if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM Book
    WHERE ISBN = :ISBN ";
    $deleteStatement = $conn->prepare($deleteQuery);
    $deleteStatement->execute([':ISBN' => $ISBN]);
    $message = 'Book deleted successfully.';
    echo $message;
    header('location: adminBookManage.php');
    exit();
}

if (isset($_POST['edit_librarian'])){

}
////////////////////////////////////////////////////
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

            <?php

            foreach ($result as $row) {
                ?>
                
                <form method="post">
                    <div class="d-flex">
                
                <div class="mb-2">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="book_title" id="book_title" class="form-control" style="width:600px"
                            value="<?php echo $row['title']; ?>"  />
                </div>
                <img src="<?php echo $row['bookURL']; ?>" alt="" style="height:70px; width:50px; margin-left:100px;">
                </div>
                <div class="mb-2">
                        <label class="form-label">Book Description</label>
                        <textarea name="description" id="book_description" class="form-control" style="height:120px;"></textarea> 
                            
                </div>
                <div class="mb-2">
                        <label class="form-label">Book URL</label>
                        <input type="url" name="book_url" id="book_url" class="form-control"/>
                </div>
                <div class="mb-2">
                        <label class="form-label">Book Supplier</label>
                        <input type="text" name="book_supplier" id="book_supplier" class="form-control" 
                            value="<?php echo $row['supplierName']; ?>"  />
                </div>
                <div class="mb-2">
                        <label class="form-label">Book Quantity</label>
                        <input type="number" name="book_quantity" id="book_quantity" class="form-control" 
                            value="<?php echo $row['Quantity']; ?>"  />
                </div>
                <div class="mb-2">
                        <label class="form-label" readonly>ISBN:<?php echo $row['ISBN']; ?> </label>
                </div>
                <div class="mb-2">
                        <?php foreach ($result2 as $row)
                        {
                            echo '<label class="form-label" readonly> Authors:  </label>'.$row['firstName'].$row['lastName'].'';
                        }  ?>
                </div>
                <div class="mb-2">
                        <?php foreach ($result3 as $roww)
                        {
                            echo '<label class="form-label" readonly> Categories:  </label>'.$roww['Category'].'';
                        }  
                        ?>
                </div>
                    <div class="mt-2 mb-0">
                        <input type="submit" name="edit_book" class="btn btn-primary" value="Edit" />
                        <input type="submit" name="delete" class="btn btn-danger" value="Delete" />
                        <input type="hidden" name="book_ISBN" value="<?php echo $row['ISBN']; ?>">
                    </div>
                </form>
            <?php }  ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>