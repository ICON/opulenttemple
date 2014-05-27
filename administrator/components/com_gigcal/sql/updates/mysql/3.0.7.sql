ALTER TABLE #__gigcal_config 
 ADD COLUMN `upcom_group_days` INTEGER NOT NULL DEFAULT 0 AFTER `upcom_timeformat`,
 ADD COLUMN `upcom_hrule` INTEGER  NOT NULL DEFAULT 0 AFTER `upcom_group_days`;

