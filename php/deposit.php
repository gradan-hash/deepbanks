<?php
session_start();
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user_id from the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // User is not logged in, redirect to the login page
        header("Location: ../login/login.html");
        exit;
    }

    $account = $_POST['account'];
    $amount = floatval($_POST['amount']);

    // Validate that the amount is positive
    if ($amount <= 0) {
        echo "<script>
            alert('Amount must be greater than 0. Please go back and enter a valid amount.');
            window.location.href = '../deposit/deposit.html';
            </script>";
        exit;
    }

    // Determine the table based on the selected account
    $table = ($account === 'savings') ? 'savings' : 'checking';

    // Insert the transaction record with user_id
    $query = "INSERT INTO `$table` (user_id, account_type, amount, transaction_date) VALUES ($user_id, '$account', $amount, NOW())";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
              alert('Deposit of $amount successfully made to your $account account with transaction ID: " . mysqli_insert_id($conn) . "');
              window.location.href = '../transactions/transaction.html'; 
            </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
