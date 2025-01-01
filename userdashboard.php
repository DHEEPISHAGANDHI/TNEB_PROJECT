<?php
session_start();
include("server.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNEB Portal Dashboard</title>
    <link rel="stylesheet" href="userdashboardstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <header>
        <div class="header-left">
            <h1>TNEB Portal </h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>
            <div class="icons">
                <i class="fa-solid fa-bell" onclick="location.href='usernotification.php'"></i>
                <i class="fa-solid fa-user" onclick="location.href='userdetails.php'"></i>
                <i class="fa-solid fa-right-from-bracket" onclick="location.href='userlogin.html'"></i>
            </div>
        </div>
    </header>
    <main>
        <h2>Welcome <?php 
       if(isset($_SESSION['username'])){
        $username=$_SESSION['username'];
        $query=mysqli_query($conn, "SELECT user_registration.* FROM `user_registration` WHERE user_registration.username='$username'");
        while($row=mysqli_fetch_array($query)){
            echo $row['username'];
        }
       }
       ?> this is Your Dashboard</h2>
        <div class="card-container">
            <div class="card"  onclick="location.href='usercomplaint.html'">
                <i class="fa-regular fa-file-lines"></i>
                <h3>File Complaint</h3>
                <p>Submit a new complaint or service request</p>
            </div>
            <div class="card"  onclick="location.href='usercomplaints.php'">
                <i class="fa-solid fa-square-poll-vertical"></i>
                <h3>Track Progress</h3>
                <p>Monitor the status of your complaints</p>
            </div>
        </div>
    </main>
</body>
</html>
