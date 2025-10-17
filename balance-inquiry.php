<?php
session_start(); // STARTS THE SESSION SO YOU THE $_SESSION VARIABLES ARE AVAILABLE
//IF THE $_SESSION['user_id'] IS FALSE, MEANING THERE IS NO user that is logged in, THEN GO TO THE REDIRECT TO THE LOG IN PAGE AND EXIT
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // RTHEN GO TO THE REDIRECT TO THE LOG IN PAGE AND EXIT
    exit();
}

// Retrieve user information from session or database IN THIS CASE WE WILL RETRIEVE THE USERNAME AND THE MONEY OF THE USER
$username = $_SESSION['username']; // TO RETRIEVE THE USERNAME FROM SESSION 
 $money = $_SESSION['money']; // ALSO THE $money WHICH IS THE MONEY YOU HAVE FROM THE BANK
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Inquiry</title>
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #002142;
            padding: 20px;
        }
        .container {
            background-color: #123353;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: white;
        }
        .balance {
            font-size: 24px;
            color: #fcfcfc;
            text-align: center;
            margin-bottom: 20px;
        }
        nav {
            margin-top: 20px;
            text-align: center;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #f0f0ef;
        }
    </style>
</head>
<body>


<div class="container">
    <h1>Balance Inquiry</h1>
    <div class="balance">Total Balance: $<?php echo htmlspecialchars($money); ?></div>
   
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
        <a href="transfer-payment.php">Transfer/Payment</a>
    </nav>
</div>


</body>
</html>


