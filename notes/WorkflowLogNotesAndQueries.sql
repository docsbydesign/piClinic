--
--	Summarize WFLog Data
--    to show time in each workflow accomplished
--

SELECT `wfGuid`,
	`logClass`,
	`wfName`,
	ROUND(max(`wfMicrotime`)-min(`wfMicrotime`),4) as `Elapsed Time` from `wflog`
where `logClass` = 'SF'
group by `wfGuid`;


DROP TABLE IF EXISTS `wfStats`;
CREATE TEMPORARY TABLE IF NOT EXISTS `wfStats`
( `wfGuid` VARCHAR(64), `wfName`  VARCHAR(64), `eTime` DOUBLE)
SELECT
	`wfGuid`,
	`wfName`,
	ROUND (max(`wfMicrotime`)-min(`wfMicrotime`),4) as `Elapsed Time`
FROM `wflog`
WHERE `logClass` = 'SF'
	AND `createdDate` >= '2019-04-19'
GROUP BY `wfGuid`;
SELECT * FROM `wfStats`;
SELECT
	`wfName`,
    COUNT(*) as `count`,
	AVG (`Elapsed Time`) as `avgTime`
FROM `wfStats`
GROUP BY `wfName`
ORDER BY `wfName`;

-- Report performance
SELECT `wfGuid`,
    `logClass`,
	`wfName`,
	`logAfterData`,
	(CASE
	    WHEN `logAfterData` REGEXP '^{"count":\s*[0-9]+}$'
	    THEN
--   	    CONVERT (REGEXP_SUBSTR(`logAfterData`,'[0-9]+'), SIGNED)
--  the preceding doesn't work on MySQL 5.x
     		CONVERT (TRIM(SUBSTR(`logAfterData`,10,LENGTH(`logAfterData`)-10)), SIGNED)
    	ELSE
    	    NULL
	END) AS `pt_count` ,
	ROUND(max(`wfMicrotime`)-min(`wfMicrotime`),4) as `Elapsed Time` from `wflog`
where `logClass` = 'RP'
group by `wfGuid`;

SELECT min(`createdDate`) as 'StartTime', max(`createdDate`) as `EndDate`
	FROM `wflog`;
