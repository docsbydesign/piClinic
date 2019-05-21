USE `piclinic`;
--
TRUNCATE TABLE `clinic`;
INSERT INTO `clinic` (`thisClinic`,`publicID`,`typeCode`,`careLevel`,`longName`,`shortName`,`currency`,`address1`,`address2`,`clinicNeighborhood`,`clinicCity`,`clinicState`,`clinicRegion`,`clinicDirector`,`clinicService`,`modifiedDate`,`createdDate`) VALUES 
	("0",NULL,"UAPS","Primario","Consolation Clinic","Consolation Clinic","HNL",NULL,NULL,"Consolation Bight","Consolation Bight, Roatan","Islas de la Bahia","11","Aleynska Grant","Outpatient",NOW(),NOW()),
	("0","001348","UAPS","Primario","Vasijas de Misericordia","Vasijas de Misericordia","HNL",NULL,NULL,"French Cay Harbour","French Cay Harbour, Roatan","Islas de la Bahia","11","Dra. Sarai Raudales","Outpatient",NOW(),NOW()),
	("0",NULL,"UAPS","Primario","Oak Ridge Community Health Association","Oak Ridge Community Health Association","HNL",NULL,NULL,"Oak Ridge","Oak Ridge, Roatan","Islas de la Bahia","11","Anneth Cooper","Outpatient",NOW(),NOW());