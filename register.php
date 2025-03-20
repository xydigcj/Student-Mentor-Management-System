<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "student_mentor_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $usn = $_POST['usn'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $religion = $_POST['religion'];
    $father_name = $_POST['father_name'];
    $father_occupation = $_POST['father_occupation'];
    $mother_name = $_POST['mother_name'];
    $mother_occupation = $_POST['mother_occupation'];
    $family_income = $_POST['family_income'];
    $contact_student = $_POST['contact_student'];
    $contact_father = $_POST['contact_father'];
    $contact_mother = $_POST['contact_mother'];

    // Prepare the SQL statement
    $sql = "INSERT INTO students (
        first_name, last_name, usn, department, semester, password, gender, email, phone, dob,
        blood_group, religion, father_name, father_occupation, mother_name, mother_occupation,
        family_income, contact_student, contact_father, contact_mother
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssssssssss",  
        $first_name, $last_name, $usn, $department, $semester,
        $password, $gender, $email, $phone, $dob,
        $blood_group, $religion, $father_name, $father_occupation,
        $mother_name, $mother_occupation, $family_income, $contact_student,
        $contact_father, $contact_mother
    );

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Congratulations, your account has been successfully created.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e7f7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .success-box {
            background-color: #4CAF50;
            color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .success-box h2 {
            margin: 0;
            font-size: 24px;
        }

        .success-box p {
            font-size: 16px;
            margin: 10px 0;
        }

        .continue-button {
            background-color: #fff;
            color: #4CAF50;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .continue-button:hover {
            background-color: #45a049;
            color: white;
        }
    </style>
</head>
<body>

    <?php if (isset($success_message)): ?>
        <div class="success-box">
            <h2>SUCCESS</h2>
            <p><?php echo $success_message; ?></p>
            <a href="student_login.php">
                <button class="continue-button">Go to Login Page</button>
            </a>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="error-box">
            <h2>Error</h2>
            <p><?php echo $error_message; ?></p>
        </div>
    <?php endif; ?>

</body>
</html>
