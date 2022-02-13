#/bin/bash
#
#	Instructions for running piClinic software on cat /etc/os-release in a
#   VirtualBox Version 6.1.32 r149290
#
#   This process can take from 60-90 minutes to complete.
#
#     Create a VirtualBox VM with these settings:
#         General: Ubuntu (64-bit) Note, this might depend on the host computers
#         System:
#           Base memory: 2048 MB or more
#           Processors: 2 or more
#           Boot order: Optical drive, Hard Disk
#           Display: 128MB, VMSVGA
#           Storage: Create or attach to a VDI 16GB or larger
#           Network adapter: Bridged
#           Shared folders: (as required)
#
#       Download the .iso for Ubuntu 20.04.3 LTS and attach as an optical disk image
#
#       Start VM and install OS.
#
#       After OS is installed, add Virtual box host utilities
#         sudo apt install virtualbox-guest-dkms virtualbox-guest-x11
#
#       Enable auto upgrades of OS
#
#*****************************************************************************
#
#   NOTE: Although this is written as a script, DO NOT EXECUTE IT as one.
#     There are numerous cases where the system must be restarted before
#     continuing and that is not accounted for in this script.
#
#     Use this script as a guide from which you can copy, paste, and edit
#     the commands as necessary.
#
#*****************************************************************************
#
# *************************************************************************
#		At this point the basic OS has been configured for the piClinic software
# *************************************************************************
#
# STEP 1: run the commands up to STEP 2 from the command line.
#       Installation script starts here
#
# install basic system software
sudo apt-get -y install nload
sudo apt install git
sudo apt install net-tools
sudo apt-get -y install exfat-fuse exfat-utils
sudo apt-get -y install apache2 apache2-doc libapache2-mod-php
sudo apt-get -y install libapache2-mod-php7.4
sudo apt-get -y install php7.4-common
sudo apt-get -y install php7.4-fpm
sudo apt-get -y install php7.4-mysql
sudo apt-get -y install php7.4
#
#	create a php info page
#   (note, these commands might need to be run from the su account by
#     entering sudo su before running these commands)
sudo echo '<?php phpinfo(); ?>' > /var/www/html/phpinfo.php
# 	set file permissions to let apache show the file.
sudo chown www-data:www-data /var/www/html/phpinfo.php
sudo chmod 750 /var/www/html/phpinfo.php
#
#	check web server by opening http://localhost in the browser
# check the php version by opening http://localhost/phpinfo.php in the browser
#
sudo apt install mysql-server
#
# Set mysql password
#   This must be done as a super user using (sudo su) access
#  	from: https://www.digitalocean.com/community/tutorials/how-to-reset-your-mysql-or-mariadb-root-password
#
sudo systemctl stop mysql
sudo mysqld_safe --skip-grant-tables --skip-networking &
mysql -u root
# in mysql
#	change new_password to your new root password.
#	  	FLUSH PRIVILEGES;
#     CREATE USER 'admin'@'localhost' IDENTIFIED BY 'new_password';
#     GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost';
#   If admin@localhost exists, just change its new_password
#     SET PASSWORD FOR 'admin'@'localhost' = PASSWORD('new_password');
# 	exit
#
sudo systemctl restart mysql
# test the new password
mysql -u admin -p
#	This should prompt you for the password.
#	Enter the one you just assigned above and you should get the MariaDB [(none)]> prompt.
# 	If it does, exit and continue
#	If not, try to reassign the password and try again.
#
# run this command on a production systems
sudo mysql_secure_installation
#
# comment the next line for a production system
#   Let phpmyadmin create a random password for its mysql access
#   You'll use the admin account created earlier to log in to PhpMyAdmin
sudo apt-get -y install phpmyadmin
#
# Verify the phpmyadmin installation by opening
#   http://localhost/phpmyadmin
#   and log in with the admin password created earlier
#
#	restart the system
sudo shutdown -r 0
#
#	After it restarts:
#		Check Apache: 	open http://localhost in a browser and make sure it displays the default page
#		Check PHP: 		open http://localhost/phpinfo.php to make sure it displays info about PHP
#		Check PhpMyAdmin (if installed) open and log into: http://localhost/phpmyadmin
#
# update the packages & restart
sudo apt-get update
sudo apt-get upgrade
sudo apt-get clean
sudo apt-get autoremove
# restart to begin application software install and config
sudo shutdown -r 0
#
# Configure PHP
#
#   review timezone strings from https://en.wikipedia.org/wiki/List_of_tz_database_time_zones
#		and pick the correct one for the system's location
#   edit the PHP ini file and change these settings on all configurations
sudo nano /etc/php/7.3/apache2/php.ini
#     		memory_limit = 512M
#     		date.timezone = <insert a standard UNIX timezone string>
#                           see the wikipedia link above, such as
#                               America/Tegucigalpa for Honduras
#                               America/Los_Angeles for Pacific time
#                               America/New_York for Eastern time
#
#   change these settings if you are configuring a development system
#
#     display_errors = On
#     display_startup_errors = On
#
#   save changes
#	restart apache
#		sudo systemctl restart apache2
#
#		open http://localhost/phpinfo.php to make sure the settings you updated have the values
#
#	Update the Apache configuration in  /etc/apache2/apache2.conf
sudo nano /etc/apache2/apache2.conf
#
#   Find the <Directory /var/www/> entry in the file and make it look like this
#
<Directory /var/www/>
        Options FollowSymLinks
        AllowOverride None
        Require all granted
        DirectoryIndex piclinic.php index.php index.html
