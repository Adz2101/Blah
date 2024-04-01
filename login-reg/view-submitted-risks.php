<?php
session_start();
require_once "database.php"; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION["user"]) && !isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

// Check if admin is logged in
if (isset($_SESSION["admin"])) {
    // Prepare the SQL statement to retrieve risks submitted by all users
    $sql = "SELECT m.*, u.full_name, m.id FROM message m JOIN users u ON m.user_id = u.id";
} else {
    // Get the user ID from the session
    $user_id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
    
    // Prepare the SQL statement to retrieve risks submitted by the logged-in user
    $sql = "SELECT m.*, u.full_name, m.id FROM message m JOIN users u ON m.user_id = u.id WHERE m.user_id = ?";
}

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Error: Unable to prepare SQL statement. " . mysqli_error($conn));
}

// Bind the user ID parameter if applicable
if (isset($user_id)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
}

// Execute the SQL statement
if (mysqli_stmt_execute($stmt)) {
    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Check if any risks are found
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Submitted Risks</h2>";
        echo "<table border='1'>";
        echo "<tr><th>User</th><th>Subject</th><th>Category</th><th>Risk Mapping</th><th>Current Impact</th><th>Current Likelihood</th><th>Risk Source</th><th>Control Regulation</th><th>Control Number</th><th>Risk Scoring Method</th><th>Owner</th><th>Owner's Manager</th><th>risk_id</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['full_name']}</td>";
            echo "<td>{$row['subject']}</td>";
            echo "<td>{$row['category']}</td>";
            echo "<td>{$row['riskMapping']}</td>";
            echo "<td>{$row['currentImpact']}</td>";
            echo "<td>{$row['currentlikelihood']}</td>";
            echo "<td>{$row['risksource']}</td>";
            echo "<td>{$row['controlregulation']}</td>";
            echo "<td>{$row['controlno']}</td>";
            echo "<td>{$row['scoringmethod']}</td>";
            echo "<td>{$row['owner']}</td>";
            echo "<td>{$row['ownermanager']}</td>";
            echo "<td><a href='fetch-mitigation.php?risk_id={$row['risk_id']}' class='btn-plan-mitigation' data-risk-id='{$row['risk_id']}'>Plan Mitigation</a></td>";

            // Fetch risk ID from the risk table
            $riskCategory = $row['category'];
            $riskMapping = $row['riskMapping'];
            $riskIdQuery = "SELECT risk_id FROM risk WHERE category = ? AND riskMapping = ?";
            $riskIdStmt = mysqli_prepare($conn, $riskIdQuery);
            mysqli_stmt_bind_param($riskIdStmt, "ss", $riskCategory, $riskMapping);
            mysqli_stmt_execute($riskIdStmt);
            mysqli_stmt_bind_result($riskIdStmt, $riskIdResult); // Corrected binding
            mysqli_stmt_fetch($riskIdStmt);
            mysqli_stmt_close($riskIdStmt);

             // Display the risk ID
           echo "<td> {$riskIdResult}</td>";
           
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No submitted risks found</p>";
    }
} else {
    // Error executing the SQL statement
    echo "Error: " . mysqli_error($conn);
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
