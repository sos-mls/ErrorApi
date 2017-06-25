#!/bin/bash

# install cron
sudo apt-get -y --force-yes install cron

# add test console command to the cron tab
crontab -l | { cat; echo "* * * * * run-one sudo service mysql start"; } | crontab -