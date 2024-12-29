<?php
session_start();
if (!isset($_SESSION['examinerusername']) || !isset($_SESSION['examinercourse'])) {
    // Redirect to login page
    header("Location: examiner_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Exam</title>
</head>
<script>
        // JavaScript function to confirm deletion
        function confirmDeletion(examId) {
            if (confirm("Are you sure you want to delete this exam? This action cannot be undone.")) {
                // Redirect to the same script with the exam_id to delete
                window.location.href = "delete_exam.php?exam_id=" + examId;
            } else {
                // Redirect back to the previous page
                
            }
        }
    </script>
<body>
    <h1>Manage Exam</h1>
    <a href="examiner_dashboard.php"><--</a>
    <h2></h2>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = $_SESSION['examinercourse'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data from database
    $sql = "SELECT * FROM exams WHERE examinerusername = '" . $_SESSION['examinerusername'] . "'";
    $result = $conn->query($sql);

    if ($result &&$result->num_rows > 0) {
        // Output data of each row
        echo "  <table>
        <tr>
            <td>Exam Name</td> 
            <td>Total Marks</td>
            <td>Total question</td> 
            <td>Date of exam</td>
            <td>Time of exam</td>
            <td>add questions</td>
            <td>update</td>
            <td>delete</td>
        </tr>";
        while($row = $result->fetch_assoc()) {

      
            echo "
        <tr>
            <td>".$row['exam_name']."</td>
      
            <td>".$row['total_marks']."</td>
       
            <td>".$row['total_question']."</td>
       
            <td>".$row['dateofexam']."</td>
       
            <td>".$row['timeofexam']."</td>
             <td><a href='question_add.php?exam_id=" . $row['exam_id'] . "'>Add Question</a></td>
        <td><a href='update_exam.php?exam_id=" . $row['exam_id'] . "'>✏️</a></td>
        <td><button id='delete' onclick='confirmDeletion(".$row['exam_id'].")'>🗑️</button></td>
        </tr>";
           
        }
        echo "</table>";
    } else {
        echo "You have not created any exams yet.want to create one? <a href='create_exam.php'>Create Exam</a>";
    }
    $conn->close();
    ?>
</body>
</html>
