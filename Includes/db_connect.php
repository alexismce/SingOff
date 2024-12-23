<?php
$servername = getenv('DB_SERVERNAME');
$dbusername = getenv('DB_USERNAME');
$dbpassword = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
