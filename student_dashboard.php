<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Define allowed pages for the dashboard
$allowed_pages = ['profile', 'academics', 'attendance', 'feedback', 'leave_application_form'];

// Get the requested page from the query parameter, default to 'profile'
$page = isset($_GET['page']) ? $_GET['page'] : 'profile';

// Basic page sanitization
$page = in_array($page, $allowed_pages) ? $page : 'profile';

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

// Query to fetch attendance for the student
$sql = "SELECT * FROM attendance WHERE usn = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('SQL prepare failed: ' . $conn->error);
}

$stmt->bind_param("s", $usn); // Binding the USN parameter
$stmt->execute();
$attendance_result = $stmt->get_result();

// Close the statement and connection after fetching data
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            color: #333;
            background-color: #f4f6f8;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #1a1a2e;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            position: fixed;
            height: 100vh;
            transition: width 0.3s;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 30px;
            font-weight: 600;
            color: #00adb5;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            margin: 5px 15px;
            color: #dddddd;
            text-decoration: none;
            font-weight: 500;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: #00adb5;
            color: #ffffff;
        }

        .sidebar a.active {
            background-color: #00adb5;
            color: white;
        }

        /* Content Area */
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .content h1 {
            font-size: 2em;
            font-weight: 700;
            color: #393e46;
            margin-bottom: 20px;
            border-bottom: 2px solid #00adb5;
            padding-bottom: 10px;
        }

        /* Logout Button Styling */
        .logout-btn {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar h2 {
                font-size: 1.2em;
            }

            .sidebar a {
                padding: 10px;
                font-size: 0.9em;
                text-align: center;
            }

            .content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <a href="?page=profile" class="<?php echo ($page == 'profile') ? 'active' : ''; ?>">Profile</a>
    <a href="student_attendance.php" class="<?php echo ($page == 'attendance') ? 'active' : ''; ?>">Attendance</a>
    <a href="?page=academics" class="<?php echo ($page == 'academics') ? 'active' : ''; ?>">Academics</a>
    
    <a href="?page=leave_application_form" class="<?php echo ($page == 'leave_application_form') ? 'active' : ''; ?>">Leave Application</a>
    <!-- Logout Button -->
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="content">
    <h1><?php echo ucfirst($page); ?></h1>
    
    <?php
    // Display attendance data for the student if the page is 'attendance'
    if ($page == 'attendance') {
        echo "<table border='1'>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>";
        
        if ($attendance_result->num_rows > 0) {
            while ($row = $attendance_result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['date'] . "</td>
                        <td>" . ($row['status'] == 1 ? 'Present' : 'Absent') . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No attendance data found.</td></tr>";
        }
        
        echo "</table>";
    } elseif ($page == 'leave_application_form') {
        // Include leave application form page
        include 'leave_application_form.php';
    } elseif (file_exists("$page.php")) {
        include "$page.php";
    } else {
        echo "<p>Sorry, the requested page could not be found.</p>";
    }
    ?>
</div>

</body>
</html>
