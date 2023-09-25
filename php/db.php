
<?php
    $servername='localhost';
    $username='root';
    $password='';
    $dbname = "banking";
    
    $conn=mysqli_connect($servername,$username,$password,"$dbname");


      if(!$conn){
         echo "connection unsuccesfull";
        }
?>