<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

$message = '';

if (isset($_POST['delete_button'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM bookcategory WHERE BookCategoryId = :id";
    $statement = $conn->prepare($query);
    if ($statement->execute([':id' => $id])) {
        $message = 'Category deleted successfully.';
    } else {
        $message = 'Failed to delete category.';
    }
    echo '<script type="text/javascript">
                alert("' . $message . '");
                window.location.href = window.location.href;
            </script>';
    exit();
}

if (isset($_POST['add_button'])) {
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $query = "INSERT INTO bookcategory (Category, Quantity) VALUES (:category, :quantity)";
    $statement = $conn->prepare($query);
    if ($statement->execute([':category' => $category, ':quantity' => $quantity])) {
        $message = 'Category added successfully.';
    } else {
        $message = 'Failed to add category.';
    }
    echo '<script type="text/javascript">
                alert("' . $message . '");
                window.location.href = window.location.href;
              </script>';
    exit();
}

$query = "
    SELECT * FROM bookcategory
    ORDER BY Category ASC
    ";

$statement = $conn->prepare($query);
$statement->execute();

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
    <div class="card mb-4" style=" width:800px">
        <div class="card-header">
            <div class="row">
                <div class="col col-md-6">
                    <i class="fas fa-table me-1"></i> Categories Management
                </div>
                <div class="col col-md-6" align="right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#addCategoryModal">Add</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if ($message != '') {
                echo '<div class="alert alert-info">' . $message . '</div>';
            }
            ?>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ($statement->rowCount() > 0) {
                        foreach ($statement->fetchAll() as $row) {
                            echo '
                            <tr>
                                <td>' . $row["Category"] . '</td>
                                <td>' . $row["Quantity"] . '</td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="' . $row["BookCategoryId"] . '">
                                        <button type="submit" name="delete_button" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        ';
                        }
                    } else {
                        echo '
                        <tr>
                            <td colspan="3" class="text-center">No Data Found</td>
                        </tr>
                    ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category">Category Name</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_button" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>