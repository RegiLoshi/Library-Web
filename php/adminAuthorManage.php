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
    $query = "DELETE FROM author WHERE authorId = :id";
    $statement = $conn->prepare($query);
    if ($statement->execute([':id' => $id])) {
        $message = 'Author deleted successfully.';
    } else {
        $message = 'Failed to delete author.';
    }
    echo '<script type="text/javascript">
                alert("' . $message . '");
                window.location.href = window.location.href;
            </script>';
    exit();
}

if (isset($_POST['add_button'])) {
    $author_name = $_POST['author_name'];
    $author_surname = $_POST['author_surname'];
    $query = "INSERT INTO author (firstName, lastName) VALUES (:author_name, :author_surname)";
    $statement = $conn->prepare($query);
    if ($statement->execute([':author_name' => $author_name, ':author_surname' => $author_surname])) {
        $message = 'Author added successfully.';
    } else {
        $message = 'Failed to add auhtor.';
    }
    echo '<script type="text/javascript">
                alert("' . $message . '");
                window.location.href = window.location.href;
              </script>';
    exit();
}

$query = "
    SELECT * FROM author
    ORDER BY firstName ASC
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
                    <i class="fas fa-table me-1"></i> Authors Management
                </div>
                <div class="col col-md-6" align="right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#addAuthorModal">Add</button>
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
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ($statement->rowCount() > 0) {
                        foreach ($statement->fetchAll() as $row) {
                            echo '
                            <tr>
                                <td>' . $row["firstName"] . '</td>
                                <td>' . $row["lastName"] . '</td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="' . $row["authorId"] . '">
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

    <div class="modal fade" id="addAuthorModal" tabindex="-1" role="dialog" aria-labelledby="addAuthorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAuthorModalLabel">Add New Author</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="author_name">Author Name</label>
                            <input type="text" class="form-control" id="author_name" name="author_name" required>
                        </div>
                        <div class="form-group">
                            <label for="author_surname">Author Surname</label>
                            <input type="text" class="form-control" id="author_surname" name="author_surname" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_button" class="btn btn-primary">Add Author</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>