<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Helper_Data extends Mage_Core_Helper_Abstract {

  /**
   * Takes a file name, imports contents, and creates BenDechrai_PackageProductManager_Model_Package
   *
   * @access public
   * @param string $filename
   * @param boolean $overwrite
   * @return void
   */
  public function importFromCsv($filename, $overwrite=false) {

    if(($fh = fopen($filename, 'r')) !== false) {

      // Get first line and map values
      if(($firstRow = fgetcsv($fh, 10000)) === false) {
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not read first line of CSV file'));
        return;
      } else {
        $skuIndex = null;
        $associatedProductsIndex = null;
        $priceMultiplierIndex = null;
        $categoriesIndex = null;
        foreach($firstRow as $index=>$key) {
          $key = strtolower($key);
          if($key == 'sku') {
            $skuIndex = $index;
          }
          if($key == 'associated products') {
            $associatedProductsIndex = $index;
          }
          if($key == 'price multiplier') {
            $priceMultiplierIndex = $index;
          }
          if($key == 'categories') {
            $categoriesIndex = $index;
          }
        }
        if(is_null($skuIndex)) {
          Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not find "sku" column'));
        }
        if(is_null($associatedProductsIndex)) {
          Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not find "associated products" column'));
        }
        if(is_null($skuIndex) || is_null($associatedProductsIndex)) return;
      }

      // Get requisite models
      $package = Mage::GetModel('bendechrai_packageproductmanager/package');
      $packageProduct = Mage::GetModel('bendechrai_packageproductmanager/product');
      $catalogProduct = Mage::GetModel('catalog/product');
      $packagesCreated = 0;
      $packagesUpdated = 0;

      // Get list of product attributes
      $productAttributes = array();
      foreach (Mage::getResourceModel('catalog/product_attribute_collection') as $attr) {
        $attr = $attr->getAttributeCode();
        if(!in_array($attr, array('sku'))) {
          $productAttributes[] = $attr;
        }
      }
      unset($attr);

      // Loop through rest of the file
      while(($row = fgetcsv($fh, 10000)) !== false) {

        $sku = trim($row[$skuIndex]);
        if($sku == '') continue;

        $associatedProducts = trim($row[$associatedProductsIndex]);

        if(is_null($priceMultiplierIndex)) {
          $priceMultiplier = (float)1;
        } else {
          $priceMultiplier = (float)trim($row[$priceMultiplierIndex]);
        }

        $categoryIds = preg_replace('#[^0-9,]#', '', $row[$categoriesIndex]);
        $categoryIds = preg_replace('#[,]+#', ',', $categoryIds);
        $categoryIds = trim($categoryIds, ',');

        $attributes = array();
        foreach($row as $key=>$value) {
          $key = $firstRow[$key];
          if(in_array($key, $productAttributes)) {
            $attributes[$key] = $value;
          }
        }

        // Start with a new package
        $package->unsetData();
        
        // Overwrite? Load in details
        if($overwrite) {
          $package->load($sku, 'sku');
        }

        // Create the package
        $package->setSku($sku);
        $package->setAssociatedProducts($associatedProducts);
        $package->setPriceMultiplier($priceMultiplier);
        $package->setCategoryIds($categoryIds);
        $package->setAttributes(json_encode($attributes));

        // Try saving - might fail if duplicate sku
        try {
          $package->save();

          // Delete existing associated products from database
          foreach(Mage::GetModel('bendechrai_packageproductmanager/product')->getCollection()->addFieldToFilter('package_id', $package->getId()) as $packageProduct) {
            $packageProduct->delete();
          }

          // Loop through product definitions
          foreach(explode(',', $associatedProducts) as $associatedProduct) {

            // Get package product definition
            list($associatedQty, $associatedSku) = explode('@', trim($associatedProduct));
            $associatedQty = (int)trim($associatedQty);
            $associatedSku = trim($associatedSku);

            // Load product from catalog
            $catalogProduct->unsetData();
            $catalogProduct->load($catalogProduct->getIdBySku($associatedSku));
            if($catalogProduct->getId()) {

              // Add package product
              $packageProduct->unsetData();
              $packageProduct->setPackageId($package->getId());
              $packageProduct->setCatalogProductEntityId($catalogProduct->getEntityId());
              $packageProduct->setQty($associatedQty);
              $packageProduct->save();

            } else {

              Mage::getSingleton('adminhtml/session')->addError(sprintf(Mage::helper('bendechrai_packageproductmanager')->__('Could not find %s in catalog while setting up package product %s'), $associatedSku, $sku));
              throw new Exception();

            }

          } // End loop through associated products

          if($overwrite) {
            $packagesUpdated++;
          } else {
            $packagesCreated++;
          }

        } catch(Exception $e) {
          $package->delete();
          Mage::getSingleton('adminhtml/session')->addError(sprintf(Mage::helper('bendechrai_packageproductmanager')->__('Could not create package with sku %s'), $sku));
        }

      } // End loop through rest of file

      if(($packagesUpdated + $packagesCreated) > 0) {
        Mage::getSingleton('adminhtml/session')->addSuccess(sprintf(Mage::helper('bendechrai_packageproductmanager')->__('Successfully added %d, and updated %d package(s)'), $packagesCreated, $packagesUpdated));
      }

    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not open file'));
    }
      

  }

}
