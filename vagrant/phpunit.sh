#!/bin/bash

# Install PHP Unit
wget https://phar.phpunit.de/phpunit-6.2.phar
sudo chmod +x phpunit-6.2.phar
sudo mv phpunit-6.2.phar /usr/local/bin/phpunit
