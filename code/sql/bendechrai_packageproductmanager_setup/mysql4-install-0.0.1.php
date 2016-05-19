<?php
$installer = $this;
$installer->startSetup();
$installer->run("

    CREATE TABLE `{$installer->getTable('bendechrai_packageproductmanager/package')}` (
      `package_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `sku` varchar(64) DEFAULT NULL,
      `associated_products` varchar(255) DEFAULT NULL,
      `price_multiplier` decimal(12,4) DEFAULT NULL,
      `attributes` TEXT,
      `approved` tinyint(1) DEFAULT '0',
      `listed` tinyint(1) DEFAULT '0',
      PRIMARY KEY (`package_id`),
      UNIQUE KEY `sku` (`sku`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `{$installer->getTable('bendechrai_packageproductmanager/product')}` (
      `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `package_id` int(10) unsigned NOT NULL,
      `catalog_product_entity_id` int(10) unsigned DEFAULT NULL,
      `qty` smallint(5) unsigned NOT NULL,
      PRIMARY KEY (`product_id`),
      KEY `package_id` (`package_id`),
      KEY `catalog_product_entity_id` (`catalog_product_entity_id`),
      FOREIGN KEY (`package_id`) REFERENCES `{$installer->getTable('bendechrai_packageproductmanager/package')}`(`package_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (`catalog_product_entity_id`) REFERENCES `{$installer->getTable('catalog/product')}`(`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
$installer->endSetup();