</Directory>
#
#		TODO: Add other security commands and apache config here
#
#	restart apache (or the system)
sudo systemctl restart apache2
#
# *************************************************************************
#		At this point the system has the pre-configured SYSTEM System software
#			but it has no application (piClinic) software or settings.
#
#		The next steps configure the system for the piClinic application software
#
# *************************************************************************
#
# *****  Checkpoint CP1 *****
#
# *************************************************************************
#	The script has not been tested for the 2020-05-27 version of the OS
#			past this point.
#
#		REMOVE THIS MESSAGE AFTER THE SCRIPT HAS BEEN TESTED WITH THE NEW OS
#
# *************************************************************************
#
#	add users
#
#   add the clinic user and group
sudo groupadd clinic
sudo useradd clinic -g clinic
sudo usermod -a -G audio clinic
sudo usermod -a -G video clinic
#
#   set an initial password (to change during clinic installation)
# sudo passwd clinic
#
# 	create the home directory for the clinic account
#
sudo mkdir /home/clinic
sudo chown clinic /home/clinic
#
# 	Log into the clinic account and:
#		Open the browser and configure the settings for the piClinic
#			TODO: this should be documented somewhere...
# 		Turn off bluetooth
#
#	*** note: be sure to remove any wifi settings before saving the system image ***
#	*** 		and clear the browser history so it won't be replicated on other computers ***
#
# *************************************************************************
#		At this point the system has the pre-configured piClinic System software
#			and is what is stored on the saved base OS images.
#
#		The next steps install the piClinic application software
#
# *************************************************************************
#
#
#	Install the software from GitHub
#
cd ~
git clone https://github.com/docsbydesign/piClinic piClinic
#
# create app folders
sudo mkdir /var/local
sudo mkdir /var/local/piclinic
sudo mkdir /var/local/piclinic/image
sudo mkdir /var/local/piclinic/deleted
sudo mkdir /var/local/piclinic/downloads
sudo chown -R www-data:www-data /var/local/piclinic
sudo chmod -R 750 /var/local/piclinic
#
sudo mkdir /var/log/piclinic
sudo chown -R www-data:www-data /var/log/piclinic
sudo chmod -R 750 /var/log/piclinic
#
#
echo 'Test your web server now by opening http://localhost in a browser.'
echo 'Update the password in the ~/create_dbuser.sql file before installing the databases'
echo 'After editing the password file, follow the commands that follow and enter them manually as directed.'
#
exit
#
# copy this file and edit the password before running it
cp ~/piClinic/sql/create_dbuser_ubuntu.sql ~/.
#
# after editing the password in create_dbuser.sql,
#    run this command to create the db user account
sudo mysql -uroot  < ~/create_dbuser_ubuntu.sql
##
# install app database and database user account
cd ~/piClinic/sql
mysql -uadmin -pYOURPASSWORD < piclinc.sql
mysql -uadmin -pYOURPASSWORD < icd10.sql
#
# copy the app files to create the web site
sudo cp -R ~/piClinic/www/* /var/www/.
sudo chown -R www-data:www-data /var/www/*
sudo chmod -R 750 /var/www/*
sudo chmod -R 755 /var/www/scripts/*
#
# edit password(s) in /var/www/pass/dbPass.php to match the password you
#   put in in the create_dbuser.sql script
#
echo 'Edit the database password in /var/www/html/dbPass.php before running app.'
exit
#
#   For dev/test systems, load test databases
# example staff database
mysql -uadmin -pYOURPASSWORD < TestUsers.sql
# example patient databases
mysql -uadmin -pYOURPASSWORD < 100PatientsNum.sql
# mysql -uadmin -pYOURPASSWORD < 100Patients.sql
#
#------------------------------------
# STOP HERE to have a complete system, with the app, but with no patients
#   Continue to add test data for development and testing
#------------------------------------
#
# to update web site after a commit see the wiki
#
