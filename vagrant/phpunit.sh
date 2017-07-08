#!/bin/bash

# Install PHP Unit
wget https://phar.phpunit.de/phpunit-4.8.phar
sudo chmod +x phpunit-4.8.phar
sudo mv phpunit-4.8.phar /usr/local/bin/phpunit
