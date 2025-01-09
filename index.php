<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Modi College - Dashboard</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="wrapper">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <img src="gdmodiphoto.jpg" alt="GD Modi College Logo" class="logo">
                <div class="header-text">
                    <h1>GD Modi College</h1>
                    <h3>Palanpur</h3>
                </div>
            </header>

            <!-- Main Body -->
            <div class="main-body">
                <div class="card">
                    <h2>Sign in As</h2>
                    <div class="signin-options">
                        <div class="signincard" onclick="window.location.href='admin/admin_login.php'">
                        
                            <img src="images/adminlogo.png" alt="Admin Logo" class="icon">
                            <h3>Admin</h3>
                        </div>
                        
                        <div class="signincard" onclick="window.location.href='examiner/examiner_login.php'">
                            <img src="images/examinerlogo.jpg" alt="Examiner Logo" class="icon">
                            <h3>Examiner</h3>
                        </div>
                        
                        <div class="signincard" onclick="window.location.href='student/student_login.php'">
                            <img src="images/studentlogo.webp" alt="Student Logo" class="icon">
                            <h3>Student</h3>
                        </div>
                    </div>
                </div><br>
                <div class="createcard">
                    <h2>Or</h2>
                <div class="createnew">
                <div class="creatememebercard" onclick="window.location.href='create_member.php'">
                 <h3>Create a New Member</h3>
                 </div>
                </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; 2025 GD Modi College. All Rights Reserved.</p>
            </footer>
        </div>
    </div>
</body>
</html>
