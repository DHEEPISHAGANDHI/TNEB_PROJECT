<?php
// Include database connection
include("server.php");

// Start the session to check if admin is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: adminlogin.html"); // Redirect to login if not logged in
    exit();
}



// Check if the form was submitted
if (isset($_POST['submitmessage'])) {
    // Sanitize and retrieve form inputs
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Get the admin username from session
    $adminUsername = $_SESSION['username'];

    // Insert the data into the messages table
    $sql = "INSERT INTO admin_message ( state, district, area, message) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss",  $state, $district, $area, $message);

        if ($stmt->execute()) {
            // Redirect to a success page or show a success message
            echo "<script>alert('Message sent successfully!'); window.location.href='admindashboard.php';</script>";
        } else {
            // Handle error during execution
            echo "<script>alert('Error sending message: " . $stmt->error . "'); window.location.href='adminmessage.php';</script>";
        }

        $stmt->close();
    } else {
        // Handle error if the statement couldn't be prepared
        echo "<script>alert('Error preparing the query: " . $conn->error . "'); window.location.href='adminmessage.php';</script>";
    }
} else {
    // Redirect back if the form is not submitted
    header("Location: adminmessage.php");
    exit();
}
?>
