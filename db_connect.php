<?php
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
if (!$dotenv->load()) {
    die("Failed to load environment variables.");
}

$servername = getenv('DB_SERVERNAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');
$flag = getenv('DB_FLAG');

// Check if environment variables are set
if (!$servername || !$username || !$password || !$dbname || !$port || !$flag) {
    die("Environment variables for database connection are not set.");
}

// Create connection with SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, "/path/to/ca-cert.pem", NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, MYSQLI_CLIENT_SSL);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . " (" . $conn->connect_errno . ")");
}
?>
