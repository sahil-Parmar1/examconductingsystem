<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>examiner Login</title>
    <link rel="stylesheet" href="../admin/style/admin_login.css">
</head>
<body>
    <div class="container">
        <h1>Examiner Login</h1>
        <form action="examiner_login.php" method="POST">
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
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" pattern="[a-zA-Z0-9]+@[a-zA-Z0-9]+" title="Enter a valid username in the format 'admin@123'" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"  required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <?php
            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                // Retrieve POST data
                $course = $_POST["course"];
                $username = $_POST["username"];
                $password = $_POST["password"];

                // Database connection details
                $servername = "localhost";
                $dbuser = "root";
                $dbpassword = "";

                // Create database connection
                $conn = new mysqli($servername, $dbuser, $dbpassword);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                try {
                    // Select the database
                    $conn->select_db($course);

                    // SQL query to fetch admin details
                    $sql = "SELECT * FROM `examiner` WHERE `username` = '$username'";
                    
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        // Fetch the row
                        $row = $result->fetch_assoc();

                        // Verify the password
                        if ($row['password'] === $password) {
                            session_start();
                            $_SESSION['examinerusername']=$username;
                            $_SESSION['examinercourse']=$course;
                            header("Location: examiner_dashboard.php");
                            exit;
                        } else {
                            echo "Password does not match.";
                        }
                    } else {
                        echo "No examiner found with username: $username on course: $course.";
                    }
                } catch (Exception $e) {
                    echo "Error: Unable to process the request. " . $e->getMessage();
                } finally {
                    // Close the connection
                    $conn->close();
                }
            }
            ?>
    </div>
</body>
</html>
