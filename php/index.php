<?php
    include 'header.php';
?>
    <div class="container">
    <h1 class="text-center mt-5">Welcome to Our Portal</h1>
    <a href="adminLogin.php" class="btn btn-outline-info ml-auto" >Admin Login</a>

    <!-- User Login/Signup Section -->
    <div class="row login-section">
        <div class="col-md-6 offset-md-3">
            <h3 class="section-title text-center">User Login/Signup</h3>
            <form>
                <div class="form-group">
                    <label for="userEmail">Email address</label>
                    <input type="email" class="form-control" id="userEmail" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <a href="userSignUp.php" class="btn btn-secondary btn-block">Signup</a>
            </form>
        </div>
    </div>

<?php
    include 'footer.php'
?>

