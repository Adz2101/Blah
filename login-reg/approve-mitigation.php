<?php
session_start();
require_once "database.php"; // Include your database connection file

if (isset($_POST['approve_mitigation']) && isset($_POST['risk_id']) && isset($_POST['user_id'])) {
    $risk_id = $_POST['risk_id'];
    $user_id = $_POST['user_id'];
    
    // Update the mitigation status to approved and associate it with the user
    $sql = "UPDATE mitigation SET approved = 1, user_id = ? WHERE risk_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $risk_id);
        
        // Execute SQL statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the user's dashboard or any other appropriate page
            header("Location: user_dashboard.php");
            exit();
        } else {
            echo "Error executing SQL statement: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing SQL statement: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
