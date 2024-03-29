--
-- Copyright (c) 2018 by Robert B. Watson
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
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `cts`
--
USE `piclinic`;
TRUNCATE TABLE `clinic`;
INSERT INTO `clinic` (`publicID`,`typeCode`,`careLevel`,`shortName`,`clinicCity`,`clinicState`,`clinicRegion`,`clinicDirector`,`modifiedDate`,`createdDate`) VALUES

  (19,'CIS','Primario','ALONZO SUAZO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [19] Name',NOW(),NOW()),
  (27,'CIS','Primario','FLOR DEL CAMPO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [27] Name',NOW(),NOW()),
  (35,'CIS','Primario','LOS PINOS (SAN BENITO)','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [35] Name',NOW(),NOW()),
  (51,'CIS','Primario','PEDREGAL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [51] Name',NOW(),NOW()),
  (60,'CIS','Primario','VILLA ADELA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [60] Name',NOW(),NOW()),
  (78,'UAPS','Primario','EL AGUACATE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [78] Name',NOW(),NOW()),
  (94,'UAPS','Primario','YAGUACIRE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [94] Name',NOW(),NOW()),
  (108,'CIS','Primario','SAN MIGUEL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [108] Name',NOW(),NOW()),
  (116,'CIS','Primario','EL BOSQUE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [116] Name',NOW(),NOW()),
  (124,'CIS','Primario','EL CHILE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [124] Name',NOW(),NOW()),
  (132,'CIS','Primario','EL MANCHEN','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [132] Name',NOW(),NOW()),
  (141,'CIS','Primario','MONTERREY','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [141] Name',NOW(),NOW()),
  (159,'CIS','Primario','NUEVA SÜYAPA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [159] Name',NOW(),NOW()),
  (167,'UAPS','Primario','JUTIAPA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [167] Name',NOW(),NOW()),
  (175,'UAPS','Primario','MONTE REDONDO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [175] Name',NOW(),NOW()),
  (183,'UAPS','Primario','RIO HONDO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [183] Name',NOW(),NOW()),
  (191,'UAPS','Primario','SAN JUAN DEL RANCHO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [191] Name',NOW(),NOW()),
  (205,'UAPS','Primario','SANTA ELENA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [205] Name',NOW(),NOW()),
  (213,'CIS','Primario','3 DE MAYO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [213] Name',NOW(),NOW()),
  (221,'CIS','Primario','LAS CRUCITAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [221] Name',NOW(),NOW()),
  (230,'CIS','Primario','SAN FRANCISCO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [230] Name',NOW(),NOW()),
  (248,'CIS','Primario','SAGRADA FAMILIA (ALEMANIA)','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [248] Name',NOW(),NOW()),
  (256,'CIS','Primario','DIVANNA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [256] Name',NOW(),NOW()),
  (264,'CIS','Primario','EL EDEN','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [264] Name',NOW(),NOW()),
  (272,'UAPS','Primario','SAN JOSE DE SOROGUARA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [272] Name',NOW(),NOW()),
  (281,'HOSP NAC','Secundario','ESCUELA UNIVERSITARIO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [281] Name',NOW(),NOW()),
  (299,'HOSP NAC','Secundario','INCP','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [299] Name',NOW(),NOW()),
  (302,'HOSP NAC','Secundario','MATERNO INFANTIL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [302] Name',NOW(),NOW()),
  (311,'HOSP NAC','Secundario','GENERAL SAN FELIPE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [311] Name',NOW(),NOW()),
  (329,'HOSP NAC','Secundario','PSIQ. SANTA ROSITA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [329] Name',NOW(),NOW()),
  (337,'HOSP NAC','Secundario','PSIQ. MARIO MENDOZA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [337] Name',NOW(),NOW()),
  (710,'UAPS','Primario','SAN JUANCITO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [710] Name',NOW(),NOW()),
  (833,'UAPS','Primario','COFRADIA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [833] Name',NOW(),NOW()),
  (841,'UAPS','Primario','SAN MATIAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [841] Name',NOW(),NOW()),
  (850,'UAPS','Primario','SAN FRANCISCO DE SOROGUARA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [850] Name',NOW(),NOW()),
  (868,'CIS','Primario','TAMARA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [868] Name',NOW(),NOW()),
  (876,'CIS','Primario','ZAMBRANO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [876] Name',NOW(),NOW()),
  (973,'UAPS','Primario','LA VENTA DEL NORTE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [973] Name',NOW(),NOW()),
  (7307,'UAPS','Primario','EL TIZATILLO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [7307] Name',NOW(),NOW()),
  (7315,'UAPS','Primario','LA CUESTA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [7315] Name',NOW(),NOW()),
  (7391,'UAPS','Primario','BRISAS DEL PICACHO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [7391] Name',NOW(),NOW()),
  (8443,'CIS','Primario','CENTRO AMERICA OESTE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8443] Name',NOW(),NOW()),
  (8460,'UAPS','Primario','LAS TORRES','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8460] Name',NOW(),NOW()),
  (8478,'UAPS','Primario','MATEO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8478] Name',NOW(),NOW()),
  (8494,'UAPS','Primario','OSCAR A. FLORES','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8494] Name',NOW(),NOW()),
  (8508,'CLIPER','Primario','LAS CRUCITAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8508] Name',NOW(),NOW()),
  (8516,'CIS','Primario','EL CARRIZAL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8516] Name',NOW(),NOW()),
  (8524,'CIS','Primario','LA PROVIDENCIA (LA JOYA)','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8524] Name',NOW(),NOW()),
  (8532,'UAPS','Primario','CONCEPCION DEL RIO GRANDE','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8532] Name',NOW(),NOW()),
  (8541,'CIS','Primario','VILLANUEVA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8541] Name',NOW(),NOW()),
  (8842,'CIS','Primario','PENITECIARIA NACIONAL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [8842] Name',NOW(),NOW()),
  (9407,'UAPS','Primario','LA BOTIJA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [9407] Name',NOW(),NOW()),
  (9431,'UAPS','Primario','RIO FRIO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [9431] Name',NOW(),NOW()),
  (10146,'UAPS','Primario','LAS PILAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [10146] Name',NOW(),NOW()),
  (70001,'IHSS','Primario','PERIFERICA No. 1','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [70001] Name',NOW(),NOW()),
  (70002,'IHSS','Primario','PERIFERICA No. 2','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [70002] Name',NOW(),NOW()),
  (70003,'IHSS','Primario','PERIFERICA No. 3','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [70003] Name',NOW(),NOW()),
  (70004,'IHSS','Primario','HOSPITAL MATERNO INFANTIL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [70004] Name',NOW(),NOW()),
  (70017,'IHSS','Primario','CENTRO MEDICO QUIRURGICO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [70017] Name',NOW(),NOW()),
  (80136,'CIS','Primario','LA CAÑADA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80136] Name',NOW(),NOW()),
  (80144,'UAPS','Primario','CARPINTERO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80144] Name',NOW(),NOW()),
  (80268,'UAPS ','Primario','CRUZ ROJA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80268] Name',NOW(),NOW()),
  (80276,'UAPS','Primario','RIO ABAJO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80276] Name',NOW(),NOW()),
  (80349,'UAPS','Primario','VILLAS EL PORVENIR','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80349] Name',NOW(),NOW()),
  (80438,'CLINICA','Primario','PNFAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [80438] Name',NOW(),NOW()),
  (81609,'UAPS','Primario','CUIDAD ESPAÑA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [81609] Name',NOW(),NOW()),
  (81647,'UAPS','Primario','21 DE FEBRERO','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [81647] Name',NOW(),NOW()),
  (81655,'UAPS','Primario','VILLA VIEJA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [81655] Name',NOW(),NOW()),
  (81744,'UAPS','Primario','LA PUERTA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [81744] Name',NOW(),NOW()),
  (82015,'UAPS','Primario','DIVINA PROVIDENCIA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [82015] Name',NOW(),NOW()),
  (82066,'UAPS','Primario','SAN MIGUEL ARCANGEL','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [82066] Name',NOW(),NOW()),
  (82091,'UAPS','Primario','ALDEA DE SANTA ROSA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [82091] Name',NOW(),NOW()),
  (82546,'CIS','Primario','NUEVA ESPERANZA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [82546] Name',NOW(),NOW()),
  (85235,'CIS','Primario','SOLIDARIDAD HONDURAS','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [85235] Name',NOW(),NOW()),
  (85391,'CIS','Primario','CIUDAD MUJER','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [85391] Name',NOW(),NOW()),
  (85405,'UAPS','Primario','EL PILIGUIN','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [85405] Name',NOW(),NOW()),
  (19003,'CIS','Primario','CODOPA','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [19003] Name',NOW(),NOW()),
  (85430,'HOSP NAC','Secundario','HOSPITAL MILITAR','Tegucigapla','Francisco Morazán','Metropolitana del D.C.','Director [85430] Name',NOW(),NOW());

-- Set This CLINIC
UPDATE `clinic` SET `ThisClinic`=0,`modifiedDate`=now() WHERE TRUE;
UPDATE `clinic` SET `ThisClinic`=1,`modifiedDate`=now() WHERE `PublicID` = 205;
