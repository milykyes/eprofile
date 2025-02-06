<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM Student E-Profile</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">UiTM E-Profile</a>
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="listprofile.php">Profiles</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>