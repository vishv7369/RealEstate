<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Redirect if accessed without form submission
if (!isset($_POST['calc'])) {
    header("Location: filter-property.php");
    exit();
}

$amount = $_POST['amount'];
$mon = $_POST['month'];
$int = $_POST['interest'];

// Validate inputs
if (empty($amount) || empty($mon) || empty($int) || !is_numeric($amount) || !is_numeric($mon) || !is_numeric($int)) {
    $_SESSION['emi_error'] = "Please enter valid numeric values for all fields.";
    header("Location: filter-property.php");
    exit();
}

// EMI calculation
$monthly_interest_rate = ($int / 12) / 100;
$n = $mon;
$emi = ($amount * $monthly_interest_rate * pow(1 + $monthly_interest_rate, $n)) / (pow(1 + $monthly_interest_rate, $n) - 1);
$total_payment = $emi * $n;
$total_interest = $total_payment - $amount;
$years = $mon / 12;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>EMI Calculator | RealEstate</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("include/header.php"); ?>

<div class="full-row bg-gray">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-12">
                <h2 class="text-secondary double-down-line text-center">EMI Calculation Result</h2>
            </div>
        </div>
        <center>
            <table class="table table-bordered col-lg-6">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>Term</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Property Price</td>
                        <td>$<?php echo number_format($amount, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Total Duration</td>
                        <td><?php echo number_format($years, 2); ?> Years</td>
                    </tr>
                    <tr>
                        <td>Monthly EMI</td>
                        <td>$<?php echo number_format($emi, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Total Interest</td>
                        <td>$<?php echo number_format($total_interest, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Total Payment</td>
                        <td>$<?php echo number_format($total_payment, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="filter-property.php" class="btn btn-secondary mt-3">Back to Filter</a>
        </center>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
