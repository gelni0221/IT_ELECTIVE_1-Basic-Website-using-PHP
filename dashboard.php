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
 $id =  $_SESSION['user_id']; // STORE THE ID TOO FOR THE TRANSACTIONS
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #06203a;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }


        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0a2945e1;
            width: 100%;
            position: absolute;
            top: 0;
            right: 0;
            font-size: 18px;
            padding: 5px;
        }


        .top-bar .dropdown {
            position: relative;
            display: inline-block;
        }
        

        .top-bar .dropdown:hover .dropdown-content {
            display: block;
            box-shadow: none;
            opacity: 1;
        }


        .top-bar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #ffffff;
            min-width: 100px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
        }
        .right-elements {
    display: flex; /* Use flex to keep elements in line */
    margin-left: auto; /* Push this section to the right */
}

        .top-bar .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
        }


        .top-bar .dropdown-content a:hover {
            background-color: #ddd;
           
        }


        .top-bar div {
            padding: 10px 15px;
        }


        .top-bar div a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }


        .top-bar div a:hover {
            background-color: #e96d01;
            border-radius: 5px;
        }
       
        .middle-bar {
            display: flex;
            justify-content:space-between;
            align-items: center;
            padding: 20px;
            background-color: #06203a;
            width: 100%;
            margin-top: 60px;
        }

        .middle-bar .search-bar{
            justify-content: center;
            align-items: center; 
        }
        .middle-bar img {
            height: 100px;
            max-width: 120px;
            width: auto;
            margin-left: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }


        .middle-bar img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }


        .middle-bar .bank-name {
            margin-right: 120px;
        }


        .middle-bar .search-bar {
            flex-grow: 1;
            margin-right: 2px;
            margin-left: 5;
            max-width: 300px;
            width: 60%;
        }


        .middle-bar .search-bar input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ff6600;
            border-radius: 5px;
        }


        .middle-bar .apply-loans {
            margin-left: 50px;
        }


        .middle-bar .apply-loans a {
            padding: 10px 20px;
            background-color: #ff6600;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }


        .middle-bar .apply-loans a:hover {
            background-color: #e96d01;
            transform: scale(1.05);
            opacity: 0.7;
        }


        .nav-bar {
            background-color:  #06203a;
            display: flex;
            flex-direction: column;
            padding: 15px;
            color: white;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }


        .nav-bar a {
            color: white;
            text-decoration: none;
            padding: 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }


        .nav-bar a:hover {
            opacity: 0.7;
        }


        .main-content {
            margin-left: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
            width: calc(100% - 220px);
            padding: 0 20px;
            align-items: center;
            height: auto;

        }


            .rectangle {
        background-color: #003366;
        margin-bottom: 20px;
        width: 100%;
        height: 800px;
        overflow: hidden; 
        position: relative; 
        text-align: right;
    }

    .imageswithdescription h1 {
    text-align: left; 
    margin-bottom: 20px; 
   padding-right: 250px;
}
        .rectangle img {
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            position: absolute; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
        }

            .grid-container {
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            padding: 20px;
          
        }
        .grid-item {
            width: 100%;
            height: 500px; 
            color: white; 
            padding: 20px;
            overflow: hidden;
            position: relative; 
           
          
        }
        .grid-item h1,
        .grid-item p {
            margin: 0; 
            text-align: center; 
            margin-bottom: 30px;
            align-content: center;
            margin-top: 60px;
        }
        .grid-item p{
            font-size: larger;
        }
        .grid-item img {
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            object-position: center; 
            position: absolute; 
            top: 0; 
            left: 0; 
        }   
        
        
        button:hover{
            opacity: .1;
        }
        h1{
            margin-left: 200px;
        }
 
        span:hover{
            opacity: 0.7;
        }
        #logout:hover{
            opacity: 0.7;
        }
        .text-overlay {
        position: absolute; 
        bottom: 10px; 
        left: 50%; 
        transform: translate(-50%);
        color: rgb(51, 48, 48); 
        font-size: 24px;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 10px;
        border-radius: 20px; 
        width: 900px;
       
}
    </style>
</head>
<body>
    <!-- #003366 -->

<div class="top-bar">
    <div class="bank-name">
        <h1><i class="bi bi-bank " alt="Bank Logo"></i> LNSN Online Banking</h1>
    </div>
    <div class="right-elements">
    <div class="dropdown">
        <span>About Us</span>
        <div class="dropdown-content">
            <a href="#">Our Mission</a>
        </div>
    </div>
    <div class="dropdown">
        <span>Branches</span>
        <div class="dropdown-content">
            <a href="#">Quezon City</a>
        </div>
    </div>
    <div><a id="logout" href="logout.php">Logout</a></div>
</div>
</div>


<div class="nav-bar">
    <a href="balance-inquiry.php">Balance Inquiry</a>
    <a href="transfer-payment.php">Transfer/Payment</a>
    <a href="account-summary.php">Account Summary</a>
</div>


<div class="main-content">
    <div class="middle-bar">
        <div class="search-bar">
            <input type="text" placeholder="Search...">
        </div>
    </div>
   <div class="imageswithdescription">
   <h1>Welcome,<?php echo htmlspecialchars($username); ?></h1>
    <!-- <div class="rectangle">
        <img src="https://img.freepik.com/free-photo/banking-saving-money-management-account-concept_53876-124787.jpg?t=st=1730305680~exp=1730309280~hmac=93591fb72c587f0defa19a2c7a13aad64ddae69b85c5b53ae4ffb45ff886bbae&w=1060"
        alt="Business Image"
        >
        <div class="text-overlay">At LNSN Online Banking, we are dedicated to providing exceptional banking services tailored to meet your needs. Our mission is to empower individuals and businesses with secure and innovative financial solutions. With a focus on customer satisfaction, we offer a wide range of services, including personal and business accounts, loans, and investment options. Our commitment to community involvement and financial education sets us apart as a leader in the banking industry. Experience convenience, security, and personalized service with LNSN Online Banking—where your financial success is our priority.</div>
    </div> -->

    <div class="grid-container">
        <div class="grid-item">
            <h1>At LNSN Online Banking,</h1>
            <p>We are dedicated to providing exceptional banking services tailored to meet your needs. Our mission is to empower individuals 
                and businesses with secure and innovative financial solutions. With a focus on customer satisfaction, we offer a wide range of services, 
                including personal and business accounts, loans, and investment options.</p>
        </div>
        <div class="grid-item">
            <img src="https://images.unsplash.com/photo-1601597111158-2fceff292cdc?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA
                        3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
        </div>
        <div class="grid-item">
            <img src="https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1">
        </div>
        <div class="grid-item">
            <h1>Your Privacy is Our Commitment,</h1>
            <p> Our commitment to privacy, community involvement and financial education sets us apart as a leader in the banking industry. 
                Experience convenience, security, and personalized service with LNSN Online Banking—where your financial success is our priority. 
                We prioritize your security by employing advanced encryption technologies, multi-factor authentication, 
                and continuous monitoring to protect your financial information. Your safety is our top priority, and we strive to maintain 
                a secure banking environment that you can trust.
            </p>
        </div>
        </div>
    </div>
</div>
</div>

</body>
</html>




