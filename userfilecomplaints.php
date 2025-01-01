

<!--       -->
<!--       -->

<?php
include("server.php");
session_start(); // Start the session to access admin's information

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: userlogin.html"); // Redirect to login if not logged in
    exit();
}

if (isset($_POST['submitcomplaint'])) {
    // $loggedInEmail = $_SESSION['email']; // Get logged-in user's email from the session
    echo "<script>alert('Complaint submited successfully!');</script>";
    // Retrieve complaint details from the form
    $name = $_POST['name'];
    $email = $_POST['email']; // Email entered in the form
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $complaintType = $_POST['complainttype'];
    $complaintDetails = $_POST['complaintdetails'];
// Fetch admin's state, district, and area from the database
$Username = $_SESSION['username'];
$adminQuery = "SELECT email FROM user_registration WHERE username = '$Username'";
$adminResult = $conn->query($adminQuery);

// Verify that admin details are available
if ($adminResult->num_rows > 0) {
    $adminRow = $adminResult->fetch_assoc();
    $useremail = $adminRow['email'];
    
        // Step 2: Count the total complaints in the database
        $countQuery = "SELECT COUNT(*) AS totalComplaints FROM user_complaint";
        $countResult = $conn->query($countQuery);

        if ($countResult->num_rows > 0) {
            $countRow = $countResult->fetch_assoc();
            $totalComplaints = $countRow['totalComplaints'];
    if($useremail == $email){
        $insertQuery = "INSERT INTO user_complaint (name, email, phone, state, district, area, address,cmpid, complainttype, complaintdetails)
                        VALUES ('$name', '$email', '$phone','$state','$district','$area', '$address','$totalComplaints', '$complaintType', '$complaintDetails')";
       
        if ($conn->query($insertQuery) === TRUE) {
            // echo "<script>alert('Complaint submit successfully!');</script>";
            //  header("Location: userprogress.php"); // Redirect to another page
            // echo "<script>alert('Complaint submited successfully!');</script>";
            header("Location: usercomplaints.php");
        } else {
            echo "Error: " . $conn->error; // Handle database insertion error
        }
    }
      else{
        echo "please enter your registered email";
      }                           // Fetch users with matching state, district, and area
                                 // $query = "SELECT * FROM user_complaint WHERE email = '$useremail'";
                                 // $result = $conn->query($query);
                                } else {
                                    echo "Error fetching total complaint count.";
                                }
                            


} else {
    echo "Error: User email not found.";
    exit();
}
}
else{
    echo" please fill this form";
}
?>