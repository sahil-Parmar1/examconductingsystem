<?php
session_start();
$message="";
$error="";
// Check if user is not logged in (session variables not set)
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
$sql = "SELECT id,subject_name, subject_code FROM subjects";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
else
{
    $sql="CREATE TABLE subjects (id INT AUTO_INCREMENT PRIMARY KEY,subject_name VARCHAR(255) NOT NULL,subject_code VARCHAR(100) NOT NULL)";
     $conn->query($sql);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_subject'])) {
        $subject_name = trim($_POST['subject_name']);
        $subject_code = trim($_POST['subject_code']);

        // Validate subject name and code
        if (!empty($subject_name) && preg_match('/^[a-zA-Z ]+$/', $subject_name) && !empty($subject_code)) {
            $stmt = $conn->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");
            $stmt->bind_param("ss", $subject_name, $subject_code);

            if ($stmt->execute()) {
                $message = "Subject added successfully!";
            } else {
                $error = "Error adding subject.";
            }

            $stmt->close();
        } else {
            $error = "Invalid input. Subject name must contain only letters and spaces, and subject code is required.";
        }
    }

    if (isset($_POST['remove_subject'])) {
        $subject_id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $subject_id);

        if ($stmt->execute()) {
            $message = "Subject removed successfully!";
        } else {
            $error = "Error removing subject.";
        }

        $stmt->close();
    }

    if(isset($_POST['update']))
    {
        $subject_id = $_POST['subject_id'];
        $subject_name = trim($_POST['subject_name']);
        $subject_code = trim($_POST['subject_code']);

         if (!empty($subject_name) && preg_match('/^[a-zA-Z ]+$/', $subject_name) && !empty($subject_code)) {
            $stmt = $conn->prepare("UPDATE `subjects` SET `subject_code` = ?,`subject_name`=? WHERE id = ?");
            $stmt->bind_param("ssi", $subject_code,$subject_name,$subject_id);

            if ($stmt->execute()) {
                $message = "Subject updated successfully!";
            } else {
                $error = "Error adding subject.";
            }

            $stmt->close();
        } else {
            $error = "Invalid input no id is available";
        }

    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Remove Subject</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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

        .message {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .subject-list {
            margin-top: 20px;
        }

        .subject-list ul {
            list-style: none;
            padding: 0;
        }

        .subject-list li {
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            background: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .actions button.update {
            background: #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Subjects</h2>

        <?php if (isset($message)) { echo "<div class='message'>$message</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>

        <div class="subject-list">
            <h3>Subjects</h3>
            <ul>
                <?php foreach ($subjects as $subject) { ?>
                    <li>
                        <?php echo htmlspecialchars($subject['id'])." ".htmlspecialchars($subject['subject_name']) . " (" . htmlspecialchars($subject['subject_code']) . ")"; ?>
                        <div class="actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
                                <button type="submit" name="remove_subject">Remove</button>
                            </form>
                           
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <form method="POST" style="margin-top: 20px;">
            <div class="form-group">
                <label for="subject_name">Subject Name</label>
                <input type="text" id="subject_name" name="subject_name" placeholder="Enter subject name" required>
            </div>

            <div class="form-group">
                <label for="subject_code">Subject Code</label>
                <input type="text" id="subject_code" name="subject_code" placeholder="Enter subject code" required>
            </div>
            <div class="form-group">
                <label for="subject_id">Subject id[optional only for update the subject]</label>
                <input type="text" id="subject_id" name="subject_id" placeholder="Enter subject code">
            </div>
            <button type="submit" name="add_subject" class="btn">Add Subject</button><br><br>
            <button class="btn" name="update">Update</button>
            <h2><?php echo $message?></h2>
        </form>
    </div>
</body>
</html>
