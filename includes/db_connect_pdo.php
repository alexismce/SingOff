echo "<?php
try {
    \$conn = new PDO('sqlsrv:server = tcp:alexisserver.database.windows.net,1433; Database = calfire_installs', 'alexismce', 'Giselle4me520@@');
    \$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to SQL Server successfully!';
} catch (PDOException \$e) {
    echo 'Error connecting to SQL Server.';
    die(print_r(\$e));
}
?>" > db_connect_pdo.php
