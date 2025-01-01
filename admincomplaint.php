<?php
include("server.php");
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: adminlogin.html"); 
    exit();
}

$adminUsername = $_SESSION['username'];
$adminQuery = "SELECT state, district, area FROM admin_registration WHERE username = ?";
$stmt = $conn->prepare($adminQuery);
$stmt->bind_param("s", $adminUsername);
$stmt->execute();
$adminResult = $stmt->get_result();

if ($adminResult->num_rows > 0) {
    $adminRow = $adminResult->fetch_assoc();
    $adminState = $adminRow['state'];
    $adminDistrict = $adminRow['district'];
    $adminArea = $adminRow['area'];

    $query = "SELECT * FROM user_complaint WHERE state = ? AND district = ? AND area = ? ORDER BY complainttype";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $adminState, $adminDistrict, $adminArea);
    $stmt->execute();
    $result = $stmt->get_result();
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
  <title>Manage Complaints</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    width: 1060px;
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
  <script>
    function showForm(cmpid) {
      document.getElementById('complaintId').value = cmpid;
      document.getElementById('employeeForm').style.display = 'block';
    }

    function assignToEmployee() {
      var cmpid = document.getElementById('complaintId').value;
      var employeeName = document.getElementById('employeeName').value;
      var employeeEmail = document.getElementById('employeeEmail').value;

      $.ajax({
        url: 'assign_complaint.php',
        type: 'POST',
        data: {
          cmpid: cmpid,
          employeeName: employeeName,
          employeeEmail: employeeEmail
        },
        success: function(response) {
          alert(response);
          document.getElementById('employeeForm').style.display = 'none';
        }
      });
    }
  </script>
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
        <button type="submit" class="back-button">&larr; Back</button>
    </div>

    <div class="container">
        <h1>Manage Complaints</h1>
        <?php
        if (isset($result) && $result->num_rows > 0) {
            $complaintsByType = [];

            while ($row = $result->fetch_assoc()) {
                $complaintsByType[$row['complainttype']][] = $row;
            }

            foreach ($complaintsByType as $type => $complaints) {
                echo "<h2>" . htmlspecialchars($type) . "</h2>";
                echo "<table>
                <thead>
                    <tr>
                       <th>S.No</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Complaint Details</th>
                        
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>";

                $counter = 1;
                foreach ($complaints as $complaint) {
                    echo "<tbody>
                        <tr>
                           <td>" . $counter++ . "</td>
                            <td>COMP" . str_pad($complaint['cmpid'], 3, "0", STR_PAD_LEFT) . "</td>
                            <td>" . htmlspecialchars($complaint['name']) . "</td>
                            <td>" . htmlspecialchars($complaint['email']) . "</td>
                            <td>" . htmlspecialchars($complaint['phone']) . "</td>
                            <td>" . htmlspecialchars($complaint['address']) . "</td>
                            <td>" . htmlspecialchars($complaint['complaintdetails']) . "</td>
                            
                            <td>Pending</td>
                            <td><a href='adminassigncomplaint.php?cmpid=" . $complaint['cmpid'] . "'>Send to Employee</a></td>

                         
                            
                        </tr>
                    </tbody>";
                }
                echo "</table>";
            }
        } else {
            echo "<p>No complaints found for your region.</p>";
        }
        ?>
    </div>

    <!-- Small form to assign complaint to employee -->
    <div id="employeeForm" style="display: none;">
        <h3>Assign Complaint</h3>
        <form>
            <input type="hidden" id="complaintId">
            <label for="employeeName">Employee Name:</label><br>
            <input type="text" id="employeeName" required><br>
            <label for="employeeEmail">Employee Email:</label><br>
            <input type="email" id="employeeEmail" required><br><br>
            <button type="button" onclick="assignToEmployee()">Assign</button>
            <td><button onclick='showForm(" . $complaint['cmpid'] . ")'>Send to Employee</button></td>
        </form>
    </div>

</body>
</html>
