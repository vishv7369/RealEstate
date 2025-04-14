<?php
include("config.php");

// Check if the 'id' parameter is set and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Sanitize the 'id' to prevent SQL injection
    $sid = mysqli_real_escape_string($con, $_GET['id']);
    
    // Prepare the DELETE query
    $sql = "DELETE FROM state WHERE sid = {$sid}";

    // Execute the query
    $result = mysqli_query($con, $sql);

    // Check if the query was successful
    if ($result) {
        $msg = "<p class='alert alert-success'>State Deleted</p>";
    } else {
        $msg = "<p class='alert alert-warning'>State Not Deleted. Error: " . mysqli_error($con) . "</p>";
    }
} else {
    // If 'id' is not provided or is invalid
    $msg = "<p class='alert alert-danger'>Invalid or missing State ID.</p>";
}

// Redirect to stateadd.php with the message
header("Location: stateadd.php?msg=" . urlencode($msg));
exit();

// Close the database connection
mysqli_close($con);
?>
