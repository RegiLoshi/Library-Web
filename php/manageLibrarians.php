<?php
require_once ('header.php');
require_once ('dbConnection.php');

session_start();

if(!isset($_SESSION['admin_id'])) {
    header('location:adminLogin.php');
    exit();
}

$query = "
    SELECT * FROM user
    WHERE Role='librarian'
    ORDER BY username ASC
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
        <a class="nav-link text-light" href="#">Book</a>
        <a class="nav-link text-light" href="adminBookRequests.php">Requests</a>
        <a class="nav-link text-light" href="manageLibrarians.php">Librarian</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>
    <div class="card mb-4" style=" width:800px">
        <div class="card-header">
            <div class="row">
                <div class="col col-md-6">
                    <i class="fas fa-table me-1"></i> Librarians Management
                </div>
                <div class="col col-md-6" align="right">
                <a href="newLibrarians.php" class="btn btn-success btn-sm">Add</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ($statement->rowCount() > 0) {
                        foreach ($statement->fetchAll() as $row) {
                            echo '
                            <tr>
                                <td>' . $row["username"] . '</td>
                                <td>' . $row["firstName"] . '</td>
                                <td>' . $row["lastName"] . '</td>
                                <td>' . $row["email"] . '</td>
                                <td>
                                    <form method="POST" action="modifyLibrarian.php">
                                        <input type="hidden" name="username" value="' . $row["username"] . '">
                                        <button type="submit" name="modify_button" class="btn btn-danger btn-sm">Modify</button>
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