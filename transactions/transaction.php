<?php
session_start();
include_once "db.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Query to retrieve savings transactions for the user
$savings_query = "SELECT * FROM savings WHERE user_id = $user_id";
$savings_result = mysqli_query($conn, $savings_query);

// Query to retrieve checking transactions for the user
$checking_query = "SELECT * FROM checking WHERE user_id = $user_id";
$checking_result = mysqli_query($conn, $checking_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Records</title>
    <link rel="stylesheet" type="text/css" href="transaction.css" />
</head>
<body>
    <section class="transaction">
        <section class="transaction-inner">
            <h1>Transaction records</h1>

            <h2>Savings Account Transactions</h2>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($savings_result)) {
                        echo "<tr>";
                        echo "<td>" . $row['transaction_id'] . "</td>";
                        echo "<td>" . '$' . number_format($row['amount'], 2) . "</td>";
                        echo "<td>" . $row['transaction_date'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h2>Checking Account Transactions</h2>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($checking_result)) {
                        echo "<tr>";
                        echo "<td>" . $row['transaction_id'] . "</td>";
                        echo "<td>" . '$' . number_format($row['amount'], 2) . "</td>";
                        echo "<td>" . $row['transaction_date'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </section>
</body>
</html>
