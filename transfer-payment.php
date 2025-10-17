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

<?php
session_start(); // Start the session

// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'db_itelective');

// Establish the database connection
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// CONDITION THAT SETS IF THE USER ID AND MONEY IS NOT SET// MEANING THEY ARE NOT ONLINE REDIRECT THEM TO LOG IN 
if (!isset($_SESSION['user_id']) || !isset($_SESSION['money'])) {
    header("Location: login.php");
    exit();
}

// FORM SUBMISSION FROM THE TRANSFER-PAYMENT PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SETS THE USER AND THE AMOUNT THAT WILL BE TRANSFERRED
    if (isset($_POST['recipient-user']) && isset($_POST['transferamount'])) {
        $recipient_user = $_POST['recipient-user'];
        $transfer_amount = $_POST['transferamount'];

        //THIS IS THE USER ID OF THE LOGGED IN AND THIS ID WILL BE USED TO DEDUCT THE MONEY IN THE USER ITSELF BEFORE/AFTER TRANSFERRING THE MONEY TO ANOTHER USER
        $sender_user_id = $_SESSION['user_id']; 

        // QUERY TO SEE IF THERE IS USERNAME EXIST
        // THIS IS A PREPARED STATEMENT SO MANY MORE STEPS THAN NORMAL
        // THE USERNAME = ? 
        $recipient_check_query = "SELECT money FROM users WHERE username = ?";

        // PREPARE THE QUERY NOW , $con IS THE CONNECTION TO THE DATABASE AND RECIPIENT_CHECK_QUERY IS NOW FOR THE QUERY WHERE IT WILL TRY TO FIND THE USERNAME WE INPUTTED IN THE FORM
        $stmt = mysqli_prepare($con, $recipient_check_query);
        // $stmt IS THE PREPARED QUERY, THE 's'  IS FOR THE WHERE username = ? TO SHOW IT'S STRING 's'  AND THEN RECIPIENT_USER WHICH IS THE ONE WHAT WE INPUTTED IN THE FORM
        mysqli_stmt_bind_param($stmt, 's', $recipient_user);
        // WILL EXECUTE THE PREPARED STATEMENT NOW
        mysqli_stmt_execute($stmt);
        // PUT THE RESULT OF THE QUERY THAT IS IN THE $stmt IN THE $recipient_money
        mysqli_stmt_bind_result($stmt, $recipient_money);

        mysqli_stmt_fetch($stmt);
        // THEN CLOSE THE STATEMENT
        mysqli_stmt_close($stmt);

        // CHECK IF THE RECIPIENT EXIST
        if ($recipient_money !== null) {
            // CHECK IF THE MONEY IS LESS THAN OR EQUAL TO THE AMOUNT THAT WILL BE TRANSFERRED
            // IF YES PROCEED NOW TO DEDUCTING 
            if ($_SESSION['money'] >= $transfer_amount) {
                // DEDUCTING AMOUNT FROM THE SENDER FIRST
                $sender_update_query = "UPDATE users SET money = money - ? WHERE id = ?";

                // ANOTHER PREPARED STATEMENT AGAIN
                // NOW FOR THE MONEY
                $stmt = mysqli_prepare($con, $sender_update_query);
                // THE DI STANDS FOR THE DOUBLE AND INTEGER, THIS IS TO SPECIFY WHAT TYPE OF PARAMETERS ARE WE GONNA PUT IN THE VARIABLE
                // IN THE TRANSFER_AMOUNT IT WILL BE'D' WHICH MEANS DOUBLE(IT COULD BE FLOAT TOO), THE 'I' ,MEANS INTEGER FOR THE USER ID
                mysqli_stmt_bind_param($stmt, 'di', $transfer_amount, $sender_user_id);

                if (mysqli_stmt_execute($stmt)) {
                    // A QUERY THAT WILL CREDIT THE MONEY WE PUTTED IN THE FORM TO THE RECEPIENT NOW 
                    $recipient_update_query = "UPDATE users SET money = money + ? WHERE username = ?";
                    // ANOTHER PREPARED STATEMENT
                    $stmt = mysqli_prepare($con, $recipient_update_query);
                    // ANOTHER BINDING PARAMETERS NOW 'DS' WHICH MEANS DOUBLE FOR THE FIRST VARIABLE AND STRING FOR THE SECOND VARIABLE

                    mysqli_stmt_bind_param($stmt, 'ds', $transfer_amount, $recipient_user);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $_SESSION['money'] -= $transfer_amount; // UPDATE THE SESSION BALANCE SO THAT WE CAN SEE NEW CHANGED BALANCE

                        // REDIRECT BACK TO DASHBOARD WITH MESSAGE TRANSFER SUCCESFULL
                        $message = urlencode("Transfer successful");
                        header("Location: dashboard.php?message=$message");
                        exit(); // It's good practice to exit after a redirect
                    } else {
                        echo "Error updating recipient's balance.";
                    }
                } else {
                    echo "Error updating sender's balance.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Insufficient funds for this transfer.";
            }
        } else {
            echo "Recipient user not found.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

// Close the connection after all operations are done
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer/Payment</title>
    <style>
        body {
            font-family:Georgia, 'Times New Roman', Times, serif;
            background-color: #002142;
            padding: 20px;
        }
        .container {
            background-color: #123353;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: white;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #f4f1f1;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #08224d;
        }
        button {
            width: fit-content;
            padding: 10px;
            background-color: #ff6600;
            color: rgb(0, 0, 0);
            border: none;
            border-radius: 10px;
            font-family:Georgia, 'Times New Roman', Times, serif;
        }
        button:hover {
            opacity: 0.7;
            cursor: pointer;
        }
        nav {
            margin-top: 20px;
            text-align: center;
        }
        nav:hover{
            margin-top: 20px;
            text-align: center;
            opacity: 0.7;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #e8e8e8;
        }
        #transfer-form{
            display: flex; 
            flex-direction: column; 
        display: flex;
        align-items: center;
        justify-content: center; 
      }
    </style>
</head>
<body>


<div class="container">
    <h1>Transfer/Payment</h1>
    <form id="transfer-form" method="POST" action="sqlconnectionTRANSFER-PAYMENT.php">
        <label for="recipient-id">Recipient Username:</label>
        <input type="text" id="recipient-user" name="recipient-user" required>
       
        <label for="amount">Amount ($):</label>
        <input type="number" id="transferamount" name="transferamount" required>


        <button type="button" id="transfer-btn" onclick="transfer()">Transfer</button>
    </form>

    <div id="navbelow">
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
    <nav>
        <a href="balance-inquiry.php">Balance Inquiry</a>
    </nav>
</div>
</div>


<script>

      function transfer(){

        var recipient_user = document.getElementById('recipient-user').value;  
        var transfer_amount = parseFloat(document.getElementById('transferamount').value); 

                // THIS VALIDATES THAT THE AMOUNT YOU WILL BE PUT IN WILL BE NUMBERS
                if (isNaN(transfer_amount)) {
                        showModal("Please enter valid numbers for the Amount");
                        return; // EXIT FORM IF NOT VALID NUMBERS <:::::::::::

                if (transfer_amount ><?php echo $money; ?>) {
                        showModal("Transfer Amount Should not exceed the amount that you have!");
                        return; // EXIT FORM SUBMISSION NO NEED TO PUT THE SUBMIT INSIDE THE CONDITION STATEMENT
                 }   
                if (transfer_amount === 0) {
                        showModal("Please enter valid numbers for the Amount");
                        return; // EXIT FORM SUBMISSION NO NEED TO PUT THE SUBMIT INSIDE THE CONDITION STATEMENT
                    }   

                if (recipient_user.trim() === "") {
                        showModal("Please enter valid Username");
                        return; // EXIT FORM SUBMISSION NO NEED TO PUT THE SUBMIT INSIDE THE CONDITION STATEMENT
                    }   
                
                if (recipient_user.length < 3 || recipient_user.length > 8) {
                        showModal("Recipient username must be between 3 and 8 characters.");
                        return; // Exit function if the username length is invalid
                    }
      }

      document.getElementById("transfer-form").submit();
      }


  
</script>


</body>
</html>



