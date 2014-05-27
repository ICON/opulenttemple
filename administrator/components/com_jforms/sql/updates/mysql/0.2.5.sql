ALTER TABLE `#__jforms_forms` ADD `alias` VARCHAR(255);
ALTER TABLE `#__jforms_forms` ADD UNIQUE (`alias`);