<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$subjectID = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;

if ($subjectID === null) {
    echo "Subject not specified!";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gitamdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subject name
$sql = "SELECT SubjectName FROM Subject WHERE SubjectID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $subjectID);
$stmt->execute();
$result = $stmt->get_result();
$subject = $result->fetch_assoc();

if (!$subject) {
    echo "Subject not found!";
    exit();
}

// Fetch units for the subject
$sql_units = "SELECT * FROM Units WHERE SubjectID = ? ORDER BY UnitNumber";
$stmt_units = $conn->prepare($sql_units);
$stmt_units->bind_param("s", $subjectID);
$stmt_units->execute();
$result_units = $stmt_units->get_result();

$units = [];
while ($unit_row = $result_units->fetch_assoc()) {
    $units[] = $unit_row;
}

// Fetch notes for the subject
$sql_notes = "SELECT * FROM Notes WHERE SubjectID = ? ORDER BY UnitNumber, VersionNumber";
$stmt_notes = $conn->prepare($sql_notes);
$stmt_notes->bind_param("s", $subjectID);
$stmt_notes->execute();
$result_notes = $stmt_notes->get_result();

$notes = [];
while ($note_row = $result_notes->fetch_assoc()) {
    $notes[] = $note_row;
}

// Fetch resources for the subject
$sql_resources = "SELECT * FROM Resources WHERE SubjectID = ? ORDER BY UnitNumber, TopicName";
$stmt_resources = $conn->prepare($sql_resources);
$stmt_resources->bind_param("s", $subjectID);
$stmt_resources->execute();
$result_resources = $stmt_resources->get_result();

$resources = [];
while ($resource_row = $result_resources->fetch_assoc()) {
    $resources[] = $resource_row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject['SubjectName']); ?></title>
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
            position: relative;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color:#e71313;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .content {
            flex: 1 1 30%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .unit-header {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .pdf-link {
            display: block;
            margin-bottom: 10px;
            padding: 8px 16px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }
        .pdf-link:hover {
            background-color: #600000;
        }
        .subtopic {
            margin-bottom: 10px;
        }
        .reference-link, .youtube-link {
            display: inline-block;
            margin-right: 10px;
            padding: 5px 10px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }
        .reference-link:hover, .youtube-link:hover {
            background-color: #600000;
        }
    </style>
</head>
<body>

<div class="header">
    <button class="back-button" onclick="window.history.back();">Back</button>
    <h1><?php echo htmlspecialchars($subject['SubjectName']); ?></h1>
</div>

<div class="container">
    <?php foreach ($units as $unit): ?>
        <div class="content">
            <div class="unit-header"><?php echo "Unit " . htmlspecialchars($unit['UnitNumber']) . " - " . htmlspecialchars($unit['UnitName']); ?></div>
            <?php foreach ($notes as $note): ?>
                <?php if ($note['UnitNumber'] == $unit['UnitNumber']): ?>
                    <a href="<?php echo htmlspecialchars($note['NoteLink']); ?>" class="pdf-link" target="_blank"><?php echo htmlspecialchars($note['NoteDescription']); ?> - Version <?php echo htmlspecialchars($note['VersionNumber']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($resources as $resource): ?>
                <?php if ($resource['UnitNumber'] == $unit['UnitNumber']): ?>
                    <div class="subtopic">
                        <strong><?php echo htmlspecialchars($resource['TopicName']); ?></strong><br>
                        <a href="<?php echo htmlspecialchars($resource['RefLink']); ?>" class="reference-link" target="_blank">Reference</a>
                        <a href="<?php echo htmlspecialchars($resource['YoutubeLink']); ?>" class="youtube-link" target="_blank">YouTube</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>


</body>
</html>
