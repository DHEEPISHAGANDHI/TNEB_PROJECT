<?php
session_start();
include("server.php");

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: adminlogin.html");
    exit();
}

// Get the complaint ID
if (isset($_GET['cmpid'])) {
    $cmpid = intval($_GET['cmpid']);
} else {
    echo "Complaint ID not provided!";
    exit();
}

// Fetch complaint details
$complaintQuery = "SELECT * FROM user_complaint WHERE cmpid = ?";
$stmt = $conn->prepare($complaintQuery);
$stmt->bind_param("i", $cmpid);
$stmt->execute();
$complaintResult = $stmt->get_result();

if ($complaintResult->num_rows > 0) {
    $complaintDetails = $complaintResult->fetch_assoc();
} else {
    echo "Complaint not found!";
    exit();
}

// Fetch all employees for assignment
$employees = mysqli_query($conn, "SELECT username FROM employee_registration");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_username = $_POST['employee_username'];

    // Insert fetched complaint details into assigned_complaints table
    $assignQuery = "INSERT INTO assigned_complaints (cmpid, employee_username, name, email, phone, address, complainttype, complaintdetails) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($assignQuery);
    $stmt->bind_param(
        "isssssss", 
        $cmpid, 
        $employee_username, 
        $complaintDetails['name'], 
        $complaintDetails['email'], 
        $complaintDetails['phone'], 
        $complaintDetails['address'], 
        $complaintDetails['complainttype'], 
        $complaintDetails['complaintdetails']
    );

    if ($stmt->execute()) {
        // Update the status in user_complaint table
        $updateStatusQuery = "UPDATE user_complaint SET status = 1 WHERE cmpid = ?";
        $statusStmt = $conn->prepare($updateStatusQuery);
        $statusStmt->bind_param("i", $cmpid);

        if ($statusStmt->execute()) {
            echo "Complaint assigned and status updated successfully!";
            header("Location: admincomplaint.php");
            exit();
        } else {
            echo "Failed to update complaint status!";
        }
    } else {
        echo "Failed to assign complaint!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminassigncomplaint.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Assign Complaint</title>
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
    <div class="button" onclick="location.href='admincomplaint.php'">
        <button type="submit" class="back-button">← Back</button>
    </div>
    <h2>Assign Complaint</h2>
   <div class ="container">

    <form method="POST" action="">
        <label for="employee_username">Select Employee:</label>
        <select name="employee_username" required>
            <option value="">-- Select Employee --</option>
            <?php while ($row = mysqli_fetch_assoc($employees)) { ?>
                <option value="<?php echo htmlspecialchars($row['username']); ?>">
                    <?php echo htmlspecialchars($row['username']); ?>
                </option>
            <?php } ?>
        </select>
        <br><br>
        <div class ="but">
        <button type="submit" class="button-assign">Assign Complaint</button>
            </div>
    </form>
    </div>
</body>
</html>
