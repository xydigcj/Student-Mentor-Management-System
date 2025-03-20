<?php
session_start();

if (!isset($_SESSION['faculty_logged_in']) || $_SESSION['faculty_logged_in'] !== true) {
    header("Location: faculty_login.php");
    exit();
}

$faculty_name = $_SESSION['faculty_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h2 {
            color: #007bff;
        }
        p {
            font-size: 1.2em;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
        }
        ul li a {
            text-decoration: none;
            font-size: 1.1em;
            color: white;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        ul li a:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($faculty_name); ?></h2>
        <p>Select a semester:</p>
        <ul>
            <?php for ($i = 1; $i <= 8; $i++): ?>
                <li><a href="attendance.php?semester=<?php echo $i; ?>">Semester <?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
</body>
</html>
