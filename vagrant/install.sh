#!/bin/bash
# folder directory for the vagrant machine should be:
# 
# 		intall 	 -> /home/vagrant/install
# 		sql 	 -> /home/vagrant/sql
# 		src 	 -> /var/www/assetapi/src
# 
# If this is not the case please reference the Vagrantfile in the root of the project.
# 

. /home/vagrant/install/helpers/init-env.sh '/home/vagrant'

# install apache, mysql, and php ... and allow the user to run these files
scripts=(init.sh mysql.sh apache_php.sh composer.sh cron.sh phpunit.sh)
n_elements=${#scripts[@]}


for ((i = 0; i < $n_elements; i ++)); do
	start_script ${scripts[i]}
	. $SHELL_HOME/install/${scripts[i]}
	end_script ${scripts[i]}
done

. $SHELL_HOME/install/status.sh

. $SHELL_HOME/install/helpers/destroy-env.sh