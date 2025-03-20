<?php
// Database configuration settings
$servername = "localhost";   // Typically 'localhost' when using MySQL locally
$username = "root";          // Default MySQL username
$password = "";              // Default MySQL password (blank for XAMPP)
$dbname = "student_mentor_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
