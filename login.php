<?php

// STARTS THE SESSION SO YOU THE $_SESSION VARIABLES ARE AVAILABLE
session_start();



// Database CONNECTION USING CONSTANTS
// THIS IS FOR THE PARAMETERS 
// DB_SERVER MEANING WHAT IS THE SERVER AND THE NEXT IS, 'localhost' // WHICH IS THE SERVER JUST THE SAME IN THE WEBSITE
// DB_DATABASE IS ABOUT WHAT IS THE NAME OF THE DATABASE IT IS THAT I CREATED, AND IN THE SQL I NAMED IT db_itelective
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'db_itelective');

// INSTEAD OF THIS THAT I DID IN THE SQLCONNECTION REGISTER 
// $con = mysqli_connect("localhost","root","","db_itelective"); 
// YOU CAN DEFINE IT FIRST OTHER THAN PUTTING IT IN INSIDE 
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// CHECK IF THE CONNECTION WILL BE SUCCESSFUL
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check THE USERNAME AND PASSWORD FROM THE POST DATA IN THE LOGIN.PHP FUNCTION    LOGINME()
    if (isset($_POST['username']) && isset($_POST['password'])) {
        
        $loginusername = mysqli_real_escape_string($con, $_POST['username']);
        $loginpassword = mysqli_real_escape_string($con, $_POST['password']);
        
        // CHECK IF THE USERNAME & PASSWORD IS EMPTY
        if (empty($loginusername) || empty($loginpassword)) {
            echo "Username and Password cannot be empty!";
            exit(); // IF EMPTY STOP 
        }

        // Prepared Statement to check if the username exists in the database
        //  Prepared Statement FOR THE QUERY
        // QUERY IS USED TO RETRIEVE DATA IN A TABLE IN A DATABASE
        // THE WHERE username = ?");  THE ? IS USED FOR THE DATA THAT WILL BE INPUTTED  IN THE $loginusername
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        // THIS IS NOW THE BINDING PARAMETER HENCE THE $stmt->bind_param("s", $loginusername); THE "s" IS THE TYPE OF PARAMETER IT IS,
        // IN THIS CASE IT IS "s" WHICH MEANS STRING AND THEN  $loginusername WHERE THEY WILL CHECK IF THAT username exists
        $stmt->bind_param("s", $loginusername);
        // EXECUTE THE QUERY
        $stmt->execute();
        // CREATE ANOTHER VARIABLE TO SAVE THE RESULTS IN THE QUERY, TO GET THE USERNAMES THAT IS IN THE DATABASE
        $result = $stmt->get_result();

        // CONDITION AFTER CHECKING IF A USERNAME EXIST
        // SO THE RESULT HAS THE QUERY OF THE DATABASE IF THERE IS A USERNAME IN THERE
        // THE NUM_ROWS IS USED TO HOW MANY ROWS IS THERE IN THE COLUMN IN THIS CONDITION THE ROWS IN THE $RESULT WHERE THE QUERY SAVED THE RESULTS
        // IF THE NUMBERS OF ROWS THAT THE RESULTS HAVE IS GREATER THAN ONE WHICH MEANS THERE IS A ROW IN THE $RESULT THAT HAS A USERNAME IN IT
        if ($result->num_rows > 0) {
            // FETCH THE USER DATA
            $row = $result->fetch_assoc(); 
            
            // NEED TO VERY THE HASHED PASSWORD FIRST
            // HASHED PASSWORD IS BASICALLY LIKE ENCRYPTED
            // IF THE $loginpassword THAT YOU PUT IN THE LOG IN PAGE IS THE SAME AS THE ONE IN THE
            // $row['password'] WHICH IS THE ROW IN THE DATABASE WHERE IT SAVES THE PASSWORD FOR THE USERNAME
            if (password_verify($loginpassword, $row['password'])) {
                
                // FOR SECURITY, REGERATE SESSION
                session_regenerate_id();

                // THESE ARE THE SESSION VARIABLES THAT WILL BE USED WHEN YOU ARE ONLINE/LOGGED IN
                // STORE THE ID AND THE USERNAME OF THE DATABASE IN THE $_SESSION
                // I WILL USE THE $_SESSION['username'] TO SHOW IN THE DASHBOARD
                // THE SESSION MONEY IS FOR THE MONEY THAT YOU HAVE ON THE BANK ACCOUNT
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['money'] = $row['money'];
                // REDIRECTS TO THE DASHBOARD AFTER THE LOG IN IS SUCCESSFUL
                header("Location: dashboard.php");
                exit();
            } else {
          
                echo "Invalid username or password!";
            }
        } else {
        
            echo "Invalid username or password!";
        }
        
        $stmt->close(); // Close the statement
    } else {
        echo "Please provide both username and password.";
    }
}

