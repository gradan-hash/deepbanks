<?php
session_start();
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = stripcslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    $password = stripcslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to retrieve user ID and hashed password based on email
    $query = "SELECT `user_id`, `password` FROM `customers` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['user_id'];
            $hashed_password = $row['password'];

            // Verify the provided password against the hashed password
            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION['user_id'] = $user_id; // Store user_id in the session
                echo "<script>
                    alert('Login Successful');
                    window.location.href = '../deposit/deposit.html';
                    </script>";
            } else {
                // Password is incorrect
                echo "<div class='insertt' style='background-color: #ffcccc; padding: 20px; border: 1px solid #ff0000; margin-top: 20px; text-align: center;'>
                      <h3>Incorrect Email or Password</h3><br/>
                      <h4 class='link'>Click here to <a href='../login/login.html' style='text-decoration: none; color: #ff0000; font-weight: bold;'>login</a> again.</h4>
                      </div>";
            }
        } else {
            // Email does not exist
            echo "<div class='insertt' style='background-color: #ffcccc; padding: 20px; border: 1px solid #ff0000; margin-top: 20px; text-align: center;'>
                  <h3>Incorrect Email or Password</h3><br/>
                  <h4 class='link'>Click here to <a href='../login/login.html' style='text-decoration: none; color: #ff0000; font-weight: bold;'>login</a> again.</h4>
                  </div>";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
