<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .registration-form {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            box-sizing: border-box;
        }
        .registration-form h2 {
            color: #4682B4;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 35%;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 65%;
            padding: 8px;
            margin-left: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .registration-form button {
            width: 100%;
            padding: 10px;
            background-color: #4682B4;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
        }
        .registration-form button:hover {
            background-color: #376a8b;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>REGISTRATION FORM</h2>
        <form action="register1.php" method="POST">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>
            </div>
            <div class="form-group">
                <label for="ufn">UFN</label>
                <input type="text" id="ufn" name="ufn" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