// Close the database connection
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign-Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #003366;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }


        .login-container {
            background-color: #123353;
            width: 90vw;
            max-width: 500px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            text-align: center;
        }


        h2 {
            color: white;
            margin-bottom: 20px;
            font-family:Georgia, 'Times New Roman', Times, serif;
        }


        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: white;
        }


        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s;
        }


        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #ff6600;
        }


        button {
            width: 100%;
            padding: 10px;
            background-color: #f5f5f5;
            color: rgb(0, 0, 0);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 15px;
            font-family:Georgia, 'Times New Roman', Times, serif;
        }


        button:hover {
            background-color: #ff4500;
        }


        .remember-me-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
        }


        .remember-me-container input {
            margin-right: 10px;
        }


        .remember-me-container label {
            color: white;
        }


        .social-media-login {
            display: flex;
            justify-content: space-between;
        }


        .social-media-login button {
            width: 48%;
            padding: 10px;
            border-radius: 5px;
            color: white;
        }

     
        #google-login-btn{
            width: fit-content;
        }
        #google-login-btn,
        #facebook-login-btn {
            background-color: #123353;
            margin-bottom: 5px;
             border-radius: 15px;
             cursor: pointer;
             border: 1px solid #ffffff;
             font-family:Georgia, 'Times New Roman', Times, serif;
             display: flex;
         text-align: center;
        }
        button:hover{
            opacity: 0.7;

        }
        button:active{
            transform: scale(0.95); 
        }
        i{
            margin-left: 10px;
           
        }
        #forgot-password,
        #forgot-user-id, #register {
            color: #f4f4f4;
            text-decoration: none;
            display: block;
            margin-bottom: 20px;
        }


        #forgot-password:hover,
        #forgot-user-id:hover, #register:hover {
            text-decoration: underline;
        }


        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }


        .modal-content {
            background-color: #123353;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }


        .modal-content h3 {
            margin-top: 0;
            color: white;
        }


        .modal-content button {
            background-color: #f5f5f5;
            color: rgb(0, 0, 0);
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }


        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }


        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }


        @media (max-width: 600px) {
            .login-container {
                padding: 30px;
            }


            button {
                padding: 8px;
                border-color: #610a10;
            }
        }
     
    </style>
</head>
<body>


<div class="login-container">
    <h2>Login</h2>
    <form id="login-form" method="POST">
        <label for="login-username">USER ID:</label>
        <input type="text" id="login-username" name="username" required><br><br>

        <label for="login-password">PASSWORD:</label>
        <input type="password" id="login-password" name="password" required><br><br>

        <div class="remember-me-container">
            <input type="checkbox" id="remember-me" name="remember-me">
            <label for="remember-me">Remember Me</label>
        </div>

        <!-- Button to trigger loginme() function -->
        <button type="button" id="sign-in-btn" onclick="loginme()">Sign In</button><br><br>

        <a href="#" id="forgot-user-id">Forgot User ID?</a>
        <a href="#" id="forgot-password">Forgot Password?</a>
        <a href="registration.php" id="register" >Register</a>

        <div class="social-media-login">
            <button type="button" id="google-login-btn">Login with Google<i class="bi bi-google"></i></button>
            <button type="button" id="facebook-login-btn">Login with Facebook<i class="bi bi-facebook"></i></button>
        </div>
    </form>
</div>


<div id="modal" class="modal">
    <div class="modal-content">
        <h3 id="modal-message"></h3>
        <button id="close-modal">OK</button>
    </div>
</div>


<script>


function loginme() {
     // Get username and password values from the form

    var loginusername = document.getElementById('login-username').value;  
    var loginpassword = document.getElementById('login-password').value; 

        //    TO MAKE SURE THAT IT IS NOT EMPTY
    if (!loginusername || !loginpassword) {
        showModal("Please provide both username and password.");
        return; // Exit the function to prevent form submission
        // STOP IF THE USERNAME OR PASSWORD IS EMPTY
    }

     // TO SUBMIT THE FORM
    var rememberMe = document.getElementById('remember-me').checked;

    document.getElementById("login-form").submit();
}
    
    document.getElementById('forgot-password').addEventListener('click', function() {
        showModal('Please contact support to reset your password.');
    });


    document.getElementById('forgot-user-id').addEventListener('click', function() {
        showModal('Please contact support to retrieve your user ID.');
    });


    document.getElementById('google-login-btn').addEventListener('click', function() {
       
        window.location.href = "https://accounts.google.com/signin";
    });


    document.getElementById('facebook-login-btn').addEventListener('click', function() {
       
        window.location.href = "https://www.facebook.com/login";
    });
</script>


</body>
</html>
