#!/bin/bash

# Redirect stdout and stderr to a log file
exec > >(tee /var/log/startup.log | logger -t startup -s 2>/dev/console) 2>&1

# Exit immediately if a command exits with a non-zero status
set -e

# Print each command to stdout before executing it
set -x

# Update package lists and install necessary packages
apt-get update -y
apt-get install -y php8.2 php8.2-odbc unixodbc unixodbc-dev \
                   curl gnupg2 ca-certificates lsb-release apt-transport-https

# Install PHP extensions
pecl install sqlsrv pdo_sqlsrv

# Enable the PHP extensions
echo "extension=sqlsrv.so" > /etc/php/8.2/mods-available/sqlsrv.ini
echo "extension=pdo_sqlsrv.so" > /etc/php/8.2/mods-available/pdo_sqlsrv.ini
phpenmod sqlsrv pdo_sqlsrv

# Install Composer if not already installed
if ! [ -x "$(command -v composer)" ]; then
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# Navigate to the web root directory
cd /home/site/wwwroot

# Install Composer dependencies
if [ -f composer.json ]; then
    composer install --no-dev --optimize-autoloader
else
    echo "composer.json not found, skipping Composer install."
fi

# Install Node.js dependencies if package.json exists
if [ -f package.json ]; then
    npm install
    npm run build
else
    echo "package.json not found, skipping npm install."
fi

# Set appropriate permissions
chown -R www-data:www-data /home/site/wwwroot
chmod -R 755 /home/site/wwwroot

# Install unattended-upgrades for security updates
apt-get install -y unattended-upgrades
dpkg-reconfigure -plow unattended-upgrades

# Start the PHP built-in server
php -S 0.0.0.0:8000 -t public

# Log the startup process completion
echo "Startup script executed successfully" >> /var/log/startup.log

# Keep the script running to ensure the container doesn't exit
tail -f /dev/null
