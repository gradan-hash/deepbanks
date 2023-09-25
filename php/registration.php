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


  $contact = stripslashes($_POST['contact']);
  $contact=mysqli_real_escape_string($conn,$contact);


 

  

  $query = "INSERT INTO `customers`( `name`, `password`, `email`,`contact`) VALUES ('$name','$password','$email','$contact')";

  
  
  $cr=mysqli_query($conn ,$query);

  $_SESSION['name'] =$name;


  if($cr){
    echo "sucess";
    
    header('location: ../login.html');
    
  }else {
    echo "error : " .$sql . mysqli_error($conn); 
  }

}



?>