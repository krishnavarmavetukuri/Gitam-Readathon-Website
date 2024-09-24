<?php
session_start();

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
$password = $_POST['password'];

// Check if the page was accessed via a form submission or a refresh
$isFormSubmitted = isset($_POST['registrationID']);

// Check if an error occurred during login and store it in session
if ($isFormSubmitted) {
    // Query to check the user
    $sql = "SELECT * FROM StudentDetails WHERE UPPER(RegistrationID) = ? AND Password = SHA2(?, 256)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $registrationID, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = true;
        header("Location: login.html");
    }

    $stmt->close();
}

$conn->close();
?>
