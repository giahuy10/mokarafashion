DROP TABLE IF EXISTS `#__inventory_products`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_inventory.%');