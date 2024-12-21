<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Actions</title>
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
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="window.location.href='create_member.php'">Create a New Member</button>
        <br>
        <div class="container">
        <h1>Sign In As</h1>
        <a href="admin_login.php" class="btn">Admin</a>
        <a href="examiner_login.php" class="btn">Examiner</a>
        <a href="student_login.php" class="btn">Student</a>
    </div>
    </div>
</body>
</html>
