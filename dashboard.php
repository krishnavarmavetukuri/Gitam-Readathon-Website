<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];
$username = $user['UserName'];
$yearOfStudy = $user['YearOfStudy'];
$branchID = $user['BranchID'];

// Convert the numeric year to the ordinal year
$yearText = '';
switch ($yearOfStudy) {
    case 1:
        $yearText = '1st Year';
        break;
    case 2:
        $yearText = '2nd Year';
        break;
    case 3:
        $yearText = '3rd Year';
        break;
    case 4:
        $yearText = '4th Year';
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-READATHON Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 90vh;
            background-image: url('https://www.gitam.edu/sites/default/files/2022-08/bengalaru-campus.jpg');
            background-size: cover;
            background-position: center;
        }
        .header {
            background-color: #800000;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title h1 {
            font-size: 24px; /* Adjusting the size to match the welcome message */
            margin: 0;
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 10px;
        }
        .header .user-info span {
            margin-right: 20px;
        }
        .container {
            display: flex;
            flex-grow: 1;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .dialog-box {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: #ffffff;
        }
        .dialog-box button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-title">
        <h1>G-READATHON Dashboard</h1>
    </div>
    <div class="user-info">
        <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
        <img src="https://i.pinimg.com/originals/1f/8d/07/1f8d074a8237fa4a7d32f8d6f87874d1.png" alt="User Avatar">
    </div>
</div>

<div class="container">
    <div class="sidebar">
        <?php if ($yearOfStudy != 0): ?>
            <a href="currentYear.php"><button class="navigation-button">Current Year: <?php echo htmlspecialchars($yearText); ?></button></a>
        <?php endif; ?>
        <?php if ($yearOfStudy != 1): ?>
            <a href="previousYear.php"><button class="navigation-button">Previous Year</button></a>
        <?php endif; ?>
        <?php if ($yearOfStudy != 4): ?>
            <a href="upcomingYear.php"><button class="navigation-button">Upcoming Year</button></a>
        <?php endif; ?>
        <?php if ($yearOfStudy != 1): ?>
            <a href="programElective.php"><button class="navigation-button">Program Electives</button></a>
            <a href="openElective.php"><button class="navigation-button">Open Electives</button></a>
        <?php endif; ?>
        <img src="https://www.gitam.edu/themes/custom/gitam/logo.png" alt="GITAM Logo" class="left-image">
    </div>
    <div class="main-content">
        <h1>Welcome to the G-READATHON Dashboard</h1>
        <h3>Get ready for the last minute preparation.</h3>
        <div class="dialog-box">
            <button onclick="learn()">Learn</button>
            <button onclick="practice()">Previous Year Question Papers</button>
        </div>
    </div>
</div>

<script>
    function learn() {
        window.location.href = 'currentYear.php';
    }

    function practice() {
        window.open('https://digitalrepository.gitam.edu/old_question_papers', '_blank');
    }
</script>

</body>
</html>
