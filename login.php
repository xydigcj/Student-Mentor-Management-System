<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_mentor_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $usn = isset($_POST['usn']) ? $_POST['usn'] : '';
    $entered_password = isset($_POST['password']) ? $_POST['password'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';

    // Validate that form fields are not empty
    if (empty($usn) || empty($entered_password) || empty($semester)) {
        echo "All fields are required.";
    } else {
        // Prepare the SQL query to check if the user exists and belongs to the selected semester
        $sql = "SELECT * FROM students WHERE usn = ? AND semester = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        // Bind the parameters
        $stmt->bind_param("si", $usn, $semester); // 's' for string (USN), 'i' for integer (semester)

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();
            
            // Compare the entered password with the hashed password stored in the database
            if (password_verify($entered_password, $user['password'])) {
                // Password is correct, log the user in
                session_start();
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_role'] = 'student';
                $_SESSION['user_usn'] = $usn;
                $_SESSION['user_semester'] = $semester; // Store the selected semester
                
                // Redirect to Student Dashboard
                header('Location: student_dashboard.php');
                exit;
            } else {
                // Password is incorrect
                echo "Invalid credentials. Please try again.";
            }
        } else {
            // User does not exist or does not belong to the selected semester
            echo "No user found with this USN and semester.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
