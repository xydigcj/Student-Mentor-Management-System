<?php
// Start the session to access the logged-in student's USN
session_start();

// Include the database connection file
include 'db_connection.php';  // Adjust the path if necessary

// Check if the student is logged in (usn is set in session)
if (isset($_SESSION['usn'])) {
    $usn = $_SESSION['usn'];  // Retrieve USN from session

    // Fetch notifications for the student from the database
    $query = "SELECT * FROM notifications WHERE usn = ? ORDER BY timestamp DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $usn);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if notifications exist
    if ($result->num_rows > 0) {
        // Display the notifications
        while ($notification = $result->fetch_assoc()) {
            echo "<p><strong>Message:</strong> " . $notification['message'] . "<br>";
            echo "<strong>Time:</strong> " . $notification['timestamp'] . "</p>";
        }
    } else {
        // If no notifications are found
        echo "<p>No notifications available.</p>";
    }
} else {
    // If the student is not logged in
    echo "<p>Please log in to view notifications.</p>";
}
?>
