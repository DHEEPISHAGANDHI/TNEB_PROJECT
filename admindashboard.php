<?php
session_start();
include("server.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admindashboardstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="header-left">
            <h1>TNEB Portal</h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>

            <i class="fa-solid fa-bell" onclick="location.href='#'"></i>
            <i class="fa-solid fa-user" id="user" onclick="location.href='admindetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='adminlogin.html'"></i>
        </div>
    </header>
    <div class="popup">
            <div class="popup-content">
            <i class="fa-solid fa-circle-xmark"></i>
            <h1>Your Details</h1>
    <?php 
       if(isset($_SESSION['username'])){
        $username=$_SESSION['username'];
        $query=mysqli_query($conn, "SELECT admin_registration.* FROM `admin_registration` WHERE admin_registration.username='$username'");
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
        </div>
        <script>
          document.getElementById("user").addEventListener("click",function(){
            document.querySelector(".popup").style.display = "flex";
          })
            document.querySelector(".fa-circle-xmark").addEventListener("click",function(){
                document.querySelector(".popup").style.display = "none";
            })
        
        </script>
    <main>
    <h2>Welcome <?php 
       if(isset($_SESSION['username'])){
        $username=$_SESSION['username'];
        $query=mysqli_query($conn, "SELECT admin_registration.* FROM `admin_registration` WHERE admin_registration.username='$username'");
        while($row=mysqli_fetch_array($query)){
            echo $row['username'];
        }
       }
       ?> this is Your Dashboard</h2>
        <h2>Admin Dashboard</h2>
        <div class="card-container">
            <div class="card" onclick="location.href='adminuserdetails.php'">
                <i class="fa-solid fa-user"></i>
                <h3>User Details</h3>
                <p>Manage user information</p>
            </div>
            <div class="card" onclick="location.href='adminemployeedetails.php'">
                <i class="fa-solid fa-users"></i>
                <h3>Employee Details</h3>
                <p>Manage employee information</p>
            </div>
            <div class="card" onclick="location.href='admincomplaint.php'">
                <i class="fa-regular fa-file-lines"></i>
                <h3>Complaints</h3>
                <p>View and manage complaints</p>
            </div>
            <div class="card" onclick="location.href='adminmessage.html'">
                <i class="fa-solid fa-comments"></i>
                <h3>Messages</h3>
                <p>Send notifications to users</p>
            </div>
        </div>
    </main>
</body>
</html>
