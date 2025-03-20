<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: mentor_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            color: #333;
            background-color: #f4f6f8;
        }

        .sidebar {
            width: 250px;
            background-color: #1a1a2e;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 30px;
            font-weight: 600;
            color: #00adb5;
        }

        .sidebar a {
            padding: 15px;
            color: #ddd;
            text-decoration: none;
            display: block;
            margin: 5px 0;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #00adb5;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .content h1 {
            font-size: 2.2em;
            color: #393e46;
            border-bottom: 2px solid #00adb5;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 100%;
            max-width: 600px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .form-container p {
            margin-bottom: 20px;
            font-size: 1.1em;
            color: #666;
        }

        label {
            font-size: 1.1em;
            color: #555;
            display: block;
            margin-bottom: 10px;
        }

        select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 1.1em;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            color: #555;
        }

        select:focus, button:focus {
            outline: none;
            border-color: #00adb5;
        }

        button {
            background-color: #00adb5;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #007a7f;
        }

        #attendance-table, #student-profile-details, #results-table, #feedback-results {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
                padding-top: 20px;
            }

            .sidebar h2 {
                font-size: 1.2em;
            }

            .content {
                margin-left: 60px;
            }

            .form-container {
                padding: 20px;
            }
        }
        /* Style the table */
.profile-table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Style for table rows */
.profile-table tr {
    border-bottom: 1px solid #ddd;
}

/* Style for table cells */
.profile-table td {
    padding: 10px;
    text-align: left;
    vertical-align: top;
}

/* Style for table labels (keys) */
.profile-table td strong {
    font-size: 16px;
    color: #333;
}

/* Style for table values */
.profile-table td {
    font-size: 14px;
    color: #555;
}

/* Optional: For better responsiveness */
@media (max-width: 600px) {
    .profile-table {
        width: 90%;
    }

    .profile-table td {
        font-size: 12px;
    }
}

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Mentor Dashboard</h2>
        <a href="#" id="attendance-link">Attendance</a>
        <a href="#" id="student-profile-link">View Student Profile</a>
        <a href="#" id="view-results-link">View Results</a>
        <a href="#" id="feedback-link">leave letters</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome to the Mentor Dashboard</h1>
        <p>Select an option from the sidebar to manage your students.</p>

        <!-- Attendance Section -->
        <div id="attendance-content" style="display: none;">
            <div class="form-container">
                <h2>Attendance Overview</h2>
                <p>Select a semester to view the attendance details of your students.</p>
                <form id="semester-form">
                    <label for="semester">Select Semester:</label>
                    <select name="semester" id="semester">
                        <option value="">Choose...</option>
                        <?php for ($i = 1; $i <= 8; $i++) { ?>
                            <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                    <button type="button" id="view-attendance">View Attendance</button>
                </form>
            </div>
            <div id="attendance-table"></div>
        </div>

       <!-- Student Profile Section -->
<div id="student-profile-content" style="display: none;">
    <div class="form-container">
        <h2>View Student Profile</h2>
        <p>View details of the selected student.</p>
        <label for="usn">Enter USN:</label>
        <input type="text" id="usn" placeholder="Enter USN">
        <button id="view-profile-btn">View Profile</button>
        <div id="student-profile-details"></div>
    </div>
</div>

        <!-- Results Section -->
        <div id="view-results-content" style="display: none;">
            <div class="form-container">
                <h2>View Results</h2>
                <p>Select a semester to view results.</p>
                <select name="semester" id="semester-results">
                    <option value="">Choose...</option>
                    <?php for ($i = 1; $i <= 8; $i++) { ?>
                        <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <button type="button" id="view-results-btn">View Results</button>
                <div id="results-table"></div>
            </div>
        </div>

        <!-- Feedback Collection Section -->
        <div id="feedback-content" style="display: none;">
            <div class="form-container">
                <h2>view request leave letters</h2>
                <p>view request leave letters for the current semester.</p>
                <label for="semester-feedback">Select Semester:</label>
                <select name="semester" id="semester-feedback">
                    <option value="">Choose...</option>
                    <?php for ($i = 1; $i <= 8; $i++) { ?>
                        <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <button type="button" id="collect-feedback">view leave letters</button>
                <div id="feedback-results"></div>
            </div>
        </div>

    </div>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Event listeners for sidebar links
            document.getElementById("attendance-link").addEventListener("click", function() {
                hideAllSections();
                document.getElementById("attendance-content").style.display = "block";
            });

            document.getElementById("student-profile-link").addEventListener("click", function() {
                hideAllSections();
                document.getElementById("student-profile-content").style.display = "block";
            });

            document.getElementById("view-results-link").addEventListener("click", function() {
                hideAllSections();
                document.getElementById("view-results-content").style.display = "block";
            });

            document.getElementById("feedback-link").addEventListener("click", function() {
                hideAllSections();
                document.getElementById("feedback-content").style.display = "block";
            });

            // Attendance handling
            document.getElementById("view-attendance").addEventListener("click", function() {
                const semester = document.getElementById("semester").value;
                if (!semester) {
                    alert("Please select a semester.");
                    return;
                }
                fetch(`fetch_attendance.php?semester=${semester}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("attendance-table").innerHTML = data;
                    });
            });

            document.getElementById("view-profile-btn").addEventListener("click", function() {
    const usn = document.getElementById("usn").value.trim();
    if (!usn) {
        alert("Please enter a valid USN.");
        return;
    }

    // Fetch the student's profile by USN
    // Fetch the student's profile by USN
fetch(`fetch_student_profile.php?usn=${encodeURIComponent(usn)}`)
    .then(response => response.json())  // Handle the response as JSON
    .then(data => {
        if (data) {
            let profileHtml = '<table class="profile-table"><tbody>';

            // Loop through each key-value pair in the fetched data
            for (const key in data) {
                let formattedKey = key.replace('_', ' ').toUpperCase(); // Format the key
                let value = data[key] ? data[key] : 'Not available'; // Handle missing data
                
                profileHtml += `
                    <tr>
                        <td><strong>${formattedKey}:</strong></td>
                        <td>${value}</td>
                    </tr>`;
            }

            profileHtml += '</tbody></table>';

            // Insert the formatted HTML into the student profile section
            document.getElementById("student-profile-details").innerHTML = profileHtml;
        } else {
            alert("Student profile not found.");
        }
    })
    .catch(error => {
        alert("Error fetching student profile: " + error.message);
    });
});



            

            // Results handling
           // Results handling (Updated to use USN)
document.getElementById("view-results-btn").addEventListener("click", function() {
    const semester = document.getElementById("semester-results").value;
    const usn = prompt("Enter the USN to fetch the results:");
    if (!usn) {
        alert("Please enter a valid USN.");
        return;
    }
    
    // Fetch results by USN and optional semester
    fetch(`fetch_results.php?usn=${encodeURIComponent(usn)}&semester=${encodeURIComponent(semester)}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById("results-table").innerHTML = data;
        });
});


            // Feedback handling
            document.getElementById("collect-feedback").addEventListener("click", function() {
                const semester = document.getElementById("semester-feedback").value;
                if (!semester) {
                    alert("Please select a semester.");
                    return;
                }
                fetch(`fetch_leave_letters.php?semester=${semester}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("feedback-results").innerHTML = data;
                    });
            });

            // Helper function to hide all sections
            function hideAllSections() {
                const sections = document.querySelectorAll('#attendance-content, #student-profile-content, #view-results-content, #feedback-content');
                sections.forEach(section => section.style.display = "none");
            }
        });
    </script>
</body>
</html>
