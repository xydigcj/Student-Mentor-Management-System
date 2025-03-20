<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['ufn'])) {
    die("Mentor not logged in.");
}
$ufn = $_SESSION['ufn'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usn'], $_POST['status'])) {
    $usn = $_POST['usn'];
    $status = $_POST['status'];

    $check_query = "SELECT * FROM leave_applications WHERE usn = ? AND ufn = ? AND status = 'pending' LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $usn, $ufn);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $update_query = "UPDATE leave_applications SET status = ? WHERE usn = ? AND ufn = ? AND status = 'pending'";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $status, $usn, $ufn);
        $update_stmt->execute();

        $notification_query = "INSERT INTO notifications (usn, message) VALUES (?, ?)";
        $notification_stmt = $conn->prepare($notification_query);
        $message = "Your leave application has been $status.";
        $notification_stmt->bind_param("ss", $usn, $message);
        $notification_stmt->execute();

        echo "<p class='success'>Leave application for USN: $usn has been updated to '$status'.</p>";

        $update_stmt->close();
        $notification_stmt->close();
    } else {
        echo "<p class='error'>No pending leave application found for USN: $usn.</p>";
    }
    $check_stmt->close();
}

$query = "SELECT usn, leave_reason, start_date, end_date, status FROM leave_applications WHERE ufn = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $ufn);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9f5ff;
        }
        form {
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Leave Applications</h1>
    <h2>Update Leave Application Status</h2>
    <form method="POST">
        <label for="usn">USN:</label>
        <input type="text" name="usn" id="usn" required>
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <button type="submit">Update Status</button>
    </form>

    <h2>Current Leave Applications</h2>
    <table>
        <thead>
            <tr>
                <th>USN</th>
                <th>Leave Reason</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['usn']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['leave_reason']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No leave applications found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
