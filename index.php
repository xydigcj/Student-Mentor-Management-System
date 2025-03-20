<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Mentor Management System</title>
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<main class="container">
    <h1>Welcome to the Student Mentor Management System</h1>

    <div class="button-group">
        <!-- Student Button -->
        <button onclick="window.location.href='student_login.php'" aria-label="Go to Student Section">
            <i class="fas fa-user-graduate"></i>
            <span>Student</span>
        </button>

        <!-- Mentor Button -->
        <button onclick="window.location.href='mentor_login.php'" aria-label="Go to Mentor Section">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Mentor</span>
        </button>

        <!-- Faculty Button -->
        <button onclick="window.location.href='faculty_login.php'" aria-label="Go to Faculty Section">
            <i class="fas fa-user-tie"></i> <!-- Faculty Icon -->
            <span>Faculty</span>
        </button>
    </div>
</main>

<footer>&copy; 2024 Student Mentor Management System. All Rights Reserved.</footer>

</body>
</html>
