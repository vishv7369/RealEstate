<?php
include("config.php");

// Prepare the query to delete the property
$pid = $_GET['id'];
$sql = "DELETE FROM property WHERE pid = ?";

// Prepare the statement
$stmt = mysqli_prepare($con, $sql);

// Bind the $pid parameter to the query
mysqli_stmt_bind_param($stmt, "i", $pid); // "i" stands for integer

// Execute the query
$result = mysqli_stmt_execute($stmt);

if ($result) {
    $msg = "<p class='alert alert-success'>Property Deleted</p>";
    header("Location: feature.php?msg=$msg");
} else {
    $msg = "<p class='alert alert-warning'>Property Not Deleted</p>";
    header("Location: feature.php?msg=$msg");
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
