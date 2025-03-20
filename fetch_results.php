<?php
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    echo "Access denied. Please log in.";
    exit;
}

$usn = $_GET['usn'] ?? '';
if (empty($usn)) {
    echo "<p class='error'>No USN provided. Please enter a valid USN.</p>";
    exit;
}

$conn = new mysqli("localhost", "root", "", "student_mentor_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT file_path FROM results WHERE usn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usn);
$stmt->execute();
$result = $stmt->get_result();

echo "<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 20px;
        }
        table {
            width: 50%;  /* Reduced width further */
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 8px;  /* Further reduced padding */
            text-align: center;
            border-bottom: 1px solid #ccc;
            font-size: 0.85em;  /* Smaller font for compact look */
        }
        th {
            background-color: #007a7f;
            color: white;
            font-size: 0.9em;  /* Slightly larger header font */
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            color: #007a7f;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.85em;
        }
        a:hover {
            color: #005f66;
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin: 15px;
            font-size: 1em;
        }
      </style>";

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>File Name</th>
                <th>View</th>
                <th>Download</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $file_path = $row['file_path'];
        $file_name = basename($file_path);
        echo "<tr>
                <td>" . htmlspecialchars($file_name) . "</td>
                <td><a href='" . htmlspecialchars($file_path) . "' target='_blank'>View</a></td>
                <td><a href='" . htmlspecialchars($file_path) . "' download>Download</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='message'>No results found for USN: " . htmlspecialchars($usn) . "</p>";
}

$stmt->close();
$conn->close();
?>
