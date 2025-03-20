<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

// Check if mentor is logged in
if (!isset($_SESSION['ufn'])) {
    die("Mentor not logged in.");
}
$ufn = $_SESSION['ufn'];

// Fetch leave applications assigned to the logged-in mentor
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
            padding: 20px;
            background-color: #f0f0f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        label, select, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<h1>Leave Applications</h1>

<h2>Update Leave Application Status</h2>
<form method="POST" action="update_leave_status.php">
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

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
