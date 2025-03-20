<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "student_mentor_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$ufn = $_POST['ufn'];
$entered_password = $_POST['password'];

// Prepare the SQL query to get the mentor’s data
$sql = "SELECT first_name, last_name, password FROM mentors WHERE ufn = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("s", $ufn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($entered_password === $user['password']) { // Assuming plain text for now; consider hashing in the future
        // Start session and store user details
        session_start();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['ufn'] = $ufn;
        
        // Redirect to Mentor Dashboard
        header('Location: mentor_dashboard.php');
        exit;
    } else {
        echo "Invalid credentials. Password mismatch.";
    }
} else {
    echo "No user found with this UFN.";
}

$stmt->close();
$conn->close();
?>
