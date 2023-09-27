<?php
session_start();
include_once "../php/db.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $account = trim($_POST['account']);
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

// Retrieve the current balance from the database
$balance_query = "SELECT balance FROM $table WHERE user_id = $user_id";
$balance_result = mysqli_query($conn, $balance_query);

if ($balance_result) {
    if (mysqli_num_rows($balance_result) == 0) {
        // User is new, initialize balance to 0
        $current_balance = 0;
    } else {
        $balance_row = mysqli_fetch_assoc($balance_result);
        $current_balance = $balance_row['balance'];
    }
} else {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Calculate the new balance after deposit
$new_balance = $current_balance + $amount;

// Update the balance in the correct table
$update_query = "UPDATE $table SET balance = $new_balance WHERE user_id = $user_id";
$update_result = mysqli_query($conn, $update_query);

if ($update_result) {
    // Insert the deposit transaction record into the correct table
    $transaction_query = "INSERT INTO $table (user_id, account_type, amount, transaction_date, balance) VALUES ($user_id, '$account', $amount, NOW(), $new_balance)";
    $transaction_result = mysqli_query($conn, $transaction_query);

    if ($transaction_result) {
        echo "<script>
              alert('Deposit of $amount successfully made to your $account account with transaction ID: " . mysqli_insert_id($conn) . "');
              window.location.href = '../transactions/transaction.php'; 
            </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

}
?>
