<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
    <div class="container">
        <h1>Admin Login</h1>
        <form action="admin_login.php" method="POST">
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
                <input type="password" id="password" name="password" minlength="4" title="Password must be at least 4 characters long" required>
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
                    $sql = "SELECT * FROM `admin_info` WHERE `username` = '$username'";
                    
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        // Fetch the row
                        $row = $result->fetch_assoc();

                        // Verify the password
                        if ($row['password'] === $password) {
                            session_start();
                            $_SESSION['username']=$username;
                            $_SESSION['course']=$course;
                            header("Location: admin_dashboard.php");
                            exit;
                        } else {
                            echo "Password does not match.";
                        }
                    } else {
                        echo "No admin found with username: $username on course: $course.";
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
