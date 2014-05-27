ALTER TABLE #__gigcal_config 
 ADD COLUMN `cal_first_day` INTEGER  NOT NULL DEFAULT 0 AFTER `cal_delim`;

