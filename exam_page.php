<?php
session_start();
if (!isset($_SESSION['studentusername']) || !isset($_SESSION['studentcourse']) || !isset($_SESSION['studentsemester'])) {
    header("Location: student_login.php");
    exit;
}
if (!isset($_GET['exam_id'])) {
    die("Exam ID is missing.");
}
$exam_id = $_GET['exam_id'];
$username=$_SESSION['studentusername'];
$course=$_SESSION['studentcourse'];
$semester=$_SESSION['studentsemester'];
$conn = new mysqli("localhost", "root", "", $course);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$duration = $_SESSION['end_time'] - time();
if ($duration < 0) {
    $duration = 0;
}
?>
<script>
    var duration = <?php echo $duration; ?>;
    function startTimer() {
        var timer = setInterval(function() {
            var minutes = Math.floor(duration / 60);
            var seconds = duration % 60;
            document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";
            duration--;
            if (duration < 0) {
                clearInterval(timer);
                document.getElementById("timer").innerHTML = "EXPIRED";
            }
        }, 1000);
    }
    window.onload = startTimer;
</script>
<h3 align="right">Exam end:</h3>
<div id="timer" align='right'></div>
