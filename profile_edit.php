<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}

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

// Retrieve the logged-in user's USN from the session
$usn = $_SESSION['user_usn'];

if (isset($_GET['usn']) && $_GET['usn'] == $usn) {
    // Query the database for the student's details
    $sql = "SELECT * FROM students WHERE usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usn);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

// If the form is submitted, update the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $bloodGroup = $_POST['blood_group'];
    $religion = $_POST['religion'];
    $fatherName = $_POST['father_name'];
    $fatherOccupation = $_POST['father_occupation'];
    $motherName = $_POST['mother_name'];
    $motherOccupation = $_POST['mother_occupation'];
    $familyIncome = $_POST['family_income'];
    $contactStudent = $_POST['contact_student'];
    $contactFather = $_POST['contact_father'];
    $contactMother = $_POST['contact_mother'];

    // Update the database
    $sql = "UPDATE students SET first_name=?, last_name=?, department=?, semester=?, gender=?, email=?, phone=?, dob=?, 
            blood_group=?, religion=?, father_name=?, father_occupation=?, mother_name=?, mother_occupation=?, 
            family_income=?, contact_student=?, contact_father=?, contact_mother=? WHERE usn=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss", $firstName, $lastName, $department, $semester, $gender, $email, $phone, 
        $dob, $bloodGroup, $religion, $fatherName, $fatherOccupation, $motherName, $motherOccupation, 
        $familyIncome, $contactStudent, $contactFather, $contactMother, $usn);
    
    if ($stmt->execute()) {
        echo "<p>Profile updated successfully!</p>";
    } else {
        echo "<p>Error updating profile: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 20px;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container h2 {
            text-align: center;
            color: #4682B4;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
            display: block;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #4682B4;
            color: white;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #376a8b;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required>
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($student['semester']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($student['gender']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" required>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group" value="<?php echo htmlspecialchars($student['blood_group']); ?>" required>
            </div>
            <div class="form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($student['religion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="father_occupation">Father's Occupation</label>
                <input type="text" id="father_occupation" name="father_occupation" value="<?php echo htmlspecialchars($student['father_occupation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mother_occupation">Mother's Occupation</label>
                <input type="text" id="mother_occupation" name="mother_occupation" value="<?php echo htmlspecialchars($student['mother_occupation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="family_income">Family Income</label>
                <input type="text" id="family_income" name="family_income" value="<?php echo htmlspecialchars($student['family_income']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_student">Student's Contact</label>
                <input type="text" id="contact_student" name="contact_student" value="<?php echo htmlspecialchars($student['contact_student']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_father">Father's Contact</label>
                <input type="text" id="contact_father" name="contact_father" value="<?php echo htmlspecialchars($student['contact_father']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_mother">Mother's Contact</label>
                <input type="text" id="contact_mother" name="contact_mother" value="<?php echo htmlspecialchars($student['contact_mother']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Save Changes">
            </div>
        </form>
    </div>
</body>
</html>
