--
-- Copyright 2020 by Robert B. Watson
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
USE `piclinic`;
--
TRUNCATE TABLE `clinic`;
INSERT INTO `clinic` (`thisClinic`,`publicID`,`typeCode`,`careLevel`,`longName`,`shortName`,`currency`,`address1`,`address2`,`clinicNeighborhood`,`clinicCity`,`clinicState`,`clinicRegion`,`clinicDirector`,`clinicService`,`modifiedDate`,`createdDate`) VALUES
	("0",NULL,"UAPS","Primario","Consolation Clinic","Consolation Clinic","HNL",NULL,NULL,"Consolation Bight","Consolation Bight, Roatan","Islas de la Bahia","11","Aleynska Grant","Outpatient",NOW(),NOW())
	, ("0","001348","UAPS","Primario","Vasijas de Misericordia","Vasijas de Misericordia","HNL",NULL,NULL,"French Cay Harbour","French Cay Harbour, Roatan","Islas de la Bahia","11","Dra. Sarai Raudales","Outpatient",NOW(),NOW())
	, ("0",NULL,"UAPS","Primario","Oak Ridge Community Health Association","Oak Ridge Community Health Association","HNL",NULL,NULL,"Oak Ridge","Oak Ridge, Roatan","Islas de la Bahia","11","Anneth Cooper","Outpatient",NOW(),NOW())
	, ("0",NULL,"UAPS","Primario","Clinic Saint Camillus","Saint Camillus","HNL",NULL,NULL,"Brisas de Mitch ","Isla de Guanaja","Islas de la Bahia","11","Dra. Mireya Edith Guillen","Outpatient",NOW(),NOW())
	, ("0",NULL,"UAPS","Primario","New Life Clinic","New Life Clinic","HNL","Oak Ridge Baptist Church","Oak Ridge Point","Oak Ridge","Roatan","Islas de la Bahia","11","Oak Ridge Baptist Church ","Outpatient",NOW(),NOW())
	;
