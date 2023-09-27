<?php
session_start();
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source_account = trim($_POST['source_account']);
    $destination_account = trim($_POST['destination_account']);
    $amount = floatval($_POST['amount']);



    // Check if the source and destination accounts are the same
if ($source_account === $destination_account) {
  echo "<script>
      alert('Source and destination accounts cannot be the same.');
      window.location.href = '../transfer/transfer.html';
      </script>";
  exit;
}

    // Validate that the amount is positive
    if ($amount <= 0) {
        echo "<script>
            alert('Amount must be greater than 0. Please enter a valid amount.');
            window.location.href = '../transfer/transfer.html';
            </script>";
        exit;
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.html");
        exit;
    }

    // Get the user_id from the session
    $user_id = $_SESSION['user_id'];

    // Determine the tables based on the selected accounts
    $source_table = ($source_account === 'savings') ? 'savings' : 'checking';
    $destination_table = ($destination_account === 'savings') ? 'savings' : 'checking';

    // Query to retrieve the source account balance
    $balance_query = "SELECT balance FROM $source_table WHERE user_id = $user_id";
    $balance_result = mysqli_query($conn, $balance_query);

    if ($balance_result) {
        $balance_row = mysqli_fetch_assoc($balance_result);
        $source_balance = $balance_row['balance'];

        // Check if the user has sufficient balance to make the transfer
        if ($source_balance < $amount) {
            echo "<script>
                alert('Insufficient balance to make the transfer.');
                window.location.href = 'transfer.html';
                </script>";
            exit;
        }

        // Calculate new balances after transfer
        $new_source_balance = $source_balance - $amount;

        // Update the source account balance
        $update_source_query = "UPDATE $source_table SET balance = $new_source_balance WHERE user_id = $user_id";
        $update_source_result = mysqli_query($conn, $update_source_query);

        if ($update_source_result) {
            // Query to retrieve the destination account balance
            $destination_balance_query = "SELECT balance FROM $destination_table WHERE user_id = $user_id";
            $destination_balance_result = mysqli_query($conn, $destination_balance_query);

            if ($destination_balance_result) {
                $destination_balance_row = mysqli_fetch_assoc($destination_balance_result);
                $destination_balance = $destination_balance_row['balance'];

                // Calculate new balance for the destination account
                $new_destination_balance = $destination_balance + $amount;

                // Update the destination account balance
                $update_destination_query = "UPDATE $destination_table SET balance = $new_destination_balance WHERE user_id = $user_id";
                $update_destination_result = mysqli_query($conn, $update_destination_query);

                if ($update_destination_result) {
                    // Insert transaction records for both accounts
                    $source_transaction_query = "INSERT INTO $source_table (user_id, account_type, amount, transaction_date, balance) VALUES ($user_id, '$source_account', -$amount, NOW(), $new_source_balance)";
                    $destination_transaction_query = "INSERT INTO $destination_table (user_id, account_type, amount, transaction_date, balance) VALUES ($user_id, '$destination_account', $amount, NOW(), $new_destination_balance)";

                    $source_transaction_result = mysqli_query($conn, $source_transaction_query);
                    $destination_transaction_result = mysqli_query($conn, $destination_transaction_query);

                    if ($source_transaction_result && $destination_transaction_result) {
                        echo "<script>
                              alert('Transfer of $amount from $source_account to $destination_account was successful.');
                              window.location.href = '../transactions/transaction.php'; 
                            </script>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>