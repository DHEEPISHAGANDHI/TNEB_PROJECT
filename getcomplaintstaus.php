<?php
// Start session or include database connection
include("server.php");

// Check if 'cmpid' is provided
if (isset($_GET['cmpid'])) {
    $cmpid = $_GET['cmpid'];

    // Prepare the SQL query to fetch the status of the complaint
    $query = "SELECT status FROM user_complaint WHERE cmpid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cmpid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the status and return it as JSON
        $row = $result->fetch_assoc();
        echo json_encode(["status" => $row['status']]);
    } else {
        // If the complaint ID doesn't exist, return an error
        echo json_encode(["error" => "Complaint not found"]);
    }
} else {
    // If 'cmpid' is not provided, return an error
    echo json_encode(["error" => "Complaint ID not provided"]);
}
?>
