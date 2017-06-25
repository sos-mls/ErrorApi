#!/bin/bash

# Installing MySQL and it's dependencies, Also, setting up root password for MySQL as it will prompt to enter the password during installation
sudo debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password password $DEFAULT_PASSWORD"
sudo debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password_again password $DEFAULT_PASSWORD"
sudo apt-get -y --force-yes install mysql-server libapache2-mod-auth-mysql php-mysql

# Install Developer Schema
sudo mysql --user="root" --password=$DEFAULT_PASSWORD < $SHELL_HOME/sql/error_api.sql
sudo mysql --user="root" --password=$DEFAULT_PASSWORD < $SHELL_HOME/sql/error_api_test.sql

# Set timeout for mysql
sudo mysql_tzinfo_to_sql /usr/share/zoneinfo/|mysql --user="root" --password=$DEFAULT_PASSWORD mysql

sudo mysql --user="root" --password=$DEFAULT_PASSWORD -e "SET sds = 'America/New_York'"