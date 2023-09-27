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
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .transaction {
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        .transaction-inner {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
          
        }

        h2 {
            font-size: 20px;
            margin-top: 20px;
        }
        h4 {
            font-size: 17px;
            margin-bottom: 20px;
            color: green;
        }

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        .transaction-table th, .transaction-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .transaction-table th {
            background-color: #f2f2f2;
        }

        .transaction-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .transaction-table tbody tr:hover {
            background-color: #ddd;
        }
    </style>
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
                        echo "<h4>"."Current balance:" .$row['balance'] ."</h4>";
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
                        echo "<h4>"."Current balance:" .$row['balance'] ."</h4>";
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
