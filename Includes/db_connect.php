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

// Check if environment variables are set
if (!$servername || !$username || !$password || !$dbname || !$port) {
    die("Environment variables for database connection are not set.");
}

try {
    $conn = new PDO("sqlsrv:server = tcp:$servername,$port; Database = $dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}
?>