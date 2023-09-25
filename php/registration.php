<?php


session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

  $name = stripslashes ($_POST['name']);
  $name=mysqli_real_escape_string($conn,$name);


  
  $password = stripslashes($_POST['password']);
  $password=mysqli_real_escape_string($conn,$password);
  
   $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  
  $email = stripslashes($_POST['email']);
  $email=mysqli_real_escape_string($conn,$email);


  $phone = stripslashes($_POST['phone']);
  $phone=mysqli_real_escape_string($conn,$phone);


  $address = stripslashes($_POST['address']);
  $address=mysqli_real_escape_string($conn,$address);

  

  $query = "INSERT INTO `customers`( `name`, `password`, `email`,`phone`,`address`) VALUES ('$name','$password','$email','$phone', '$address')";

  
  
  $cr=mysqli_query($conn ,$query);

  $_SESSION['name'] =$name;


  if($cr){
    
    header('location: ../login.html');
    
  }else {
    echo "error : " .$sql . mysqli_error($conn); 
  }

}



?>