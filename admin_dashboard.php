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
    <link rel="stylesheet" href="admin_dashboard.css">
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
