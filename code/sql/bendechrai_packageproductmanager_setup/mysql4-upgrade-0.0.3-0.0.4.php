<?php
$installer = $this;
$installer->startSetup();
$installer->run("

  ALTER TABLE `{$installer->getTable('bendechrai_packageproductmanager/package')}`
    ADD `category_ids` TEXT;

");
$installer->endSetup();
