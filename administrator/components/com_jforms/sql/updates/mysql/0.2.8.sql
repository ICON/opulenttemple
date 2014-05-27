ALTER TABLE `#__jforms_submissions` ADD `password` VARCHAR(255);
ALTER TABLE `#__jforms_submissions` ADD UNIQUE (`password`);