<?php

if($_SERVER['REQUEST_METHOD']=='POST')
{
  if(isset($_POST['submit']))
  {
    
    $course = $_POST['course'];
    $semester = $_POST['semester'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Database connection
    $conn = new mysqli("localhost", "root", "", $course);
    $sql = "SELECT * FROM `student_info` WHERE `username` = '$username' AND `password` = '$password' AND `semester` = '$semester'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        session_start();
        $_SESSION['studentusername']=$username;
        $_SESSION['studentcourse']=$course;
        $_SESSION['studentsemester']=$semester;
        header("Location: student_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }
    unset($_POST['course']);
  }
}
// Handle AJAX request
if (isset($_POST['course'])) {
    $course = $_POST['course'];
    echo "Course: $course";
    // Database connection
        $conn = new mysqli("localhost", "root", "", $course);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $semesters=0;
        $sql="SELECT * FROM `admin_info` WHERE `course` LIKE '".$course."'";
           $result = $conn->query($sql);
           if ($result && $result->num_rows > 0) {
               // Fetch the row
               $row = $result->fetch_assoc();
               $semesters=intval($row['semesters']); 
               
                for ($i = 1; $i <= $semesters; $i++) {
                    $options .= "<option value='$i'>Semester $i</option>";
                }  
                echo $options;
           }
           else
           {
               echo "No semesters found";
               exit;
           }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Dropdown</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <form method="POST" action="student_login.php">
       
        <div class="form-group"><h1>Student Login</h1></div>
        <div class="form-group">
        <label for="course">Select Course:</label>
                        <select id="course" name="course" required>
                            <option value="">--Select a Course--</option>
                            <option value="BCA">BCA</option>
                            <option value="BBA">BBA</option>
                            <option value="BA">BA</option>
                            <option value="MA">MA</option>
                            <option value="BCOM">BCOM</option>
                            <option value="MCOM">MCOM</option>
                        </select>
        </div>
        <div class="form-group">
    <label for="semester">Select Semester:</label>
            <select id="semester" name="semester">
                <option value="">--Select Semester--</option>
            </select>
        </div>
        <div class="form-group">
              <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        
        </div>
        <div class="form-group">
              <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        
        </div>
        <div class="form-group"> <button type="submit" name='submit'>Submit</button></div>
            

      
      

      
       
    </form>

    <script>
        $(document).ready(function () {
            $('#course').change(function () {
                const course = $(this).val();

                // Clear the semester dropdown
                $('#semester').html('<option value="">--Select Semester--</option>');

                if (course) {
                    $.ajax({
                        url: 'student_login.php', // The same page
                        method: 'POST',
                        data: { course: course },
                        success: function (response) {
                            $('#semester').html(response);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
