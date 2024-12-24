echo "<?php
\$connectionInfo = array(
    'UID' => 'alexismce',
    'pwd' => 'Giselle4me520@@',
    'Database' => 'calfire_installs',
    'LoginTimeout' => 30,
    'Encrypt' => 1,
    'TrustServerCertificate' => 0
);
\$serverName = 'tcp:alexisserver.database.windows.net,1433';
\$conn = sqlsrv_connect(\$serverName, \$connectionInfo);

if (\$conn) {
    echo 'Connected to SQL Server successfully!';
} else {
    echo 'Connection failed.';
    die(print_r(sqlsrv_errors(), true));
}
?>" > db_connect_sqlsrv.php
