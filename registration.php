<?php

$con = mysqli_connect("localhost","root","","db_itelective");
// CHECK CONNECTION
if (mysqli_connect_errno())
{
    die("Connection failed: " . mysqli_connect_error());
}
else {
    echo "Connection successful!";
}

        // CREATE 3 VARIABLES JUST LIKE IN REGISTRATION AND DO THE CONDITIONS AGAIN, $con IS THE CONNECTION NAME AND $_POST [] IS THE VARIABLE YOU CREATED IN REGISTRATION SCRIPT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm-password']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Check if the password is empty
    if (empty($password)) {
        echo "Password cannot be empty!";
        exit();
    }

    // Check if the username is empty
    if (empty($username)) {
        echo "Username cannot be empty!";
        exit();
    }

       // CHECK IF THE USERNAME IS TAKEN ALREADY
       $checkUsernameSql = "SELECT * FROM users WHERE username = '$username'";
       $result = mysqli_query($con, $checkUsernameSql);

       if (mysqli_num_rows($result) > 0) {
           echo "Username already exists!";
           exit(); // STOP THE PROCESS IF USERNAME EXISTS
       }
}

    // HASH THE PASSWORD FOR SECURITY
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // USE THE HASH PASSWORD HERE NOT THE $password PUT IT IN IN THE $INSERTSQL

    $initialMoney = 1000.00; // THE MONEY YOU WILL HAVE FROM THE BANK



    // $insertSql = "INSERT INTO THIS IS THE NAME OF DATABASE (THIS ARE THE NAME OF THE ROWS) VALUES (THIS IS THE VALUE IN THE REGISTRATION VARIABLES AND THE SQL CONNECTION ABOVE AND PUT IT IN HERE)";
    $insertSql = "INSERT INTO users (username, password, money) VALUES ('$username', '$hashedPassword','$initialMoney')";



    // This is a PHP function used to execute a query on a MySQL database using a connection established with mysqli_connect().
    // (mysqli_query(mysqli_connect(THAT YOU PUT, AND THEN THE $INSERTSQL THAT YOU MADE WITH THE DATABASE NAME, ROWS AND VALUES ETC)))
    if (mysqli_query($con, $insertSql)) {
        echo "Registration successful!";
        header("Location: login.php"); // IT WILL REDIRECT TO THE LOGIN PHP
        exit();
    } else {
        // ERROR
        echo "Error: " . mysqli_error($con);
    }

    // TO CLOSE THE CONNECTION
    mysqli_close($con);







?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #002142;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }


        .registration-container {
            background-color: #123353;
            width: 90vw;
            max-width: 400px;
            padding: 80px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            text-align: center;
        }


        h2 {
            color: #ffffff;
            margin-bottom: 20px;
        }


        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #f8f8f8;
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
            width: 50%;
            padding: 15px;
            background-color: #f7f6f5;
            color: rgb(0, 0, 0);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 15px;
            font-family:Georgia, 'Times New Roman', Times, serif;
        }


        button:hover {
            background-color: #535353;
        }


    </style>
</head>
<body>


<div class="registration-container">
    <h2>Register</h2>
    <!-- THE FORM SHOULD HAVE METHOD = POST BECAUSE I AM USING THE POST IN SQLCONNECTION.PHP THE ACTION = IS FOR WHERE THE FORM WILL GO AND IT WILL GO TO THE SQLCONNECTION WITH A METHOD OF POST -->
    <form id="registration-form" method="POST" action="sqlconnectionREGISTER.php">
        <label for="reg-username">USERNAME:</label>
        <input type="text" id="reg-username" name="username" required>


        <label for="reg-password">PASSWORD:</label>
        <input type="password" id="reg-password" name="password" required>


        <label for="reg-confirm-password">CONFIRM PASSWORD:</label>
        <input type="password" id="reg-confirm-password" name="confirm-password" required>


        <button type="button" id="register-btn" onclick="registerme()">Register</button>
    </form>
</div>


<script>
        // THIS FUNCTION GETS THE ID OF THE USERNAME AND THE PASSWORD IN THE FORMS AND STORES IT IN A PHP VARIABLE
    function registerme(){
        var username = document.getElementById('reg-username').value;  
        var password = document.getElementById('reg-password').value;  
        var confirmpassword = document.getElementById('reg-confirm-password').value;  

        //  event.preventDefault(); gagamitin para hindi mag refresh yung page or mag submit yung form pag pinindot yung register button
        event.preventDefault();

        // IF PASSOWRD DON'T MATCH RETURN BACK TO FORM
        if (password !== confirmpassword) {
            alert("Passwords do not match!");
            return;
            // IF PASSOWRD IS EMPTY RETURN BACK TO FORM
        } else if(password === "" || confirmpassword === ""){
            alert("The Password is empty!");
            return;
            // IF USERNAME IS EMPTY PLEASE ENTER A USERNAME
        }    else if (username === ""){
            alert("Please enter a Username!");
            return;
            //  USERNAME NEED TO BE 5-8 CHARS ONLY 
        
        }
        else if (username.length < 5 || username.length > 8) {
                        showModal("Username must be between 5 and 8 characters only");
                        return; 
                    }
        // ELSE REGISTRATION SUCCESSFUL AND GO TO ANOTHER FORM NOW
        else{
              // IF ALL VALIDATIONS PASS, SUBMIT THE FORM
        alert("Registration successful!");
        document.getElementById("registration-form").submit();  // SUBMIT THE FORM TO THE PHP


        // THE window.location.href = "login.php"; SHOULD BE PUT IN THE SQL SIDE OF THE PHP BECAUSE THIS JAVASCRIPT DOESN'T DO ANYTHING AND IT SHOULD ONLY HAPPEN IF A FORM HAS BEEN 
        // REGISTERED
        // window.location.href = "login.php";
        }
        
    }

</script>

</body>
</html>
