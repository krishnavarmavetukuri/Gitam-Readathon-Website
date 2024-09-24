<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gitamdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user input
$registrationID = strtoupper($_POST['registrationID']);
$email = $_POST['email'];
$answer1 = $_POST['answer1'];
$answer2 = $_POST['answer2'];
$answer3 = $_POST['answer3'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

// Check if new password and confirm password match
if ($newPassword !== $confirmPassword) {
    echo "New password and confirm password do not match.";
    exit();
}

// Query to check if the user exists and answers match
$sql = "SELECT * FROM Questions WHERE UPPER(RegistrationID) = ? AND Email = ? AND Answer1 = ? AND Answer2 = ? AND Answer3 = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $registrationID, $email, $answer1, $answer2, $answer3);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists and answers match, allow password reset
    // Hash the new password
    $hashedPassword = hash('sha256', $newPassword);

    // Update the password in the database
    $updateSql = "UPDATE StudentDetails SET Password = ? WHERE UPPER(RegistrationID) = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $hashedPassword, $registrationID);
    $updateStmt->execute();
    $updateStmt->close();

    // Password reset successful
    echo "Password successfully reset. Please <a href='login.html'>login</a> with your new password.";
} else {
    // Answers do not match or user does not exist
    echo "Invalid details provided. Please try again.";
}

$stmt->close();
$conn->close();
?>
