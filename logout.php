<?php
// TO START THE SESSION
session_start();

// UNSETS ALL THE SESSION VARIABLES
session_unset();

// DESTROYS THE SESSION
session_destroy();

// REDIRECT TO LOG IN PAGE
header("Location: login.php");
exit();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #002142;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }


        .logout-container {
            background-color: #123353;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
        }


        h2 {
            color: #ffffff;
        }


        a {
            color: #eae9e8;
            text-decoration: none;
            font-weight: bold;
        }


        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>


<div class="logout-container">
    <h2>You have been logged out successfully!</h2>
    <a href="login.php">Click here to log back in</a>
</div>


</body>
</html>


