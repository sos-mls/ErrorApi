#!/bin/bash
# Install composer
sudo apt-get -y --force-yes install curl php-cli

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

cd /var/www/error_api/src/protected

composer update

cd ~