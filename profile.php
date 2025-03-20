<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Database connection details
$servername = "localhost";  // Update if different
$username = "root";         // Update with your database username
$password = "";             // Update with your database password
$dbname = "student_mentor_system"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the logged-in user's USN from the session
$usn = $_SESSION['user_usn'];

// Query the database for the student's details
$sql = "SELECT first_name, last_name, usn, department, semester, gender, email, phone, dob, 
        blood_group, religion, father_name, father_occupation, mother_name, mother_occupation, 
        family_income, contact_student, contact_father, contact_mother 
        FROM students WHERE usn = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

// Bind and execute the query
$stmt->bind_param("s", $usn);
$stmt->execute();
$result = $stmt->get_result();

// Check if the student profile exists
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "<p>No profile found for the user.</p>";
    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 20px;
        }
        .profile-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-container h2 {
            text-align: center;
            color: #4682B4;
            margin-bottom: 20px;
        }
        .profile-details {
            line-height: 1.8;
            font-size: 16px;
        }
        .profile-details div {
            margin-bottom: 10px;
        }
        .profile-details span {
            font-weight: bold;
            color: #333;
        }
        .edit-button {
            display: inline-block;
            background-color: #4682B4;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            text-align: center;
        }
        .edit-button:hover {
            background-color: #376a8b;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Student Profile</h2>
        <div class="profile-details">
            <div><span>First Name:</span> <?php echo htmlspecialchars($student['first_name']); ?></div>
            <div><span>Last Name:</span> <?php echo htmlspecialchars($student['last_name']); ?></div>
            <div><span>USN:</span> <?php echo htmlspecialchars($student['usn']); ?></div>
            <div><span>Department:</span> <?php echo htmlspecialchars($student['department']); ?></div>
            <div><span>Semester:</span> <?php echo htmlspecialchars($student['semester']); ?></div>
            <div><span>Gender:</span> <?php echo htmlspecialchars($student['gender']); ?></div>
            <div><span>Email:</span> <?php echo htmlspecialchars($student['email']); ?></div>
            <div><span>Phone:</span> <?php echo htmlspecialchars($student['phone']); ?></div>
            <div><span>Date of Birth:</span> <?php echo htmlspecialchars($student['dob']); ?></div>
            <div><span>Blood Group:</span> <?php echo htmlspecialchars($student['blood_group']); ?></div>
            <div><span>Religion:</span> <?php echo htmlspecialchars($student['religion']); ?></div>
            <div><span>Father's Name:</span> <?php echo htmlspecialchars($student['father_name']); ?></div>
            <div><span>Father's Occupation:</span> <?php echo htmlspecialchars($student['father_occupation']); ?></div>
            <div><span>Mother's Name:</span> <?php echo htmlspecialchars($student['mother_name']); ?></div>
            <div><span>Mother's Occupation:</span> <?php echo htmlspecialchars($student['mother_occupation']); ?></div>
            <div><span>Family Income:</span> <?php echo htmlspecialchars($student['family_income']); ?></div>
            <div><span>Student Contact:</span> <?php echo htmlspecialchars($student['contact_student']); ?></div>
            <div><span>Father's Contact:</span> <?php echo htmlspecialchars($student['contact_father']); ?></div>
            <div><span>Mother's Contact:</span> <?php echo htmlspecialchars($student['contact_mother']); ?></div>
        </div>
        <a href="profile_edit.php?usn=<?php echo htmlspecialchars($student['usn']); ?>" class="edit-button">Edit Profile</a>
    </div>
</body>
</html>
