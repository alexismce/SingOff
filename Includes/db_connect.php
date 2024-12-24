<?php
$servername = "calfi-server.mysql.database.azure.com";
$username = "alexismce";
$password = "Giselle4me520@@";
$dbname = "calfi-database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
