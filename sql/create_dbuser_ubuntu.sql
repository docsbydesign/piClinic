--
-- Copyright (c) 2022 by Robert B. Watson
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy of
-- this software and associated documentation files (the "Software"), to deal in
-- the Software without restriction, including without limitation the rights to
-- use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
-- of the Software, and to permit persons to whom the Software is furnished to do
-- so, subject to the following conditions:
--
-- The above copyright notice and this permission notice shall be included in all
-- copies or substantial portions of the Software.
--
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
-- IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
-- FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
-- AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
-- LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
-- OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
-- SOFTWARE.
--
--
CREATE DATABASE IF NOT EXISTS `piclinic` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `piclinic`;
CREATE USER 'CTS-user'@'localhost' IDENTIFIED BY 'YOURPASSWORD';
GRANT FILE, SHOW DATABASES, CREATE TEMPORARY TABLES, LOCK TABLES, SHOW VIEW, EXECUTE ON  *.* TO 'CTS-user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON piclinic.* TO 'CTS-user'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS for 'CTS-user'@'localhost';
