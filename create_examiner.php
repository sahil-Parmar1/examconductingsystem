<?php
session_start();
// Check if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', $_SESSION['course']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects from the database
$subjects = [];
$sql = "SELECT id, subject_name, subject_code FROM subjects";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    $_SESSION['error'] = "You need to add subjects before creating an examiner.";
    header("Location: adremovesubject.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $subject_id = $_POST['subject'];
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'];

        // Find selected subject details
        $subject_name = '';
        $subject_code = '';
        foreach ($subjects as $subject) {
            if ($subject['id'] == $subject_id) {
                $subject_name = $subject['subject_name'];
                $subject_code = $subject['subject_code'];
                break;
            }
        }

        // Ensure table exists
        $sql = "CREATE TABLE IF NOT EXISTS `examiner` (
            `examiner_id` INT NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(25) NOT NULL,
            `username` VARCHAR(25) NOT NULL,
            `password` VARCHAR(25) NOT NULL,
            `subject_id` INT NOT NULL,
            `subject_name` VARCHAR(25) NOT NULL,
            `subject_code` VARCHAR(25) NOT NULL,
            PRIMARY KEY (`examiner_id`)
        ) ENGINE = MyISAM";
        $conn->query($sql);

        // Insert data into the examiner table
        $sql = "INSERT INTO `examiner` (`name`, `username`, `password`, `subject_id`, `subject_name`, `subject_code`)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiss", $name, $username, $password, $subject_id, $subject_name, $subject_code);

        if ($stmt->execute()) {
            $_SESSION['success'] = "$name was added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add $name. Please try again.";
        }

        header("Location: create_examiner.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Examiner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .message {
            color: green;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function validateForm() {
            const name = document.getElementById('name').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const rePassword = document.getElementById('re_password').value;
            const subject = document.getElementById('subject').value;

            let isValid = true;

            // Validate name
            if (!/^[a-zA-Z ]+$/.test(name)) {
                document.getElementById('nameError').innerText = 'Name must contain only letters and spaces.';
                isValid = false;
            } else {
                document.getElementById('nameError').innerText = '';
            }

            // Validate username
            if (!/^[a-zA-Z]+@[0-9]+$/.test(username)) {
                document.getElementById('usernameError').innerText = 'Username must be in the format "xyz@123".';
                isValid = false;
            } else {
                document.getElementById('usernameError').innerText = '';
            }

            // Validate password
            if (password.length < 5) {
                document.getElementById('passwordError').innerText = 'Password must be at least 5 characters long.';
                isValid = false;
            } else {
                document.getElementById('passwordError').innerText = '';
            }

            // Validate re-password
            if (password !== rePassword) {
                document.getElementById('rePasswordError').innerText = 'Passwords do not match.';
                isValid = false;
            } else {
                document.getElementById('rePasswordError').innerText = '';
            }

            // Validate subject
            if (subject.trim() === '') {
                document.getElementById('subjectError').innerText = 'Subject is required.';
                isValid = false;
            } else {
                document.getElementById('subjectError').innerText = '';
            }

            return isValid;
        }
    </script>
</head>
<body>
<div class="form-container">
    <h2>Create Examiner</h2>
    <?php
    if (isset($_SESSION['success'])) {
        echo "<div class='message'>{$_SESSION['success']}</div>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<div class='error'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']);
    }
    ?>
    <form onsubmit="return validateForm();" method="POST" action="create_examiner.php">
        <div class="form-group">
            <label for="subject">Which subject is taught by the examiner?</label>
            <select name="subject" id="subject" required>
                <option value="">--Select Subject--</option>
                <?php
                foreach ($subjects as $row) {
                    echo "<option value='".htmlspecialchars($row['id'])."'>"
                        .htmlspecialchars($row['subject_name'])." (".htmlspecialchars($row['subject_code']).")</option>";
                }
                ?>
            </select>
            <div class="error" id="subjectError"></div>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <div class="error" id="nameError"></div>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <div class="error" id="usernameError"></div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="error" id="passwordError"></div>
        </div>

        <div class="form-group">
            <label for="re_password">Re-enter Password</label>
            <input type="password" id="re_password" name="re_password" required>
            <div class="error" id="rePasswordError"></div>
        </div>
        <button type="submit" class="btn" name="submit" id="submit">Submit</button>
    </form>
</div>
</body>
</html>
