#/bin/bash
#
#	Instructions updated for 27-Jun-2018 release of Raspbian Stretch
#
#	Start here if your installing a clean OS as downloaded from raspberrypi.org.
#	If you are starting from a pre-configured piClinic OS image, start futher down the page
#
#	Power up system with fresh OS on SD card.
#	Walk through initialization wizard
#
#	Set Country
#		For a dev system:
#			Country: United States
#			Language: American English
#			Timezone: (as appropriate)
#		For a customer system:
#			Country: Honduras
#			Language: Spanish
#			Timezone: Tegucigalpa
#
#	Change Password
#		Set default pi account password
#
#	Select WiFi Network
#		Select as appropriate for DEV system
#		Skip (don't connect) on a customer system and use a wired network for updates
#
#	Check for Updates
#		Click skip (or you'll be sorry...)
#
#	After this, the pi will reboot to the desktop.
#
#	In a terminal window. Remove some unused software before continuing.
#		sudo apt-get purge wolfram-engine scratch scratch2 minecraft-pi sonic-pi dillo oracle-java8-jdk oracle-java7-jdk
#		sudo apt-get clean
#		sudo apt-get autoremove
#		sudo shutdown -r 0
#
# 	In a terminal window update the OS.
#		sudo apt-get update
#		sudo apt-get upgrade
#		sudo apt-get clean
#		sudo apt-get autoremove
#		sudo shutdown -r 0
#
#	Update the system config file for the piClinic configuration.
#	Open config.txt and change/uncomment, or add if not present, these config parameters
#		sudo nano /boot/config.txt
#			disable_overscan=1
#			hdmi_group=1
#			hdmi_mode=16
#			hdmi_blanking=1
#     		hdmi_drive=2
#
#		sudo shutdown -r 0
#
# configure the basic PI settings
#   open Raspberry Pi Configuration from the Preferences menu
#     SYSTEM page
#		Hostname: piclinic
#       Auto Login: uncheck Login as user 'pi'
#     INTERFACES page
#       enable these and leave the others disabled unless needed for your configuration
#         SPI
#         I2C
#     PERFORMANCE page
#       leave as default
#     LOCALISATION
#		(confirm, these should be configured at initial boot)
#     LOCALE: en-US, UTF-8
#	     TIMEZONE
#			(confirm, these should be configured at initial boot)
#	       your local timezone (e.g. AMERICA/New_York for Eastern time
#	     KEYBOARD
#			(confirm, these should be configured at initial boot)
#	       as desired: (e.g. United States, English)
#		  WiFi COUNTRY:
#			(confirm, these should be configured at initial boot)
#
#	Turn off bluetooth from the icon in the system menu bar.
#
#   Save changes and restart.
#		sudo shutdown -r 0
#
#	Configure the RealTime clock (make sure that it's been installed on the Pi board.)
# 	This is easiest if done while connected to the Internet
#
#		1) Load the RTC module by entering:
#			sudo modprobe rtc-ds1307
#		2) Restart the pi
#		3) Add the device to the list of modules
#			sudo nano /etc/modules
#				add "rtc-ds1307" (without quotes) to the end of the file, and save the changes
#				save changes and exit
#		4) Add the device to the rc file before the "exit 0" command
#			sudo nano /etc/rc.local/
#				add these lines before the exit command:
#					echo ds1307 0x68 > /sys/class/i2c-adapter/i2c-1/new_device
#					sudo hwclock -s
#					date
#				save changes and exit
#		5) Restart the pi
#			make sure the time is correct
#			if not, refer to https://thepihut.com/blogs/raspberry-pi-tutorials/17209332-adding-a-real-time-clock-to-your-raspberry-pi from where these instructions were found
#
# *************************************************************************
#		At this point the basic OS has been configured for the piClinic hardware
# *************************************************************************
#
# STEP 1: run the commands up to STEP 2 from the command line.
#       Installation script starts here
#
# install basic system software
# sudo apt-get -y install nload
sudo apt-get -y install exfat-fuse exfat-utils
sudo apt-get -y install apache2 apache2-doc libapache2-mod-php
sudo apt-get -y install libapache2-mod-php7.0 php7.0-common php7.0-fpm php7.0-mysql php7.0
#
#	create a php info page
#
sudo echo '<?php phpinfo(); ?>' > /var/www/html/phpinfo.php
# 	set file permissions to let apache show the file.
sudo chown www-data:www-data /var/www/html/phpinfo.php
sudo chmod 750 /var/www/html/phpinfo.php
#
#	check web server by opening http://localhost in the browser
#
sudo apt-get -y install mysql-server
sudo apt-get -y install mysql-client
#
# Set mysql password
#  	from: https://www.digitalocean.com/community/tutorials/how-to-reset-your-mysql-or-mariadb-root-password
#
sudo systemctl stop mariadb
sudo mysqld_safe --skip-grant-tables --skip-networking &
mysql -u root
# in mysql
#	change new_password to your new root password.
#		FLUSH PRIVILEGES;
#		GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY PASSWORD 'new_password';
# 		exit
# kill mysqld_safe process
#	list running processes:
#		ps
#	find id of mysqld_safe process and use it in the following command
#		sudo kill <id>
#
#	start the real service
#
sudo systemctl start mariadb
# test the new password
mysql -u root -p
#	This should prompt you for the password.
#	Enter the one you just assigned above and you should get the MariaDB [(none)]> prompt.
# 	If it does, exit and continue
#	If not, try to reassign the password and try again.
#
# uncomment the next line for production systems
# sudo mysql_secure_installation
#
# comment the next line for a production system
sudo apt-get -y install phpmyadmin
#
# Verify the phpmyadmin installation
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
#   	sudo nano /etc/php/7.0/apache2/php.ini
#     		memory_limit = 512M
#     		date.timezone = <insert a standard UNIX timezone string from the wikipedia link above, such as America/Tegucigalpa for Honduras>
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
#	Remove directory listing from Apache
#
#	edit the \<Directory\> entries in /etc/apache2/apache2.conf and remove the \"Indexes\" option
#
#		TODO: Add other security commands and apache config here
#
#	restart apache (or the system)
#		sudo systemctl restart apache2
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
#	On first boot from a saved image, it might be necessary to resize the FS to
#	use the avaliable memory on the microSD card
#
#	From a terminal window, run
#		df -h
#
#	if the size of the /dev/root partition doesn't match the size of the SD card,
#		from a terminal window, run
#			sudo raspi-config
#
#		Select:
#			7. Advanced options
#			A1 Expand Filesystem
#
#		Click OK to exit
#		Select the reboot option to restart the system
#
#	The system will restart a couple of times. When it shows the login prompt
#		log in and check the new size of /dev/root.
#			df -h
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
cp ~/piClinic/sql/create_dbuser.sql ~/.
#
# after editing the password in create_dbuser.sql,
#    run this command to create the db user account
sudo mysql -uroot -pYOURPASSWORD  < ~/create_dbuser.sql
##
# install app database and database user account
cd ~/piClinic/sql
sudo mysql -uroot -pYOURPASSWORD < piclinc.sql
# sudo mysql -uroot -pYOURPASSWORD < HondurasClinics.sql
sudo mysql -uroot -pYOURPASSWORD < TestUsers.sql
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
#------------------------------------
# STOP HERE to have a complete system, with the app, but with no patients
#   Continue to add test data for development and testing
#------------------------------------
#
# load test patients into the database
# cd ~/piClinic/tools
# change GEN-PAT-1- to your patient ID prefix. Patients will be numbered squentially.
#  change 100 to however many patient records you want to create
#  the script takes about 15 minutes to add 10,000 patients and their photos
# python3 create-patients.py GEN-PAT-1- 100
#
# load test visits into the database
#   change 100 to the number of days worth of data you want to create
#   change 50 to the number of visits per day you want to create
#
# python3 create-visits.py 100  50
#
# ------------------------------------------------
#
# to update web site after a commit see the wiki
#
