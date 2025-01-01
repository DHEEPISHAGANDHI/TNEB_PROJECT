<?php
session_start();
include("server.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File a Complaint</title>
  <link rel="stylesheet" href="usercomplaintstyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="header-left">
            <h1>TNEB Portal</h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>
            <i class="fa-solid fa-bell" onclick="location.href='usernotification.php'"></i>
            <i class="fa-solid fa-user" onclick="location.href='userdetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='userlogin.html'"></i>
        </div>
    </header>
    <div class="button" onclick="location.href='userdashboard.php'">
        <button type="submit" class="back-button">← Back</button>
    </div>
  <div class="container">
    <h1>Your Details</h1>
    <?php 
       if(isset($_SESSION['username'])){
        $username=$_SESSION['username'];
        $query=mysqli_query($conn, "SELECT user_registration.* FROM `user_registration` WHERE user_registration.username='$username'");
        while($row=mysqli_fetch_array($query)){
            echo "
                    <h1>Name : " . htmlspecialchars($row['username']) . "</h1>
                    <h1>Email : " . htmlspecialchars($row['email']) . "</h1>
                    <h1>Phone No :" . htmlspecialchars($row['phone']) . "</h1>
               ";
        }
       }
     ?>
      </div>
    </form>
  </div>
</body>
</html>
