<?php
include("server.php");
session_start(); // Start the session to access user details

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: employeelogin.html"); // Redirect to login if not logged in
    exit();
}

// Fetch the logged-in user's state, district, and area
$userId = $_SESSION['username'];
$userQuery = "SELECT state, district, area FROM employee_registration WHERE username = '$userId'";
$userResult = $conn->query($userQuery);

// Verify that user details are available
if ($userResult->num_rows > 0) {
    $userRow = $userResult->fetch_assoc();
    $userState = $userRow['state'];
    $userDistrict = $userRow['district'];
    $userArea = $userRow['area'];

    // Fetch admin messages that match the user's state, district, and area
    $messageQuery = "SELECT * FROM admin_message WHERE state = '$userState' AND district = '$userDistrict' AND area = '$userArea'";
    $messageResult = $conn->query($messageQuery);
} else {
    echo "Error: User details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications</title>
  <link rel="stylesheet" href="adminmessagestyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="header-left">
            <h1>TNEB Portal</h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>
            <i class="fa-solid fa-bell" onclick="location.href='employeenotification.php'"></i>
            <i class="fa-solid fa-user" onclick="location.href='employeedetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='employeelogin.html'"></i>
        </div>
    </header>
    <div class="button" onclick="location.href='employeedashboard.php'">
        <button type="submit" class="back-button">&larr; Back</button>
    </div>
    <div class="container">
        <h1>Your Notifications</h1>
        <?php
        if (isset($messageResult) && $messageResult->num_rows > 0) {
            while ($messageRow = $messageResult->fetch_assoc()) {
                // Current date and tomorrow's date
                $currentDate = date("Y-m-d");
                $tomorrowDate = date("Y-m-d", strtotime("-1 day"));

                // Format message date
                $messageDate = $currentDate; // Assume message date as today for simplicity

                // Determine if the message is for today or tomorrow
                if ($messageDate == $currentDate) {
                    $dateLabel = "Today";
                } elseif ($messageDate == $tomorrowDate) {
                    $dateLabel = "Yesterday";
                } else {
                    $dateLabel = date("d-m-Y", strtotime($messageDate));
                }

                echo "<div class='notification'>
                        <h5> <strong>$dateLabel </strong>  ". date("d-m-Y H:i:s") . " </h5>
                        <p><strong>Message:</strong> " . htmlspecialchars($messageRow['message']) . "</p>
                      </div>";
            }
        } else {
            echo "<p>No notifications available for your region.</p>";
        }
        ?>
    </div>
</body>
</html>
