<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .registration-form {
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 500px;
            overflow-y: auto;
            max-height: 90vh;
        }
        .registration-form h2 {
            color: #4682B4;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
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
        }
        .registration-form button:hover {
            background-color: #376a8b;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Student Registration</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="usn">USN</label>
                <input type="text" id="usn" name="usn" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" required>
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number" id="semester" name="semester" min="1" max="8" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group">
            </div>
            <div class="form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion">
            </div>
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" id="father_name" name="father_name">
            </div>
            <div class="form-group">
                <label for="father_occupation">Father's Occupation</label>
                <input type="text" id="father_occupation" name="father_occupation">
            </div>
            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" id="mother_name" name="mother_name">
            </div>
            <div class="form-group">
                <label for="mother_occupation">Mother's Occupation</label>
                <input type="text" id="mother_occupation" name="mother_occupation">
            </div>
            <div class="form-group">
                <label for="family_income">Family Income</label>
                <input type="number" id="family_income" name="family_income" step="0.01">
            </div>
            <div class="form-group">
                <label for="contact_student">Student Contact</label>
                <input type="text" id="contact_student" name="contact_student" required>
            </div>
            <div class="form-group">
                <label for="contact_father">Father's Contact</label>
                <input type="text" id="contact_father" name="contact_father">
            </div>
            <div class="form-group">
                <label for="contact_mother">Mother's Contact</label>
                <input type="text" id="contact_mother" name="contact_mother">
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
