#/bin/bash
#
#	Instructions updated for 2020-05-27 release of Raspberry Pi OS (32-bit)
#   with desktop
#
#   This process can take from 60-90 minutes to complete.
#
# For ease of copying the base image:
#   1. Install and configure this software as described below
#       on an 8-GB MicroSD card.
#   2. Save the image of the MicroSD card as an .img file.
#   3. Load the image on to a high-speed 32GB (or larger) microSD card.
#   4. After booting from the new MicroSD card, run raspi-config and
#       in Advanced Options, select option A1 to make the entire SD card
#       available to the OS.
#   5. The system should be ready to run normally after that
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
#	Start here if your installing a clean OS as downloaded from raspberrypi.org.
#   Install the version that HAS the GUI desktop but DOES NOT HAVE the apps
#   The required apps will be installed by this procedure
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
#   We'll run the update shortly
#
#	After this, the pi will reboot to the desktop.
#
#	In a terminal window. Remove some unused software before continuing.
sudo apt-get purge dillo
sudo apt-get clean
sudo apt-get autoremove
sudo shutdown -r 0
#
# 	In a terminal window update the OS.
sudo apt-get update
sudo apt-get upgrade
sudo apt-get clean
sudo apt-get autoremove
sudo shutdown -r 0
#
#	Update the system config file for the piClinic configuration.
#	Open config.txt and change/uncomment, or add if not present, these config parameters
sudo nano /boot/config.txt
#			disable_overscan=1
#			hdmi_group=1
#			hdmi_mode=16
#     hdmi_blanking=1
#     hdmi_drive=2
#
sudo shutdown -r 0
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
#	      (confirm, these should be configured at initial boot)
#     LOCALE: en-US, UTF-8
#	     TIMEZONE
#			  (confirm, these should be configured at initial boot)
#	       your local timezone (e.g. AMERICA/New_York for Eastern time
#	     KEYBOARD
#			  (confirm, these should be configured at initial boot)
#	       as desired: (e.g. United States, English)
#		  WiFi COUNTRY:
#			  (confirm, these should be configured at initial boot)
#
#	Turn off bluetooth from the icon in the system menu bar.
#
#   Save changes and restart.
sudo shutdown -r 0
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
#			sudo nano /etc/rc.local
#				add these lines before the exit command:
#					echo ds1307 0x68 > /sys/class/i2c-adapter/i2c-1/new_device
#					sudo hwclock -s
#					date
#				save changes and exit
#		5) Restart the pi
#			after it restarts, make sure the time is correct
#     this command displays the realtime clock's time
#       sudo hwclock -r
#
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
sudo apt-get -y install nload
sudo apt-get -y install exfat-fuse exfat-utils
sudo apt-get -y install apache2 apache2-doc libapache2-mod-php
sudo apt-get -y install libapache2-mod-php7.3 php7.3-common php7.3-fpm php7.3-mysql php7.3
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
#   These commands install the Raspian version of mysql, a.k.a. mariadb
#
sudo apt-get -y install mariadb-server-10.0
sudo apt-get -y install mariadb-client-10.0
#
# Set mysql password
#   This must be done as a super user using (sudo su) access
#  	from: https://www.digitalocean.com/community/tutorials/how-to-reset-your-mysql-or-mariadb-root-password
#
sudo systemctl stop mariadb
sudo mysqld_safe --skip-grant-tables --skip-networking &
mysql -u root
# in mysql
#	change new_password to your new root password.
#	  	FLUSH PRIVILEGES;
#     CREATE USER 'root'@'localhost' IDENTIFIED BY 'new_password';
#     GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost';
#   If root@localhost exists, just change its new_password
#     SET PASSWORD FOR 'root'@'localhost' = PASSWORD('new_password');
#
#   create an admin password (this works better for user access to
#     administer the database)
#     CREATE USER 'admin'@'localhost' IDENTIFIED BY 'new_password';
#     GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost';
#
# 		exit
# kill mysqld_safe process, first	list running processes:
#		 ps
#	find id of mysqld_safe process and use it in the following command
#		sudo kill <id>
#
# restart the system
sudo shutdown -r 0
#	start the real service
#
sudo systemctl start mariadb
# test the new password
sudo mysql -u root -p
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
#   	sudo nano /etc/php/7.3/apache2/php.ini
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
