<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'odbc:Driver={ODBC Driver 18 for SQL Server};Server=tcp:alexisserver.database.windows.net,1433;Database=calfire_installs;Uid=alexismce;Pwd=Giselle4me520@@;Encrypt=yes;TrustServerCertificate=no;Connection Timeout=30';

try {
    $dbh = new PDO($dsn);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database successfully!";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
