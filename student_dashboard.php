<?php
session_start();
if (!isset($_SESSION['studentusername']) || !isset($_SESSION['studentcourse']) || !isset($_SESSION['studentsemester'])) {
    header("Location: student_login.php");
    exit;
}
$name=$_SESSION['studentusername'];
$course=$_SESSION['studentcourse'];
$semester=$_SESSION['studentsemester'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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

        .content {
            margin-left: 100px;
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
    </style>
</head>
<body>
    <header>
        <h1>Student Dashboard</h1>
        <h2>Welcome, <?php echo $name; ?>!</h2>
        <h3>Course: <?php echo $course; ?></h3>
        <h3>Semester: <?php echo $semester; ?></h3>
        <h6><button name="logout">Logout</button></h6>
    </header>
    <div class="content">
        
        <div class="main-options">
            <div class="option" onclick="location.href='view_results.php'">
                <h2>attend exam</h2>
            </div>
            <div class="option" onclick="location.href='view_results.php'">
                <h2>see exam</h2>
            </div>
           <div class="option" onclick="location.href='student_logout.php'">
                <h2>result</h2>
            </div>
        </div>
    </div>
</body>
</html>