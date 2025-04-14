<?php
session_start();
include("config.php");

// Check if the user is logged in
if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit;
}

// Get the ID from the URL parameter and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $aid = $_GET['id'];
} else {
    // Redirect if ID is not present or invalid
    $msg = "<p class='alert alert-danger'>Invalid ID.</p>";
    header("Location: aboutview.php?msg=$msg");
    exit;
}

// Check if the form has been submitted
if (isset($_POST['update'])) {
    // Sanitize and validate form inputs
    $title = mysqli_real_escape_string($con, $_POST['utitle']);
    $content = mysqli_real_escape_string($con, $_POST['ucontent']);

    // Check if a new image is uploaded
    if (isset($_FILES['aimage']) && $_FILES['aimage']['error'] === 0) {
        $aimage = $_FILES['aimage']['name'];
        $temp_name1 = $_FILES['aimage']['tmp_name'];

        // Set the target directory for uploads
        $upload_dir = "upload/";
        $target_file = $upload_dir . basename($aimage);

        // Check if the uploaded file is an image
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_file_type, $allowed_file_types)) {
            if (move_uploaded_file($temp_name1, $target_file)) {
                // Image upload successful
            } else {
                $msg = "<p class='alert alert-warning'>Failed to upload the image.</p>";
                header("Location: aboutview.php?msg=$msg");
                exit;
            }
        } else {
            $msg = "<p class='alert alert-warning'>Only image files are allowed.</p>";
            header("Location: aboutview.php?msg=$msg");
            exit;
        }
    } else {
        // If no new image, keep the old image
        $sql = "SELECT image FROM about WHERE id = {$aid}";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $aimage = $row['image']; // Use the existing image if no new image uploaded
    }

    // Prepare the update SQL query
    $sql = "UPDATE about SET title = '{$title}', content = '{$content}', image = '{$aimage}' WHERE id = {$aid}";

    // Execute the update query
    $result = mysqli_query($con, $sql);
    if ($result) {
        $msg = "<p class='alert alert-success'>About Us updated successfully.</p>";
        header("Location: aboutview.php?msg=$msg");
        exit;
    } else {
        $msg = "<p class='alert alert-warning'>Failed to update About Us.</p>";
        header("Location: aboutview.php?msg=$msg");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>RealEstate</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/css/feathericon.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="assets/css/select2.min.css">

    <link rel="stylesheet" href="assets/plugins/summernote/dist/summernote-bs4.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Main Wrapper -->

    <!-- Header -->
    <?php include("header.php"); ?>

    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">About</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">About</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">About Us</h2>
                        </div>

                        <?php
                        // Get the data for the about us page
                        $sql = "SELECT * FROM about WHERE id = {$aid}";
                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <h5 class="card-title">About Us</h5>

                                            <!-- Title -->
                                            <div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Title</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" name="utitle" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Content</label>
                                                <div class="col-lg-9">
                                                    <textarea class="tinymce form-control" name="ucontent" rows="10" cols="30" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                                                </div>
                                            </div>

                                            <!-- Image -->
                                            <div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Image</label>
                                                <div class="col-lg-9">
                                                    <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" height="200px" width="200px"><br><br>
                                                    <input class="form-control" name="aimage" type="file">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-left">
                                        <input type="submit" class="btn btn-primary" value="Submit" name="update" style="margin-left:200px;">
                                    </div>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Select2 JS -->
    <script src="assets/js/select2.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>

</html>
