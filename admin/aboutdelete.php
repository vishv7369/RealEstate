<?php
include("config.php");

// Ensure $aid is set and is a valid number
$aid = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Ensure it’s an integer and safe to use in queries

// Check if a valid ID is provided
if ($aid > 0) {

    // View code: Get the image name for deletion
    $sql = "SELECT image FROM about WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $aid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $img);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the image from the server if it exists
    if (!empty($img)) {
        @unlink('upload/' . $img); // Attempt to delete the image file
    }

    // Delete the record from the database
    $sql = "DELETE FROM about WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $aid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $msg = "<p class='alert alert-success'>About Deleted</p>";
    } else {
        $msg = "<p class='alert alert-warning'>About not Deleted</p>";
    }

    mysqli_stmt_close($stmt);

} else {
    // If ID is not valid or missing, show an error message
    $msg = "<p class='alert alert-danger'>Invalid or Missing ID</p>";
}

header("Location: aboutview.php?msg=" . urlencode($msg));
mysqli_close($con);
?>
