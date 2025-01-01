<?php
session_start();
include("server.php");

// Check if employee is logged in
if (!isset($_SESSION['username'])) {
    header("Location: employeelogin.html");
    exit();
}

// Check if complaint ID is provided via POST
if (isset($_POST['cmpid'])) {
    $cmpid = intval($_POST['cmpid']);
    
    // Update the status to 2 in user_complaint table
    $updateQuery = "UPDATE user_complaint SET status = 2 WHERE cmpid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $cmpid);

    if ($stmt->execute()) {
        // Redirect back to the employee's complaint management page
        // $_SESSION['message'] = "Complaint marked as finished successfully!";
        echo "<script>alert('Update the progress'); window.location.href='employeedashboard.php';</script>";
        exit();
    } else {
        echo "Failed to update complaint status!";
    }
} else {
    echo "Complaint ID not provided!";
    exit();
}
?>

