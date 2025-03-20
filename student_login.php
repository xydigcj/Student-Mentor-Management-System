<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Login Container -->
<div class="login-container">
    <div class="login-box">
        <h3>Login to your account</h3>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <div class="input-group">
                <input type="text" id="login-usn" name="usn" placeholder="Enter your USN" required>
            </div>

            <div class="input-group">
                <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-group">
                <input type="number" id="semester" name="semester" placeholder="Enter the semester (e.g., 1)" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <!-- Additional Link for Registration -->
        <p>If not registered? <a href="student_register.php" class="register-link">Fill registration form</a></p>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Student Portal. All Rights Reserved.</p>
</footer>

</body>
</html>
