<?php
class BenDechrai_PackageProductManager_Model_Cron {

  private $log = array();

  public function run() {

    $catalogProduct = Mage::GetModel('catalog/product');
    $package = Mage::GetModel('bendechrai_packageproductmaganer/package');

    $this->log[] = 'Find 20 packages that need to be listed';
    $packages = Mage::GetModel('bendechrai_packageproductmanager/package')->getCollection()->limitToList()->setPageSize(20);
    foreach($packages as $package) {

      $this->log[] = "Processing package with sku {$package->getSku()}";

      // Try and load existing catalog product. Not using helper attributes, to make sure we're getting absolute latest data.
      $existingCatalogProduct = $catalogProduct->load($catalogProduct->getIdBySku($package->getSku()));
      if($existingCatalogProduct->getId()) {

        $this->log[] = "- found existing product with id {$existingCatalogProduct->getId()}";

        if(!$package->getReplaceExisting()) {
          $this->log[] = "- this packge can't replace existing products. Skipping.";
          continue;
        }

        $backupSku = substr($package->getSku() . '_bd_ppm_backup', 0, 64);
        if($catalogProduct->getIdBySku($backupSku)) {
          $this->log[] = "- created backup sku $backupSku, but it's already in use. Skipping.";
          continue;
        }

        $this->log[] = "- renaming catalog product from {$package->getSku()} to $backupSku";

      }

    }

    return implode("\n", $this->log);
  }

}
