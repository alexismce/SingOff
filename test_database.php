<?php
$servername = "alexisserver.database.windows.net";
$username = "alexismce";
$password = "Giselle4me520@@";
$dbname = "calfire_installs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>

