<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: mentor_login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_mentor_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the UFN from session
$ufn = $_SESSION['ufn'];

// Prepare SQL query to fetch mentor details
$sql = "SELECT first_name, last_name, ufn, department, email, phone, address FROM mentors WHERE ufn = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("s", $ufn);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists
if ($result->num_rows > 0) {
    $mentor = $result->fetch_assoc();
} else {
    echo "No profile details found.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f8;
            color: #333;
        }
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .profile-container h2 {
            color: #4682B4;
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .profile-details div {
            display: flex;
            justify-content: space-between;
        }
        .profile-details label {
            font-weight: bold;
            color: #555;
        }
        .profile-details span {
            color: #000;
        }
        .action-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background: #4682B4;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }
        .action-btn:hover {
            background: #376a8b;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Mentor Profile</h2>
        <div class="profile-details">
            <div><label>First Name:</label> <span><?php echo htmlspecialchars($mentor['first_name']); ?></span></div>
            <div><label>Last Name:</label> <span><?php echo htmlspecialchars($mentor['last_name']); ?></span></div>
            <div><label>UFN:</label> <span><?php echo htmlspecialchars($mentor['ufn']); ?></span></div>
            <div><label>Department:</label> <span><?php echo htmlspecialchars($mentor['department']); ?></span></div>
            <div><label>Email:</label> <span><?php echo htmlspecialchars($mentor['email']); ?></span></div>
            <div><label>Phone:</label> <span><?php echo htmlspecialchars($mentor['phone']); ?></span></div>
            <div><label>Address:</label> <span><?php echo htmlspecialchars($mentor['address']); ?></span></div>
        </div>
        <a href="mentor_edit.php" class="action-btn">Edit Profile</a>
        <a href="mentor_dashboard.php" class="action-btn">Back to Dashboard</a>
    </div>
</body>
</html>
