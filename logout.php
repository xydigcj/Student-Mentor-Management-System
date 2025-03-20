<?php
// Start the session
session_start();

// Check if the user is logged in by verifying the 'user_usn' session
if (isset($_SESSION['user_usn'])) {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();
}

// Display logout success message
echo "<h1>You have logged out successfully!</h1>";
echo "<p><a href='index.php'>Click here to go to the home page.</a></p>";
?>
