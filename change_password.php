<?php
session_start();

// Check if required parameters are present
if (!isset($_GET['id'], $_GET['type'], $_GET['course'])) {
    echo "Invalid request";
    exit;
}

$type = $_GET['type'];
$id = $_GET['id'];
$course = $_GET['course'];
$table = '';
$username = '';

// Determine the table and validate session
switch ($type) {
    case 'student':
        if (isset($_SESSION['username']) || isset($_SESSION['studentusername'])) {
            $table = 'student_info';
        } else {
            echo "Invalid request";
            exit;
        }
        break;
    case 'admin':
        if (isset($_SESSION['username'])) {
            $table = 'admin_info';
            $username = $_SESSION['username'];
        } else {
            echo "Invalid request";
            exit;
        }
        break;
    case 'examiner':
        if (isset($_SESSION['username']) || isset($_SESSION['examinerusername'])) {
            $table = 'examiner';
        } else {
            echo "Invalid request";
            exit;
        }
        break;
    default:
        echo "Invalid request";
        exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';
    $errors = [];

    // Validation
    if (empty($new_password) || empty($re_password)) {
        $errors[] = "Both fields are required.";
    } elseif ($new_password !== $re_password) {
        $errors[] = "Passwords do not match.";
    } elseif (!preg_match('/[A-Za-z]/', $new_password)) {
        $errors[] = "Password must contain at least one letter.";
    } elseif (!preg_match('/\d/', $new_password)) {
        $errors[] = "Password must contain at least one digit.";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    // If no errors, update the password
    if (empty($errors)) {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = $course;

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the statement based on type
        switch ($type) {
            case 'student':
                $stmt = $conn->prepare("UPDATE student_info SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $new_password, $id);
                break;
            case 'admin':
                $stmt = $conn->prepare("UPDATE admin_info SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $new_password, $username);
                break;
            case 'examiner':
                $stmt = $conn->prepare("UPDATE examiner SET password = ? WHERE examiner_id = ?");
                $stmt->bind_param("si", $new_password, $id);
                break;
            default:
                echo "Invalid request";
                exit;
        }

        // Execute the statement
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Password changed successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating password. Please try again.</p>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            padding: 20px;
        }
        form {
            display: inline-block;
            margin: 25px auto;
            padding: 60px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left;
        }
        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Change Password</h1>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="new_password">Enter New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        
        <label for="re_password">Re-enter Password:</label>
        <input type="password" name="re_password" id="re_password" required>
        
        <button type="submit">Change Password</button>
    </form>
</body>
</html>
