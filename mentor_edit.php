<?php
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

// Fetch mentor details
$sql = "SELECT first_name, last_name, department, email, phone, address FROM mentors WHERE ufn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ufn);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();
$stmt->close();

// Update mentor details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE mentors SET first_name=?, last_name=?, department=?, email=?, phone=?, address=? WHERE ufn=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $department, $email, $phone, $address, $ufn);
    if ($stmt->execute()) {
        echo "<p>Profile updated successfully!</p>";
    } else {
        echo "<p>Error updating profile: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
    header("Location: mentor_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mentor Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .form-container h2 {
            color: #4682B4;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background: #4682B4;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-group input[type="submit"]:hover {
            background: #376a8b;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Mentor Profile</h2>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($mentor['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($mentor['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($mentor['department']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($mentor['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($mentor['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($mentor['address']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Save Changes">
            </div>
        </form>
    </div>
</body>
</html>
