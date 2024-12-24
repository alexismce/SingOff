<?php
require_once 'includes/db_connect.php'; // Include for DB connection

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal System Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css?v=1.0"> <!-- Cache busting -->
    <link rel="stylesheet" href="css/style.css?v=1.0"> <!-- Cache busting, adjusted path -->
</head>
<body>
    <div class="container">
        <h1>Welcome!</h1>
        <p>Select the process you need to perform:</p>
        <div class="row">
            <!-- Sign-Off Process -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Sign-Off Process</h3>
                        <p>Complete the sign-off process for installations.</p>
                        <a href="includes/sign_off.php" class="btn btn-primary">Go to Sign-Off</a>
                    </div>
                </div>
            </div>
            <!-- Inventory Update -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Inventory Update</h3>
                        <p>Update the inventory with new devices.</p>
                        <a href="includes/inventory_update.php" class="btn btn-primary">Go to Inventory</a>
                    </div>
                </div>
            </div>
            <!-- Provisioning Devices -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Provisioning Devices</h3>
                        <p>Provision devices for installation and testing.</p>
                        <a href="includes/provisioning.php" class="btn btn-primary">Go to Provisioning</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Device Testing -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Device Testing</h3>
                        <p>Perform testing on the devices.</p>
                        <a href="includes/testing.php" class="btn btn-primary">Go to Testing</a>
                    </div>
                </div>
            </div>
            <!-- Track Shipping & Installations -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Track Shipping & Installations</h3>
                        <p>Track the status of shipping and installations.</p>
                        <a href="includes/tracking.php" class="btn btn-primary">Go to Tracking</a>
                    </div>
                </div>
            </div>
            <!-- Logout -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Logout</h3>
                        <p>Log out of the internal system.</p>
                        <a href="includes/logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
