<?php
session_start();
if (!isset($_SESSION['examinerusername']) || !isset($_SESSION['examinercourse'])) {
    // Redirect to login page
    header("Location: examiner_login.php");
    exit;
}
if(!isset($_SESSION['exam_id']))
{
    header("Location: examiner_dashboard.php");
    exit;
}
$exam_id=$_SESSION['exam_id'];
echo "$exam_id";

// Establish database connection
$conn = new mysqli('localhost', 'root', '', $_SESSION['examinercourse']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql="SELECT *FROM exams WHERE exam_id=$exam_id";
$result=$conn->query($sql);
$examname='';
$examiner=$_SESSION['examinerusername'];
$subject_id='';
$total_questions='';
$perquestion_mark='';
$total_marks='';
$timeofexam='';
$dateofexam='';
$timelimit='';
$negative_mark='';
$subject_name='';
$subject_code='';
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $examname=$row['exam_name'];
    $examiner=$row['examinerusername'];
    $subject_id=$row['subject_id'];
    $total_questions=$row['total_question'];
    $perquestion_mark=$row['perquestion_mark'];
    $total_marks=$row['total_marks'];
    $timeofexam=$row['timeofexam'];
    $dateofexam=$row['dateofexam'];
    $timelimit=$row['timelimit'];
    $negative_mark=$row['negative_mark'];
    $sql="SELECT *FROM  subjects WHERE id=$subject_id";
    $result=$conn->query($sql);
    if($result && $result->num_rows>0)
    {
        $row=$result->fetch_assoc();
        $subject_name=$row['subject_name'];
        $subject_code=$row['subject_code'];
    }
} else {
    echo "No exam found with the name '$examname'.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>question add</title>
  <style>
    .dynamic-div {
      display: none;
      margin-top: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <h1>Add Questions</h1>
  <h2><?php echo "subject".htmlspecialchars($subject_name)?></h2>
  <h2><?php echo "subject code".htmlspecialchars($subject_code)?></h2>
  <h2><?php echo "examiner".htmlspecialchars($examiner)?></h2>
  <h2><?php echo "total marks".htmlspecialchars($total_marks)?></h2>
  <h2><?php echo "total questions".htmlspecialchars($total_questions)?></h2>
  <h2><?php echo "per question mark".htmlspecialchars($perquestion_mark)?></h2>
  <h2><?php echo "negative mark".htmlspecialchars($negative_mark)?></h2>
  <h2><?php echo htmlspecialchars($timelimit)?></h2>
  <h2><?php echo htmlspecialchars($timeofexam)?></h2>
  <h2><?php echo htmlspecialchars($dateofexam)?></h2>
  <select id="dropdownMenu">
    <option value="" disabled selected>Select an Questions type:</option>
    <option value="d1">D1</option>
    <option value="d2">D2</option>
    <option value="d3">D3</option>
  </select>

  <div id="d1" class="dynamic-div">This is Div 1</div>
  <div id="d2" class="dynamic-div">This is Div 2</div>
  <div id="d3" class="dynamic-div">This is Div 3</div>

  <script>
    const dropdownMenu = document.getElementById("dropdownMenu");
    const divs = document.querySelectorAll(".dynamic-div");

    dropdownMenu.addEventListener("change", function () {
      const selectedValue = this.value;

      // Hide all divs
      divs.forEach((div) => {
        div.style.display = "none";
      });

      // Show the selected div
      const selectedDiv = document.getElementById(selectedValue);
      if (selectedDiv) {
        selectedDiv.style.display = "block";
      }
    });
  </script>

</body>
</html>
