<?php
include 'header.php';
include 'CheckAdminLogin.php';
require("config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
    exit();
}
$username = $_SESSION['user_id'];

$query = "SELECT * FROM User WHERE username = ?";
$statement = $conn->prepare($query);
$statement->bind_param('s', $username);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();

    $firstName = $userData['firstName'];
    $lastName = $userData['lastName'];
    $email = $userData['email'];
    $gender = $userData['gender'];
    $userId = $userData['UserId'];
    $role = $userData['Role'];
} else {
    session_destroy();
    header('location:index.php');
    exit();
}

$statement->close();
$conn->close();
?>

<div class="d-flex" id="navbar">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">User Panel</h4>
        <a class="nav-link text-light" href="MainView.php">Homepage</a>
        <a class="nav-link text-light" href="logout.php">Logout</a>
    </nav>

    <div class="flex-grow-1 p-3">
        <h1>Welcome to the User Panel <?php echo $username ?>!</h1>
        <h2>Edit Your Information</h2>
        <form method="POST" action="updateUserProfile.php">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo $firstName ?>"><br><br>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo $lastName ?>"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email ?>"><br><br>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?php if ($gender == 'Male') echo 'selected' ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected' ?>>Female</option>
            </select><br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<?php
include 'footer.php';
?>
