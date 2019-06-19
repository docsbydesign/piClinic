USE `piclinic`;
UPDATE `patient` 
	SET 
		`familyID`=CONCAT(`homeCity`,'-',CAST(CEIL(RAND()*100) AS char)) 
	WHERE 1; 
UPDATE `patient` 
	SET 
		`clinicPatientID`=CONCAT(`familyID`,'-',CAST(CEIL(RAND()*10) AS char)) 
	WHERE 1; 	
	