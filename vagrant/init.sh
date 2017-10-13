#!/bin/bash
sudo rm /var/lib/dpkg/lock
sudo dpkg --configure -a
# update the machine
sudo apt -y --force-yes update

#install vim ... cause why not
sudo apt -y --force-yes install vim nmap

#install git for getting necessary packages
sudo apt -y --force-yes install git 
