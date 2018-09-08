#!/bin/bash
sleep 15
sudo rm /var/lib/apt/lists/lock
sudo rm /var/cache/apt/archives/lock
sudo rm /var/lib/dpkg/lock
sudo dpkg --configure -a
# update the machine
sudo apt -y --force-yes update

#install vim ... cause why not
sudo apt -y --force-yes install vim nmap

#install git for getting necessary packages
sudo apt -y --force-yes install git 
