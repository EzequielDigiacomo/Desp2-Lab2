<?php // Start PHP script to clear user session
session_start(); // Initialize session to access active variables
session_destroy(); // Destroy all session data completely
header('Location: login.php'); // Redirect user to the login screen
exit(); // Stop further script execution
?>
