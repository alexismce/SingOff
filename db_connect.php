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

// Check if environment variables are set
if (!$servername || !$username || !$password || !$dbname) {
    die("Environment variables for database connection are not set.");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . " (" . $conn->connect_errno . ")");
}
?>
