<?php 

include 'server.php';

if(isset($_POST['signup'])){
    $username = $_POST['username'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'] ;
    $state  =  $_POST['state'] ;
    $district  =  $_POST['district'] ;
    $area  = $_POST['area'] ;
    $password = $_POST['password'] ;
    $confirm = $_POST['confirm-password'] ;
   //  $password=md5($password);

    //  $checkEmail="SELECT * From users where email='$email'";
     $SELECT = "SELECT * From admin_registration Where email = '$email'";
     $SELECT1 = "SELECT * From admin_registration Where phone = '$phone'";
     $result=$conn->query($SELECT);
     $result1=$conn->query($SELECT1);
     if($result->num_rows>0 || $result1->num_rows>0) {
     if($result->num_rows>0){
        echo "Email Address Already Exists !";
     }
     if($result1->num_rows>0){
        echo "Phone number Already Exists !";
     }
    }
     else{
        $insertQuery="INSERT INTO  admin_registration (username ,email ,phone ,state ,district ,area ,password ,confirmpassword)
                       VALUES ('$username','$email','$phone','$state','$district','$area','$password','$confirm')";
            if($conn->query($insertQuery)==TRUE){
                header("location: adminlogin.html");
            }
            else{
                echo "Error:".$conn->error;
            }
     }
   

}

if(isset($_POST['login'])){
   $username = $_POST['username'];
   $password=$_POST['password'];
   // $password=md5($password) ;
   
   $sql="SELECT * FROM admin_registration WHERE username='$username' and password='$password'";
   $result=$conn->query($sql);
   if($result->num_rows>0){
    session_start();
    $row=$result->fetch_assoc();
    $_SESSION['username']=$row['username'];
    header("Location: admindashboard.php");
    exit();
   }
   else{
    echo "Not Found, Incorrect Email or Password";
   }

}
?>