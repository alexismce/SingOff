<?php
$dsn = 'odbc:Driver={ODBC Driver 18 for SQL Server};Server=tcp:alexisserver.database.windows.net,1433;Database=calfire_installs;Uid=alexismce;Pwd=Giselle4me520@@;Encrypt=yes;TrustServerCertificate=no;Connection Timeout=30';
$username = 'alexismce';
$password = 'Giselle4me520@@';

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database successfully!";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
