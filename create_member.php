<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin creation Form</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <div class='container'>
    <div class="form-container">
        <h2>Admin creation Form</h2><br>
                    <form action="create_member.php" method="POST">
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
                    <label for="semesters">Number of semesters</label>
                    <input type="number" id="semesters" name="semesters" required>
                </div>
                <div class="form-group">
                    <label for="admin-name">Admin Name:</label>
                    <input type="text" id="admin-name" name="admin_name" required>
                </div>
                <div class="form-group">
                    <label for="user-name">Username:</label>
                    <input type="text" id="user-name" name="user_name" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="re-password">Re-enter Password:</label>
                    <input type="password" id="re-password" name="re_password" required>
                </div>
                <button type="submit">Submit</button>
            </form>

                    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validate inputs
            $course = $_POST['course'] ?? '';
            $semesters = $_POST['semesters'] ?? 0;
            $adminName = $_POST['admin_name'] ?? '';
            $userName = $_POST['user_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $rePassword = $_POST['re_password'] ?? '';

            // Semesters validation
            if ($semesters <= 0 || $semesters > 10) {
                $errors[] = "Semesters must be between 1 and 10.";
            }

            // Admin Name validation
            if (!preg_match("/^[a-zA-Z]+$/", $adminName)) {
                $errors[] = "Admin Name must contain only letters.";
            }

            // Username validation
            if (!preg_match("/^[a-zA-Z0-9]+@[a-zA-Z0-9]+$/", $userName)) {
                $errors[] = "Username must be in the format 'admin@123'.";
            }

            // Password validation
            if (strlen($password) <= 4) {
                $errors[] = "Password must be more than 4 characters.";
            }

            // Confirm password match
            if ($password !== $rePassword) {
                $errors[] = "Passwords do not match.";
            }

            // If there are validation errors
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
                exit;
            }

            // Proceed with database operations
            $servername = "localhost";
            $dbUser = "root";
            $dbPassword = "";

            // Create connection
            $conn = new mysqli($servername, $dbUser, $dbPassword);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Create database for the course
            $sql = "CREATE DATABASE `$course`";
            if ($conn->query($sql) === TRUE) {
                $conn->select_db($course);

                // Create `admin_info` table
                $sql = "CREATE TABLE  `admin_info` (
                            `name` VARCHAR(25) NOT NULL,
                            `username` VARCHAR(25) NOT NULL,
                            `password` VARCHAR(255) NOT NULL,
                            `semesters` INT(10) NOT NULL,
                            `course` VARCHAR(25) NOT NULL
                        ) ENGINE=MyISAM";

                if ($conn->query($sql) === TRUE) {
                    // Insert admin data
                    $sql = $conn->prepare("INSERT INTO `admin_info` (`name`, `username`, `password`, `semesters`,`course`) VALUES (?, ?, ?, ?, ?)");
                    $sql->bind_param("sssis", $adminName, $userName, $password, $semesters,$course);

                    if ($sql->execute()) {
                        echo "<p style='color: green;'>Admin `$adminName` created successfully. Now you can log in as admin go to <a href='index.php'>Home page</a></p>";
                    } else {
                        echo "<p style='color: red;'>Error inserting row: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error creating table: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>course admin already exists: " . $conn->error . "</p>";
            }

            $conn->close();
        }
        ?>


    </div>
   
</body>
</html>
