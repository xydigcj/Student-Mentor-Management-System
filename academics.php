<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$usn = $_SESSION['user_usn']; // USN from session

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['result_file'])) {
    $target_dir = "uploads/results/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_name = basename($_FILES['result_file']['name']);
    $target_file = $target_dir . $usn . "_" . time() . "_" . $file_name;
    $upload_ok = true;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES['result_file']['size'] > 2000000) {
        echo "<p class='error'>File is too large. Maximum size is 2MB.</p>";
        $upload_ok = false;
    }

    if (!in_array($file_type, ['pdf', 'docx'])) {
        echo "<p class='error'>Only PDF and DOCX files are allowed.</p>";
        $upload_ok = false;
    }

    if ($upload_ok) {
        if (move_uploaded_file($_FILES['result_file']['tmp_name'], $target_file)) {
            echo "<p class='success'>File uploaded successfully.</p>";
            $conn = new mysqli("localhost", "root", "", "student_mentor_system");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO results (usn, file_path) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $usn, $target_file);

            if ($stmt->execute()) {
                echo "<p class='success'>Result saved in the database.</p>";
            } else {
                echo "<p class='error'>Error saving to database.</p>";
            }
            $stmt->close();
            $conn->close();
        } else {
            echo "<p class='error'>Error uploading file.</p>";
        }
    }
}

// Handle file deletion
if (isset($_POST['delete_file'])) {
    $file_to_delete = $_POST['file_to_delete'];
    $conn = new mysqli("localhost", "root", "", "student_mentor_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete file from the server
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
    }

    // Delete file record from the database
    $sql = "DELETE FROM results WHERE file_path = ? AND usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $file_to_delete, $usn);
    if ($stmt->execute()) {
        echo "<p class='success'>File deleted successfully.</p>";
    } else {
        echo "<p class='error'>Error deleting file from database.</p>";
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
    <title>Academics - Upload Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #393e46;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="file"], input[type="submit"] {
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #00adb5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #007f82;
        }
        .success { color: green; }
        .error { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #00adb5; color: white; }
        a { color: #007f82; text-decoration: none; }
        a:hover { text-decoration: underline; }
        form.delete-form { display: inline; }
    </style>
</head>
<body>
<div class="container">
    <h2>Upload Semester Results</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="result_file">Select Result File (PDF/DOCX, max 2MB):</label>
        <input type="file" name="result_file" id="result_file" required>
        <input type="submit" value="Upload Result">
    </form>

    <h3>Your Uploaded Results</h3>
    <table>
        <tr>
            <th>File Name</th>
            <th>Download</th>
            <th>Action</th>
        </tr>
        <?php
        $conn = new mysqli("localhost", "root", "", "student_mentor_system");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT file_path FROM results WHERE usn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $file_path = $row['file_path'];
                $file_name = basename($file_path);
                echo "<tr>
                        <td>" . htmlspecialchars($file_name) . "</td>
                        <td><a href='$file_path' download>Download</a></td>
                        <td>
                            <form class='delete-form' method='POST'>
                                <input type='hidden' name='file_to_delete' value='" . htmlspecialchars($file_path) . "'>
                                <input type='submit' name='delete_file' value='Delete'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No results uploaded yet.</td></tr>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </table>
</div>
</body>
</html>
