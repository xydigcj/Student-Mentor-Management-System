<?php
// Password to be hashed
$password = 'asdf';

// Generate the hashed password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Output the hashed password
echo "Hashed password: " . $hashed_password;
?>
