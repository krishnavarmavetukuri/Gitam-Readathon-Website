<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];
$yearOfStudy = $user['YearOfStudy'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gitamdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects based on the year of study
$sql = "SELECT SubjectID, SubjectName FROM Subject WHERE Year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $yearOfStudy);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            background-color: #800000;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .content {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            position: relative;
        }
        .subject-button {
            display: block;
            margin-bottom: 10px;
            padding: 8px 16px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .subject-button:hover {
            background-color: #600000;
        }
        .back-button-container {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #800000;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .back-button {
            color: white;
            font-size: 20px;
            text-decoration: none;
            line-height: 30px;
        }
        .back-button:hover {
            color: #f0f0f0;
        }
    </style>
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</head>
<body>

<div class="header">
    <h1>NOTES</h1>
</div>

<div class="content">
    <h2>Subjects</h2>
    <!-- Subject Buttons -->
    <?php foreach ($subjects as $subject): ?>
        <button class="subject-button" onclick="navigateTo('subjects.php?subject_id=<?php echo htmlspecialchars($subject['SubjectID']); ?>')">
            <?php echo htmlspecialchars($subject['SubjectName']); ?>
        </button>
    <?php endforeach; ?>

    <!-- Back Button Container -->
    <div class="back-button-container" onclick="navigateTo('dashboard.php')">
        <div class="back-button">&#9664;</div>
    </div>
</div>

</body>
</html>
