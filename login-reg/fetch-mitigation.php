<?php
session_start();
require_once "database.php";

if(isset($_GET['risk_id'])) {
    $risk_id = $_GET['risk_id'];
    
    // Query the database to retrieve mitigation plan for the specified risk ID
    // Your SQL query goes here
    // Make sure to sanitize $risk_id before using it in the query to prevent SQL injection
    
    // Example query
    $sql = "SELECT * FROM mitigation WHERE risk_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $risk_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Fetch mitigation data
    $mitigation = mysqli_fetch_assoc($result);
    
    // Return mitigation data as JSON
    echo json_encode($mitigation);
} else {
    // Risk ID not provided
    echo json_encode(array('error' => 'Risk ID not provided'));
}
?>
