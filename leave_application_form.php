<?php
session_start();

// Assuming the student is logged in and we have their USN
$usn = $_SESSION['user_usn'];  // Retrieve USN from session or any other way

// Database connection
$conn = new mysqli("localhost", "root", "", "student_mentor_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for leave application
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ufn = $_POST['ufn'];         // Mentor's UFN from the form
    $leave_reason = $_POST['leave_reason'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO leave_applications (usn, ufn, leave_reason, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $usn, $ufn, $leave_reason, $start_date, $end_date);

    if ($stmt->execute()) {
        $success_message = "Leave application submitted successfully.";
    } else {
        $error_message = "Error submitting application: " . $conn->error;
    }

    $stmt->close();
}

// Fetch previous leave applications for the student
$query = "SELECT * FROM leave_applications WHERE usn = ? ORDER BY start_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $usn);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }

        h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #00adb5;
            padding-bottom: 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            background-color: #00adb5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #007bff;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #00adb5;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .success-message, .error-message {
            margin-top: 20px;
            font-weight: 600;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>



<div class="container">
    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="ufn">Mentor UFN:</label>
            <input type="text" name="ufn" required>
        </div>
        <div class="input-group">
            <label for="leave_reason">Reason for Leave:</label>
            <textarea name="leave_reason" required></textarea>
        </div>
        <div class="input-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
        </div>
        <div class="input-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>
        </div>
        <button type="submit">Submit Leave Application</button>
    </form>

    <h3>Previous Leave Requests:</h3>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Leave Reason</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['leave_reason']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No leave applications found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
