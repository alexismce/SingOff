#!/bin/bash

# Redirect stdout and stderr to a log file
exec > >(tee /var/log/startup.log | logger -t startup -s 2>/dev/console) 2>&1

# Update package lists
apt-get update -y

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
if [ -f composer.json ]; then
    composer install
else
    echo "composer.json not found, skipping Composer install."
fi

# Install unattended-upgrades for security updates
apt-get install -y unattended-upgrades
dpkg-reconfigure -plow unattended-upgrades
