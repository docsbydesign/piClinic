USE `piclinic_study`;
DROP TABLE IF EXISTS `wflog_study`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wflog_study` (
  `wflogId` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '(Autofill) Unique record ID for log records',
  `clinicID` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) The ID of the clinic from which the log was collected',
  `sourceModule` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Module creating the log entry.',
  `logQueryString` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Query string that initiated the
 action',
  `prevPage` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) page before the one logging the workflow',
  `prevLink` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Link used on the page before the one logging the workflow',
  `requestId` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Unique request ID provided by Web server',
  `userToken` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(optional) sessionID of user.',
  `logClass` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) type of log entry (home or sub step).',
  `wfName` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) workflow name',
  `wfGuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) unique workflow ID',
  `wfStep` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) workflow step',
  `wfHomeGuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) unique workflow ID of HOME step',
  `wfMicrotime` double DEFAULT '0' COMMENT '(optional) micro time stamp of log entry',
  `wfMicrotimeString` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Microtime value formatted as date/time string',
  `activeWorkflows` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) JSON string of session workflow list',
  `logBeforeData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) data.',
  `logAfterData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) data',
  `logStatusCode` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Status code of action.',
  `logStatusMessage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Text status message resulting from action.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this entry was created.',
  PRIMARY KEY (`wflogId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that logs workflow events.';
/*!40101 SET character_set_client = @saved_cs_client */;
