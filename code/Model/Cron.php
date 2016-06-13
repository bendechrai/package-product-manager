<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Model_Cron {

  private $log = array();

  public function run() {

    // Get a list of packages to work on, and loop through the list
    $this->log[] = 'Find 20 packages that need to be listed';
    $packages = Mage::GetModel('bendechrai_packageproductmanager/package')->getCollection()->limitToList()->setPageSize(20);
    foreach($packages as $package) {

      $sku = $package->getSku();
      $this->log[] = "Processing package with sku $sku";

      if(!$package->isComplete()) {
        $this->log[] = "- package is incomplete. Skipping and unapproving";
        $package->setApproved(0);
        $package->save();
        continue;
      }

      // Try and load existing catalog product. Not using helper attributes, to make sure we're getting absolute latest data.
      $existingCatalogProduct = Mage::GetModel('catalog/product')->load(Mage::GetModel('catalog/product')->getIdBySku($sku));
      if($existingCatalogProduct->getId()) {

        // Did we find the mapped product?
        if($existingCatalogProduct->getId() == $package->getMappedProductId()) {

          // Forget it - we're about to update that product...
          $existingCatalogProduct->unsetData();

        } else {

          // Another existing product was found
          $this->log[] = "- found existing product with id {$existingCatalogProduct->getId()}";

          // Double check we're allowed to replace it
          if(!$package->getReplaceExisting()) {
            $this->log[] = "- this packge can't replace existing products. Skipping.";
            continue;
          }

          // Prepare to rename the SKU so we can use it. Skip if new SKU is already taken
          $backupSku = substr($sku . '_bd_ppm_backup', 0, 64);
          if(Mage::GetModel('catalog/product')->getIdBySku($backupSku)) {
            $this->log[] = "- created backup sku $backupSku, but it's already in use. Skipping.";
            continue;
          }

          // Prepare to rename the URL key. Skip if new URL already taken
          $urlKey = $existingCatalogProduct->getUrlKey();
          $backupUrlKey = $urlKey . '_bd_ppm_backup';
          if(Mage::GetModel('catalog/product')->getCollection()->addAttributeToFilter('url_key', $backupUrlKey)->getFirstItem()->getId()) {
            $this->log[] = "- created backup urlKey $backupUrlKey, but it's already in use. Skipping.";
            continue;
          }

          // Do the renaming, now we know it's safe
          $this->log[] = "- renaming catalog product from $sku to $backupSku";
          $existingCatalogProduct->setSku($backupSku);

          $this->log[] = "- renaming catalog url from $urlKey to $backupUrlKey (without permanent redirect)";
          $existingCatalogProduct->setUrlKey($backupUrlKey);
          $existingCatalogProduct->setData('save_rewrites_history', false);

          $this->log[] = "- disabling this product";
          $existingCatalogProduct->setStatus(2); // 1 = Enabled; 2 = Disabled

          // Save changes to product, so URL and SKU can be reused.
          $existingCatalogProduct->save();

        }

      }

      // Load or create new product
      if(intval($package->getMappedProductId()) > 0) {
        $this->log[] = "Updating existing catalog product";
        $catalogProduct = Mage::GetModel('catalog/product')->load($package->getMappedProductId());
      } else {
        $this->log[] = "Creating new catalog product";
        $catalogProduct = Mage::GetModel('catalog/product');
      }

      // Set defaults for this product, that can be overridden by the package
      $catalogProduct->setTaxClassId(2);        // 2 = Taxable Goods
      $catalogProduct->setAttributeSetId(4);    // 4 = Default Set
      $catalogProduct->setWebsiteIds(array(1)); // 1 = Default Website
      $catalogProduct->setStatus(1);            // 1 = Enabled; 2 = Disabled
      $catalogProduct->setVisibility(4);        // 4 = Catalog & Search

      // Set data from attributes. This wwill overwrite anything already set,
      // so put sensitive settings after this, for example, a "type_id" column.
      $attributes = json_decode($package->getAttributes(),1);
      foreach($attributes as $key=>$value) {
        $this->log[] = "- Setting $key to $value";
        $catalogProduct->setData($key, $value);
      }

      // Set type as package product
      $this->log[] = "- Setting type to 'package'";
      $catalogProduct->setTypeId(MageRevolution_PackageProductType_Model_Product_Type_Package::TYPE_CODE);
 
      // Set sku
      $this->log[] = "- Setting sku to $sku";
      $catalogProduct->setSku($sku);

      // Set price
      $this->log[] = "- Setting price to " . $package->getPrice();
      $catalogProduct->setPrice($package->getPrice());

      // Set categories
      $this->log[] = "- Setting category ids to " . $package->getCategoryIds();
      $catalogProduct->setCategoryIds($package->getCategoryIds());

      // Use disable stock control
      $catalogProduct->setData('stock_data',array(
        'use_config_manage_stock'=>0,
        'manage_stock'=>0
      ));

      // ======
      // IMAGES
      // ======

      // Get details from existing product
      if($existingCatalogProduct->getId()) {

        $this->log[] = "- Setting URL key to $urlKey";
        $catalogProduct->setUrlKey($urlKey);

        foreach($existingCatalogProduct->getMediaGalleryImages() as $image) {
          $this->log[] = "- Adding image {$image->getPath()} from existing product {$existingCatalogProduct->getId()}";
          try {
            $catalogProduct->addImageToMediaGallery($image->getPath(), array('image', 'small_image', 'thumbnail'), false, false);
          } catch(Exception $e) {
            $this->log[] = "-- Couldn't add image";
          }
        }

      }

      // Does this product have any images now?
      $imageCount = count($catalogProduct->getMediaGalleryImages());
      $defaultImageSet = false;

      // Add associated products
      $linkdata = array();
      foreach($package->getProducts() as $packageProduct) {

        // If this product has no images yet, add these associated products' immages
        if($imageCount == 0) {
          $childProduct = Mage::GetModel('catalog/product')->load($packageProduct->getCatalogProductEntityId());
          foreach($childProduct->getMediaGalleryImages() as $image) {
            $this->log[] = "- Adding image {$image->getPath()} from child product {$childProduct->getId()}";
            try {
              if(!$defaultImageSet) {
                $catalogProduct->addImageToMediaGallery($image->getPath(), array('image', 'small_image', 'thumbnail'), false, false);
                $defaultImageSet = true;
              } else {
                $catalogProduct->addImageToMediaGallery($image->getPath(), array(), false, false);
              }
            } catch(Exception $e) {
              $this->log[] = "-- Couldn't add image";
            }
          }
        }

        // Append to link data
        $linkdata[$packageProduct->getCatalogProductEntityId()] = array(
          'position'    => 0,
          'package_qty' => $packageProduct->getQty(),
        );

      }
      $catalogProduct->setPackageLinkData($linkdata);

      try {

        // Try saving the new product
        $catalogProduct->save();
        $this->log[] = "Product saved";

        // Map this new product to the package, and mark package as listed
        $package->setMappedProductId($catalogProduct->getId());
        $package->setListed(1);
        $package->save();

      } catch(Exception $e) {
        $this->log[] = "Error saving product during listing: " . $e->getMessage();
      }

    }

    return implode("\n", $this->log);
  }

}
