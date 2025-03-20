<?php
// Start the session
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

// Fetch attendance data for the logged-in student
$usn = $_SESSION['user_usn']; // USN from session

// Fetch all subjects for the student
$subjects = [];
$subject_query = "SELECT DISTINCT subject_code FROM attendance WHERE usn = ?";
$stmt = $conn->prepare($subject_query);
$stmt->bind_param("s", $usn);
$stmt->execute();
$subject_result = $stmt->get_result();
while ($row = $subject_result->fetch_assoc()) {
    $subjects[] = $row['subject_code'];
}

// Fetch attendance data
$attendance_data = [];
$total_classes = [];
foreach ($subjects as $subject) {
    // Total classes for each subject
    $query = "SELECT COUNT(DISTINCT date) as total_classes FROM attendance WHERE subject_code = ? AND usn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subject, $usn);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_classes[$subject] = $result->fetch_assoc()['total_classes'] ?? 0;

    // Attendance data for each subject
    $query = "
        SELECT COUNT(CASE WHEN status = '1' THEN 1 END) as attended_classes 
        FROM attendance 
        WHERE subject_code = ? AND usn = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subject, $usn);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance_data[$subject] = $result->fetch_assoc()['attended_classes'] ?? 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f9f9f9;
        }
        /* Color codes for attendance percentages */
        .low-attendance {
            background-color: rgba(255, 165, 0, 0.4); /* Light orange */
            color: black;
        }
        .medium-attendance {
            background-color: rgba(255, 255, 0, 0.4); /* Light yellow */
        }
        .high-attendance {
            background-color: rgba(0, 255, 0, 0.4); /* Light green */
            color: black;
        }
    </style>
</head>
<body>
    <h1>Attendance Summary</h1>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Subject</th>
                <th rowspan="2">Total Classes</th>
                <th rowspan="2">Attended Classes</th>
                <th rowspan="2">Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?php echo htmlspecialchars($subject); ?></td>
                    <td><?php echo $total_classes[$subject] ?? '0'; ?></td>
                    <td><?php echo $attendance_data[$subject] ?? '0'; ?></td>
                    <td class="
                        <?php 
                        $total = $total_classes[$subject] ?? 0;
                        $attended = $attendance_data[$subject] ?? 0;
                        $percentage = $total > 0 ? ($attended / $total) * 100 : 0;
                        if ($percentage < 30) {
                            echo "low-attendance"; 
                        } elseif ($percentage >= 30 && $percentage < 85) {
                            echo "medium-attendance"; 
                        } else {
                            echo "high-attendance"; 
                        }
                        ?>
                    ">
                        <?php
                        $total = $total_classes[$subject] ?? 0;
                        $attended = $attendance_data[$subject] ?? 0;
                        $percentage = $total > 0 ? ($attended / $total) * 100 : 0;
                        echo round($percentage, 2) . '%';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
