<?php
// Start the session
session_start();

// Check if already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: listprofile.php");
    exit;
}

// Initialize variables
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get username and password
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    // Validate credentials (using your hardcoded admin/admin)
    if($username === "admin" && $password === "admin"){
        // Start a new session
        session_start();
        
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        
        // Redirect to list profile page
        header("location: listprofile.php");
        exit;
    } else {
        $login_err = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM E-Profile | Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            background: #6B0F1A 0%;
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .btn-uitm {
            background: #6B0F1A;
            color: white;
            border: none;
            padding: 10px 30px;
        }
        .btn-uitm:hover {
            background: #8B1522;
            color: white;
        }
        .footer {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .alert {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="login-card p-4">
                        <div class="text-center mb-4">
                            <img src="assets/images/logo.png" alt="UiTM Logo" class="logo">
                            <h2 class="mb-4">UiTM Student E-Profile</h2>
                        </div>
                        
                        <?php 
                        if(!empty($login_err)){
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }        
                        ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-uitm">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> UiTM Student E-Profile. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>