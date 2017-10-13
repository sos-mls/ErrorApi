#!/bin/bash

# Installing Apache
sudo apt-get -y --force-yes install apache2

# Get the current document root
DOC_ROOT=$(grep -i 'DocumentRoot' /etc/apache2/sites-enabled/000-default.conf)
CLEAN_DOC_ROOT=${DOC_ROOT// } # remove trailing spaces

# If the current root directory is not the sale cents directory then change it
if [ $CLEAN_DOC_ROOT = "DocumentRoot/var/www/html" ]; then
	# Enable the .htaccess file to be used
	sudo a2enmod rewrite
	sudo sed -i '166s/AllowOverride\ None/AllowOverride\ All/g' /etc/apache2/apache2.conf

	# Setup root directory for apache to be /var/www/website
	sudo sed -i 's/\/var\/www\/html/\/var\/www\/error_api\/src/g' /etc/apache2/sites-enabled/000-default.conf
fi

# Set Apache Variables
CONFIG_CONTENTS=$(<$SHELL_HOME/install/helpers/config.txt)
CONFIG_CONTENTS=${CONFIG_CONTENTS//$'\n'/\\$'\n'} # escape newlines
CONFIG_CONTENTS=${CONFIG_CONTENTS//\//\\\/} # escape slashes
sudo sed -i "2s/^/$CONFIG_CONTENTS\n/" /etc/apache2/sites-enabled/000-default.conf

# Installing PHP and it's dependencies
sudo apt-get -y --force-yes install php libapache2-mod-php php-mcrypt php-xml
sudo apt-get -y --force-yes install curl libcurl3 libcurl3-dev php-curl
sudo phpenmod mcrypt

# Enabled CORS
sudo a2enmod headers

# Restart apache server after updating its root directory
sudo service apache2 restart

# For GII!!!
sudo mkdir /var/www/error_api/src/assets