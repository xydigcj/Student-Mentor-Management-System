<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "student_mentor_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$ufn = $_POST['ufn'];
$department = $_POST['department'];
$password = $_POST['password'];  // Saving password as plain text (Not Secure)
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Insert data into the mentors table
$sql = "INSERT INTO mentors (first_name, last_name, ufn, department, password, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $firstName, $lastName, $ufn, $department, $password, $email, $phone, $address);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
