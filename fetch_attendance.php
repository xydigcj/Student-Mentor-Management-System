<?php
// Database connection (adjust credentials as needed)
$conn = new mysqli("localhost", "root", "", "student_mentor_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for a specific semester
$semester = $_GET['semester'] ?? '';

// Fetch student information
$students_query = "SELECT usn, first_name, last_name FROM students WHERE semester = '$semester'";
$students_result = $conn->query($students_query);

// Fetch all subjects for the semester
$subjects_query = "
    SELECT DISTINCT subject_code 
    FROM attendance 
    WHERE usn IN (SELECT usn FROM students WHERE semester = '$semester')
";
$subjects_result = $conn->query($subjects_query);

$subjects = [];
while ($row = $subjects_result->fetch_assoc()) {
    $subjects[] = $row['subject_code'];
}

// Initialize arrays for attendance data
$attendance_data = [];
$total_classes = [];

// Fetch attendance data
$attendance_query = "
    SELECT usn, subject_code, COUNT(*) as attended 
    FROM attendance 
    WHERE status = 1 AND usn IN (SELECT usn FROM students WHERE semester = '$semester') 
    GROUP BY usn, subject_code
";
$attendance_result = $conn->query($attendance_query);

// Store attendance data
while ($row = $attendance_result->fetch_assoc()) {
    $usn = $row['usn'];
    $subject_code = $row['subject_code'];
    $attended = $row['attended'];

    $attendance_data[$usn][$subject_code]['attended'] = $attended;
}

// Fetch total classes per subject
$total_classes_query = "
    SELECT subject_code, COUNT(DISTINCT date) as total_classes 
    FROM attendance 
    WHERE usn IN (SELECT usn FROM students WHERE semester = '$semester') 
    GROUP BY subject_code
";
$total_classes_result = $conn->query($total_classes_query);

while ($row = $total_classes_result->fetch_assoc()) {
    $subject_code = $row['subject_code'];
    $total_classes[$subject_code] = $row['total_classes'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .low-attendance {
            background-color: rgba(255, 165, 0, 0.4); /* Orange */
        }
        .medium-attendance {
            background-color: rgba(255, 255, 0, 0.4); /* Yellow */
        }
        .high-attendance {
            background-color: rgba(0, 255, 0, 0.4); /* Green */
        }
    </style>
</head>
<body>
    <h2>Attendance Report for Semester <?php echo htmlspecialchars($semester); ?></h2>
    <table>
        <thead>
            <tr>
                <th>USN</th>
                <th>Name</th>
                <?php foreach ($subjects as $subject): ?>
                    <th><?php echo htmlspecialchars($subject); ?> (Total Classes)</th>
                    <th><?php echo htmlspecialchars($subject); ?> (Attended)</th>
                    <th><?php echo htmlspecialchars($subject); ?> (%)</th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($student = $students_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['usn']); ?></td>
                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                    <?php 
                        $usn = $student['usn'];
                        foreach ($subjects as $subject): 
                            $total = $total_classes[$subject] ?? 0;
                            $attended = $attendance_data[$usn][$subject]['attended'] ?? 0;
                            $percentage = ($total > 0) ? round(($attended / $total) * 100, 2) : 0;
                    ?>
                        <td><?php echo $total; ?></td>
                        <td><?php echo $attended; ?></td>
                        <td class="<?php 
                            echo $percentage < 75 ? 'low-attendance' : 
                                ($percentage < 90 ? 'medium-attendance' : 'high-attendance'); 
                        ?>">
                            <?php echo $percentage . '%'; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
