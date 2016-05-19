<?php
$installer = $this;
$installer->startSetup();
$installer->run("

  ALTER TABLE `{$installer->getTable('bendechrai_packageproductmanager/package')}`
    ADD `replace_existing` tinyint(1) NOT NULL DEFAULT 0,
    ADD `catalog_product_exists` tinyint(1) NOT NULL DEFAULT 0;

");
$installer->endSetup();
