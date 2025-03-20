<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['faculty_logged_in']) || $_SESSION['faculty_logged_in'] !== true) {
    // Redirect to the login page if not logged in
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

// Get the semester and subject from the query string
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 0;
$subject_code = isset($_GET['subject']) ? $_GET['subject'] : '';

// Fetch student data for the selected semester
$sql = "
    SELECT s.usn, s.first_name, s.last_name, s.semester
    FROM students s
    WHERE s.semester = ?
";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);
}
$stmt->bind_param("i", $semester);
$stmt->execute();
$students_result = $stmt->get_result();

// Store students in an array for later use
$students = [];
while ($student = $students_result->fetch_assoc()) {
    $students[$student['usn']] = $student; // Use USN as the key
}

// Handle form submission to update attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update attendance from both forms
    if (isset($_POST['attendance_date'])) {
        // Mark attendance for a specific date
        $attendance_date = $_POST['attendance_date']; // Get the date from the form
        foreach ($_POST['attendance'] as $usn => $status) {
            if ($status !== '1' && $status !== '0') {
                continue; // Skip invalid input
            }

            // Insert or update attendance for the student for the given date
            $sql = "
                INSERT INTO attendance (usn, date, status, subject_code)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Error preparing the query: ' . $conn->error);
            }
            $stmt->bind_param("ssss", $usn, $attendance_date, $status, $subject_code);
            $stmt->execute();
        }
        echo "<p>Attendance updated successfully!</p>";
    }

    // Update attendance for each student in the "All Attendance Records for Subject" section
    if (isset($_POST['all_attendance'])) {
        foreach ($_POST['all_attendance'] as $usn => $attendance_records) {
            foreach ($attendance_records as $date => $status) {
                if ($status !== '1' && $status !== '0') {
                    continue; // Skip invalid input
                }

                // Update attendance for the student on the given date
                $sql = "
                    INSERT INTO attendance (usn, date, status, subject_code)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE status = VALUES(status)
                ";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('Error preparing the query: ' . $conn->error);
                }
                $stmt->bind_param("ssss", $usn, $date, $status, $subject_code);
                $stmt->execute();
            }
        }
        echo "<p>Attendance updated successfully in the 'All Attendance Records' section!</p>";
    }
}

// Fetch all attendance records for the selected subject and semester
$sql = "
    SELECT usn, date, status 
    FROM attendance
    WHERE subject_code = ? AND usn IN (SELECT usn FROM students WHERE semester = ?)
";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);
}
$stmt->bind_param("si", $subject_code, $semester);
$stmt->execute();
$attendance_result = $stmt->get_result();

// Organize the attendance data by USN and date
$attendance_data = [];
while ($row = $attendance_result->fetch_assoc()) {
    $attendance_data[$row['usn']][] = $row;
}

// Calculate attendance percentage for each student
$attendance_percentage = [];
foreach ($attendance_data as $usn => $attendance_records) {
    $total_classes = count($attendance_records);
    $present_classes = count(array_filter($attendance_records, function($record) {
        return $record['status'] == '1';
    }));
    $attendance_percentage[$usn] = ($total_classes > 0) ? ($present_classes / $total_classes) * 100 : 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Semester <?php echo htmlspecialchars($semester); ?></title>
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
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"] {
            width: 50px;
            text-align: center;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .subject-list {
            margin-top: 20px;
        }
        .subject-list a {
            display: inline-block;
            margin: 5px 10px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .subject-list a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Attendance for Semester <?php echo htmlspecialchars($semester); ?></h1>
    
    <div class="subject-list">
        <!-- List of subjects -->
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS501">BCS501</a>
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS502">BCS502</a>
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS503">BCS503</a>
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS504">BCS504</a>
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS505">BCS505</a>
        <a href="?semester=<?php echo htmlspecialchars($semester); ?>&subject=BCS506">BCS506</a>
    </div>

    <?php if ($subject_code): ?>
        <h2>Mark Attendance for Subject: <?php echo htmlspecialchars($subject_code); ?></h2>
        
        <!-- Form to mark attendance -->
        <form method="post">
            <label for="attendance_date">Enter Date:</label>
            <input type="date" name="attendance_date" id="attendance_date" required><br><br>

            <table>
                <thead>
                    <tr>
                        <th>USN</th>
                        <th>Name</th>
                        <th>Status (1 for Present, 0 for Absent)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['usn']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']); ?></td>
                            <td>
                                <input type="text" name="attendance[<?php echo $student['usn']; ?>]" maxlength="1" placeholder="0 or 1" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Update Attendance</button>
        </form>

        <!-- Display All Attendance Records for Subject -->
        <h3>All Attendance Records for Subject: <?php echo htmlspecialchars($subject_code); ?></h3>
        <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>USN</th>
                        <th>Name</th>
                        <?php 
                        // Display dates dynamically
                        $dates = [];
                        foreach ($attendance_data as $usn => $attendance_records) {
                            foreach ($attendance_records as $record) {
                                $dates[] = $record['date'];
                            }
                        }
                        $dates = array_unique($dates);
                        foreach ($dates as $date):
                            echo "<th>" . htmlspecialchars($date) . "</th>";
                        endforeach;
                        ?>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_data as $usn => $attendance_records): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usn); ?></td>
                            <td>
                                <?php 
                                // Safely fetch student details from the $students array
                                if (isset($students[$usn])) {
                                    echo htmlspecialchars($students[$usn]['first_name']) . " " . htmlspecialchars($students[$usn]['last_name']);
                                }
                                ?>
                            </td>
                            <?php 
                            // Attendance status per date
                            $attendance_status = array_fill_keys($dates, 'Absent');
                            foreach ($attendance_records as $record) {
                                $attendance_status[$record['date']] = $record['status'] == '1' ? 'Present' : 'Absent';
                            }
                            foreach ($dates as $date): ?>
                                <td>
                                    <input type="text" name="all_attendance[<?php echo $usn; ?>][<?php echo $date; ?>]" 
                                           value="<?php echo $attendance_status[$date] === 'Present' ? '1' : '0'; ?>" 
                                           maxlength="1" required />
                                </td>
                            <?php endforeach; ?>
                            <td><?php echo round($attendance_percentage[$usn], 2) . "%"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Update Attendance</button>
        </form>
    <?php endif; ?>

    <!-- Button to navigate to final attendance page -->
    <br><br>
    <a href="final_attendance.php?semester=<?php echo htmlspecialchars($semester); ?>" class="button">Go to Final Attendance</a>

</body>
</html>
