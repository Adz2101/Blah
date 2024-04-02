<?php
session_start();
require_once "database.php"; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION["user"]["id"];

// Prepare SQL statement to retrieve approved mitigation for the user
$sql = "SELECT * FROM mitigation WHERE user_id = ? AND approved = 1"; // Assuming there's a column named 'approved' to mark approved mitigation

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameter
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    // Execute SQL statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the result set
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if any approved mitigation found
        if (mysqli_num_rows($result) > 0) {
            // Display approved mitigation
            echo "<h2>Approved Mitigation</h2>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>{$row['mitigation']}</p>"; // Assuming 'mitigation' is the column name for mitigation text
            }
        } else {
            echo "<p>No approved mitigation found for the user.</p>";
        }
    } else {
        echo "Error executing SQL statement: " . mysqli_error($conn);
    }
} else {
    echo "Error preparing SQL statement: " . mysqli_error($conn);
}

// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
