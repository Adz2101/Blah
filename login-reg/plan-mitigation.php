<?php
session_start();
require_once "database.php"; // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

// Check if risk_id is provided in the URL parameters
if (!isset($_GET['risk_id'])) {
    // Redirect if risk_id is not provided
    header("Location: view-submitted-risks.php");
    exit();
}

$risk_id = $_GET['risk_id'];

// Fetch mitigations for the specified risk_id from the mitigation table
$sql = "SELECT * FROM mitigation WHERE risk_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Error: Unable to prepare SQL statement. " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $risk_id);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);

    // Check if any mitigations are found
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Mitigations for Risk ID: $risk_id</h2>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>{$row['mitigation']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No mitigations found for Risk ID: $risk_id</p>";
    }
} else {
    // Error executing the SQL statement
    echo "Error: " . mysqli_error($conn);
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
