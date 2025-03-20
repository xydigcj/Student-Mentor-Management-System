<?php
session_start();

// Check if the USN is passed in the URL
if (isset($_GET['usn'])) {
    $usn = $_GET['usn'];

    // Database connection
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

    // Query to fetch student details
    $sql = "SELECT first_name, last_name, usn, department, semester, gender, email, phone, dob, 
            blood_group, religion, father_name, father_occupation, mother_name, mother_occupation, 
            family_income, contact_student, contact_father, contact_mother 
            FROM students WHERE usn = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    // Bind and execute the query
    $stmt->bind_param("s", $usn);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the student exists and display their data
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode($student);  // Return the student data as JSON for debugging
    } else {
        echo "No profile found for the given USN.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No USN provided to view the profile.";
}
?>
