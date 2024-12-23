#!/bin/bash

# Update package lists
apt-get update

# Install necessary packages
apt-get install -y php8.2 php8.2-odbc unixodbc unixodbc-dev

# Install PHP extensions
pecl install sqlsrv pdo_sqlsrv

# Enable the PHP extensions
echo "extension=sqlsrv.so" > /etc/php/8.2/mods-available/sqlsrv.ini
echo "extension=pdo_sqlsrv.so" > /etc/php/8.2/mods-available/pdo_sqlsrv.ini
phpenmod sqlsrv pdo_sqlsrv

# Install Composer dependencies
cd /home/site/wwwroot
composer install
