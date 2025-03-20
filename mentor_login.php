//mentor_login.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mentor Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h3>Login to your account</h3>
        <form action="login1.php" method="POST">
            <div class="input-group">
                <input type="text" id="login-ufn" name="ufn" placeholder="Enter your UFN" required>
            </div>
            <div class="input-group">
                <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p>If not registered? <a href="mentor_register.php" class="register-link">Fill registration form</a></p>
    </div>
</div>

<footer>
    <p>&copy; 2024 Mentor Portal. All Rights Reserved.</p>
</footer>

</body>
</html>
