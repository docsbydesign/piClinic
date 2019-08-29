-- select and count the participants registered from the usability test
-- 
SELECT 
    TRIM(SUBSTR(`a`.`name`, POSITION(' ' in `a`.`name`))) as `lastName`,
	`a`.`name`,
    (CASE
    	WHEN COUNT(`a`.`name`) > 4
        THEN
        	20
        ELSE 
	     	COUNT(`a`.`name`)* 5 
    END) AS `extraCredit`,
    MIN(`a`.`commentDate`) AS `firstTest`, 
    MAX(`a`.`commentDate`) AS `lastTest`, 
    COUNT(`a`.`name`) AS `participationCount`
  FROM (
     SELECT `b`.`commentDate` as `commentDate`,`b`.`commentString` as `commentString`, TRIM(SUBSTR(`b`.`commentString`,1,POSITION('-' IN `b`.`commentString`)-1)) as `name` FROM (
        SELECT `commentDate`, 
            REPLACE(REPLACE(`commentText`,'<',''),'>','') as `commentString`
          FROM `comment`
          WHERE `commentText` like '%finished%') AS `b`      
    WHERE 1) AS `a`
WHERE 1
GROUP BY `a`.`name`
ORDER BY `lastName`;
