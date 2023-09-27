<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($conn, $name);

    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    $contact = stripslashes($_POST['contact']);
    $contact = mysqli_real_escape_string($conn, $contact);

    $query = "INSERT INTO `customers`(`name`, `password`, `email`, `contact`) VALUES ('$name','$hashed_password','$email','$contact')";

    $cr = mysqli_query($conn, $query);

    if ($cr) {
        $user_id = mysqli_insert_id($conn); // Get the auto-generated user_id
        $_SESSION['user_id'] = $user_id; // Store user_id in the session

        echo "<script>
            alert('Registration Successful');
            window.location.href = '../login/login.html';
          </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
