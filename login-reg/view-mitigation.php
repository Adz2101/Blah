<?php
require_once "database.php"; // Include your database connection file

// Query the database to retrieve approved mitigation plans
$sql = "SELECT * FROM mitigation WHERE risk_id IN (SELECT id FROM message WHERE mitigation_approved = 1)";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Display the approved mitigation plans
    while ($row = mysqli_fetch_assoc($result)) {
        // Echo HTML code to display the mitigation plan details
        echo "<p>Mitigation: {$row['mitigation']}</p>";
        // Add more HTML formatting for other mitigation plan details as needed
    }
} else {
    // No approved mitigation plans found
    echo "<p>No approved mitigation plans found</p>";
}

mysqli_close($conn);
?>
