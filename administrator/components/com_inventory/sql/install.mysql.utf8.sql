CREATE TABLE IF NOT EXISTS `#__inventory_products` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`category` TEXT NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`code` VARCHAR(255)  NOT NULL ,
`price` INT(11)  NOT NULL ,
`old_price` INT(11)  NOT NULL ,
`color` VARCHAR(255)  NOT NULL ,
`material` VARCHAR(255)  NOT NULL ,
`neck` VARCHAR(255)  NOT NULL ,
`sleeve` VARCHAR(255)  NOT NULL ,
`type` VARCHAR(255)  NOT NULL ,
`skirt` VARCHAR(255)  NOT NULL ,
`input_price` VARCHAR(255)  NOT NULL ,
`size_s` VARCHAR(255)  NOT NULL ,
`size_m` VARCHAR(255)  NOT NULL ,
`size_l` VARCHAR(255)  NOT NULL ,
`size_xl` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Product','com_inventory.product','{"special":{"dbtable":"#__inventory_products","key":"id","type":"Product","prefix":"InventoryTable"}}', '{"formFile":"administrator\/components\/com_inventory\/models\/forms\/product.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_inventory.product')
) LIMIT 1;
