<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['faculty_logged_in']) || $_SESSION['faculty_logged_in'] !== true) {
    header("Location: faculty_login.php");
    exit();
}

// Connect to the database
$host = "localhost";
$username = "root"; // Change as per your database setup
$password = ""; // Change as per your database setup
$database = "student_mentor_system";

$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the semester from the query string
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 0;

// Fetch all students for the semester
$sql = "
    SELECT usn, CONCAT(first_name, ' ', last_name) AS full_name 
    FROM students 
    WHERE semester = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $semester);
$stmt->execute();
$students_result = $stmt->get_result();

$students = [];
while ($row = $students_result->fetch_assoc()) {
    $students[$row['usn']] = $row['full_name'];
}

// Fetch all subjects for the semester
$subjects = [];
$subject_query = "SELECT DISTINCT subject_code FROM attendance WHERE usn IN (SELECT usn FROM students WHERE semester = ?)";
$stmt = $conn->prepare($subject_query);
$stmt->bind_param("i", $semester);
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
    $query = "SELECT COUNT(DISTINCT date) as total_classes FROM attendance WHERE subject_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_classes[$subject] = $result->fetch_assoc()['total_classes'] ?? 0;

    // Attendance data for each student and subject
    $query = "
        SELECT usn, COUNT(CASE WHEN status = '1' THEN 1 END) as attended_classes 
        FROM attendance 
        WHERE subject_code = ? 
        GROUP BY usn
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $attendance_data[$row['usn']][$subject] = [
            'attended' => $row['attended_classes'],
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Attendance - Semester <?php echo htmlspecialchars($semester); ?></title>
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
        /* Transparent background for subject, USN, and name columns */
        td, th {
            background-color: rgba(255, 255, 255, 0.5); /* Light transparent background */
        }
        th {
            background-color: rgba(240, 240, 240, 0.5); /* Light transparent background for headers */
        }
    </style>
</head>
<body>
    <h1>Final Attendance - Semester <?php echo htmlspecialchars($semester); ?></h1>

    <table>
        <thead>
            <tr>
                <th rowspan="3">USN</th>
                <th rowspan="3">Name</th>
                <?php foreach ($subjects as $subject): ?>
                    <th colspan="3"><?php echo htmlspecialchars($subject); ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($subjects as $subject): ?>
                    <th>Total Classes</th>
                    <th>Attended Classes</th>
                    <th>Percentage</th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $usn => $full_name): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usn); ?></td>
                    <td><?php echo htmlspecialchars($full_name); ?></td>
                    <?php foreach ($subjects as $subject): ?>
                        <td>
                            <?php echo $total_classes[$subject] ?? '0'; ?>
                        </td>
                        <td>
                            <?php 
                            $attended = $attendance_data[$usn][$subject]['attended'] ?? '0';
                            echo $attended; 
                            ?>
                        </td>
                        <td class="
                            <?php 
                            $total = $total_classes[$subject] ?? 0;
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
                            $percentage = $total > 0 ? ($attended / $total) * 100 : 0;
                            echo round($percentage, 2) . '%';
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
