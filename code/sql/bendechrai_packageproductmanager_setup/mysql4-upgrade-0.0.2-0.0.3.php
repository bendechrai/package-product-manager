<?php
$installer = $this;
$installer->startSetup();
$installer->run("

  ALTER TABLE `{$installer->getTable('bendechrai_packageproductmanager/package')}`
    ADD `mapped_product_id` int(10) unsigned DEFAULT NULL AFTER `package_id`,
    ADD FOREIGN KEY (`mapped_product_id`) REFERENCES `{$installer->getTable('catalog/product')}`(`entity_id`) ON DELETE SET NULL ON UPDATE CASCADE;

");
$installer->endSetup();
