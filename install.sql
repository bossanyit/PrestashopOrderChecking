CREATE TABLE if not exists `ps_order_product_checked` (
	`id_checked` INT(10) NOT NULL AUTO_INCREMENT,
	`id_order` INT(10) NOT NULL DEFAULT '0',
	`id_product` INT(10) NOT NULL DEFAULT '0',
	`id_product_attribute` INT(10) NOT NULL DEFAULT '0',
	`quantity` INT(10) NOT NULL DEFAULT '0',
	`checked` TINYINT NOT NULL DEFAULT '0',
	PRIMARY KEY (`id_checked`),
	INDEX `id_order` (`id_order`),
	INDEX `id_product` (`id_product`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
