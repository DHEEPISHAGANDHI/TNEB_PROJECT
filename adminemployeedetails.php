<?php
include("server.php");
session_start(); // Start the session to access admin's details

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: adminlogin.html"); // Redirect to login if not logged in
    exit();
}

// Fetch admin's state, district, and area from the database
$adminUsername = $_SESSION['username'];
$adminQuery = "SELECT state, district, area FROM admin_registration WHERE username = '$adminUsername'";
$adminResult = $conn->query($adminQuery);

// Verify that admin details are available
if ($adminResult->num_rows > 0) {
    $adminRow = $adminResult->fetch_assoc();
    $adminState = $adminRow['state'];
    $adminDistrict = $adminRow['district'];
    $adminArea = $adminRow['area'];

    // Fetch employees with matching state, district, and area
    $query = "SELECT * FROM employee_registration WHERE state = '$adminState' AND district = '$adminDistrict' AND area = '$adminArea'";
    $result = $conn->query($query);
} else {
    echo "Error: Admin details not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="admincomplaintstyle.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Manage Employees</title>
  <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    line-height: 1.6;
    padding: 0;
    margin: 0;
}

header {
    background-color: #007bff; /* Blue navbar */
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left h1 {
    font-size: 24px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 18px;
}

.header-right i {
    cursor: pointer;
    transition: color 0.3s ease;
}

.header-right i:hover {
    color: #ffd700; /* Highlight icons on hover */
}

.button {
    margin: 20px;
}

.back-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #0056b3;
}

.container {
    max-width: 800px;
    margin: 30px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

.container h1 {
    margin-bottom: 20px;
    color: #007bff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
}

tbody tr:nth-child(even) {
    background-color: #f4f4f9;
}

tbody tr:hover {
    background-color: #eef;
}

p {
    margin-top: 20px;
    color: #555;
}

    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>TNEB Portal</h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>
            <i class="fa-solid fa-bell" onclick="location.href='#'"></i>
            <i class="fa-solid fa-user" onclick="location.href='admindetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='adminlogin.html'"></i>
        </div>
    </header>
    <div class="button" onclick="location.href='admindashboard.php'">
        <button type="submit" class="back-button">← Back</button>
    </div>
    
    <div class="container">
        <h1>Employee Details</h1>
        <?php
        if (isset($result) && $result->num_rows > 0) {
            echo "<table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>";
            $counter = 1; // Counter for S.No
            while ($row = $result->fetch_assoc()) {
                echo "
                <tbody>
                    <tr>
                        <td>" . $counter++ . "</td>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['phone']) . "</td>
                    </tr>
                </tbody>";
            }
            echo "</table>";
        } else {
            echo "<p>No employees found matching your state, district, and area.</p>";
        }
        ?>
    </div>
</body>
</html>
