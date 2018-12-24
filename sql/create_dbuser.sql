CREATE DATABASE IF NOT EXISTS `piclinic` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `piclinic`;
GRANT USAGE ON *.* TO 'CTS-user'@'localhost' IDENTIFIED BY 'YOURPASSWORD';
GRANT FILE  ON *.* TO 'CTS-user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON piclinic.* TO 'CTS-user'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS for 'CTS-user'@'localhost';
