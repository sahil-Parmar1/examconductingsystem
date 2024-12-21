<?php
session_start();

// Check if user is not logged in (session variables not set)
if (!isset($_SESSION['examinerusername']) || !isset($_SESSION['examinercourse'])) {
    // Redirect to login page
    header("Location: examiner_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examiner Dashboard</title>
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

        .option h2 {
            color: #007bff;
        }
    </style>
</head>
<body>
<form action="examiner_dashboard.php" method='POST'>
<header>
    <h1>Welcome to examiner Dashboard, <?php echo htmlspecialchars($_SESSION['examinerusername']); ?>!</h1>
    <h2><?php echo htmlspecialchars($_SESSION['examinercourse']) ?> examination</h2>
    <h3><?php
            $servername = "localhost";
            $dbuser = "root";
            $dbpassword = "";
            $course=$_SESSION['examinercourse'];
            $examinerusername=$_SESSION['examinerusername'];
            $subject_name='';
            $subject_code='';
            $subject_id='';
            // Create database connection
            $conn = new mysqli($servername, $dbuser, $dbpassword);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
                $conn->select_db($course);
                $sql = "SELECT * FROM `examiner` WHERE `username` LIKE '$examinerusername'";
                                
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    // Fetch the row
                    $row = $result->fetch_assoc();
                    $subject_code=$row['subject_code'];
                    $subject_name=$row['subject_name'];
                    $subject_id=$row['subject_id'];
                    $_SESSION['subject_id']=$subject_id;
                    echo htmlspecialchars($row['subject_name'])." (".htmlspecialchars($row['subject_code']).")";
                }

                if($_SERVER['REQUEST_METHOD']=='POST')
                {
                   if(isset($_POST['logout']))
                   {
                      session_destroy();
                      header("Location: examiner_login.php");
                      exit;
                   }
                }
?></h3>
<h6><button id="logout" name="logout">Logout</button></h6>

</header>



<div class="content">
    <h2>Main Options</h2>
    <div class="main-options">

        <div class="option" onclick="window.location.href='create_exam.php';">
            <h2>Create Exam</h2>
            <p>add new exam for the student</p>
        </div>
        <div class="option" onclick="window.location.href='manage_exam.php';">
            <h2>Manage Exam</h2>
            <p>Manage the exiting exam</p>
        </div>
      
    </div>
    <div class="main-options">
   
        <div class="option" onclick="window.location.href='listofstudent.php';">
            <h2>List of student</h2>
            <p>see List of student with each details</p>
        </div>
    </div>
</div>
</Form>
</body>
</html>
