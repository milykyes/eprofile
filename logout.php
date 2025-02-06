<?php
// Start the session to access session variables
session_start();

// Check if the user is not logged in, redirect to login page
if(!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit;
}

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page (index.php) instead of listprofile.php since user is now logged out
header("Location: login.php");
exit;
?>