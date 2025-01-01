<?php
session_start();
include("server.php");

// Check if employee is logged in
if (!isset($_SESSION['username'])) {
    header("Location: employeelogin.html");
    exit();
}

$employeeUsername = $_SESSION['username'];

// Fetch complaints assigned to the logged-in employee
 $query = "
     SELECT uc.cmpid, uc.name ,uc.address,uc.complainttype,uc.complaintdetails,uc.email,uc.phone
     FROM assigned_complaints ac
     INNER JOIN user_complaint uc ON ac.cmpid = uc.cmpid
     WHERE ac.employee_username = ?
";
 $stmt = $conn->prepare($query);
 $stmt->bind_param("s", $employeeUsername);
$stmt->execute();
 $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="usercomplaintstyle.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Manage Complaints</title>
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
   width: 1170px;
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
            <span><i class="fa-solid fa-phone-volume"></i> 94987 94987</span>
            <i class="fa-solid fa-bell" onclick="location.href='employeenotification.php'"></i>
            <i class="fa-solid fa-user" onclick="location.href='employeedetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='index.html'"></i>
        </div>
    </header>
   
    <center><h1>Welcome <?php echo htmlspecialchars($employeeUsername); ?></h1></center>
    <div class="container">

    <center><h2>Your Assigned Complaints</h2></center>
    <?php if ($result->num_rows > 0): ?>
        <table >
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>ComplaintID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Complainttype</th>
                    <th>complaintdetails</th>
                    <th>Action</th>
                </tr>
            </thead>
           
            <tbody>
                
                <?php 
                $counter =1;
                while ($row = $result->fetch_assoc()): ?>
                    
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo "COMP" . str_pad($row['cmpid'], 3, "0", STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['complainttype']); ?></td>
                        <td><?php echo htmlspecialchars($row['complaintdetails']); ?></td>
                        <td>
                            <form method="POST" action="employeemarkcomplete.php">
                                <input type="hidden" name="cmpid" value="<?php echo $row['cmpid']; ?>">
                                <button type="submit">Mark as Finished</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <center><p>No complaints assigned to you.</p></center>
    <?php endif; ?>
    </div>
</body>
</html>
