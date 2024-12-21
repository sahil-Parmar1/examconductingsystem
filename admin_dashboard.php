<?php
session_start();

// Check if user is not logged in (session variables not set)
if (!isset($_SESSION['username']) || !isset($_SESSION['course'])) {
    // Redirect to login page
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-bottom: 1px solid #474f57;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .main-options {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .option {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 45%;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .option:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .option h2 {
            color: #007bff;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2><?php echo htmlspecialchars($_SESSION['course']) ?> examination</h2>
</header>

<div class="sidebar">
    <a href="#">Settings</a>
    <a href="adremovesubject.php">Add & Update Subject</a>
    <a href="#">Change Password</a>
    <a href="#">Change Password for Students</a>
    <a href="#">Change Password for Examiners</a>
    <a href="#">Logout</a>
</div>

<div class="content">
    <h2>Main Options</h2>
    <div class="main-options">

        <div class="option" onclick="window.location.href='create_examiner.php';">
            <h2>Create Examiner</h2>
            <p>Manage and add new examiners for the system.</p>
        </div>
        <div class="option" onclick="window.location.href='create_student.php';">
            <h2>Create Student</h2>
            <p>Manage and add new students for the system.</p>
        </div>
      
    </div>
    <div class="main-options">
    <div class="option" onclick="window.location.href='listofexaminer.php';">
            <h2>List of examiner</h2>
            <p>see List of examiner with each details</p>
        </div>
        <div class="option" onclick="window.location.href='listofstudent.php';">
            <h2>List of student</h2>
            <p>see List of student with each details</p>
        </div>
    </div>
</div>

</body>
</html>
