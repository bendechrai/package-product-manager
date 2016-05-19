<?php
class BenDechrai_PackageProductManager_Package_Product_ManagerController extends Mage_Adminhtml_Controller_Action
{

  public function indexAction()
  {
    $this->loadLayout()->_setActiveMenu('catalog/packageproductmanager');
    $this
      ->_title($this->__('Package Product Manager'))
      ->_title($this->__('Packages'))
      ->_addBreadcrumb($this->__('Package Product Manager'), $this->__('Package Product Manager'))
      ->_addBreadcrumb($this->__('Packages'), $this->__('Packages'))
      ->renderLayout();
  }

  public function importAction()
  {

    // If POST request, handle submission
    if ($data = $this->getRequest()->getPost() && isset($_FILES['csvfile']) && is_array($_FILES['csvfile']) && isset($_FILES['csvfile']['tmp_name'])) {
      $this->_importFromCsv($_FILES['csvfile']['tmp_name']);
    }

    $this->loadLayout()->_setActiveMenu('catalog/packageproductmanager');
    $this
      ->_title($this->__('Package Product Manager'))
      ->_title($this->__('Packages'))
      ->_title($this->__('Import'))
      ->_addBreadcrumb($this->__('Package Product Manager'), $this->__('Package Product Manager'))
      ->_addBreadcrumb($this->__('Packages'), $this->__('Packages'))
      ->_addBreadcrumb($this->__('Import'), $this->__('Import'))
      ->_addContent($this->getLayout()->createBlock('bendechrai_packageproductmanager/adminhtml_import_fileupload'))
      ->renderLayout();
  }

  /**
   * Takes a file name, imports contents, and creates BenDechrai_PackageProductManager_Model_Package
   *
   * @access private
   * @param string $filename
   * @return void
   */
  private function _importFromCsv($filename) {

    if(($fh = fopen($filename, 'r')) !== false) {

      // Get first line and map values
      if(($firstRow = fgetcsv($fh, 10000)) === false) {
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not read first line of CSV file'));
        return;
      } else {
        $skuIndex = null;
        $associatedProductsIndex = null;
        $priceMultiplierIndex = null;
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

      // Loop through rest of the file
      while(($row = fgetcsv($fh, 10000)) !== false) {

        $sku = $row[$skuIndex];
        $associatedProducts = $row[$associatedProductsIndex];
        if(is_null($priceMultiplierIndex)) {
          $priceMultiplier = (float)1;
        } else {
          $priceMultiplier = (float)$row[$priceMultiplierIndex];
        }

        // Create the package
        $package->unsetData();
        $package->setSku($sku);
        $package->setAssociatedProducts($associatedProducts);
        $package->setPriceMultiplier($priceMultiplier);
        $package->setAttributes(json_encode(array_combine($firstRow, $row)));

        // Try saving - might fail if duplicate sku
        try {
          $package->save();
          $packagesCreated++;

          // Loop through product definitions
          foreach(explode(',', $associatedProducts) as $associatedProduct) {

            // Get package product definition
            list($associatedQty, $associatedSku) = explode('@', trim($associatedProduct));
            $associatedQty = (int)trim($associatedQty);
            $associatedSku = trim($associatedSku);

            // Load product from catalog
            $catalogProduct->load($catalogProduct->getIdBySku($associatedSku));
            if($catalogProduct->getId()) {

              // Add package product
              $packageProduct->unsetData();
              $packageProduct->setPackageId($package->getId());
              $packageProduct->setCatalogProductEntityId($catalogProduct->getEntityId());
              $packageProduct->setQty($associatedQty);
              $packageProduct->save();

            }

          } // End loop through associated products

        } catch(Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError(sprintf(Mage::helper('bendechrai_packageproductmanager')->__('Could not create package with sku %s'), $sku));
        }

      } // End loop through rest of file

      if($packagesCreated>0) {
        Mage::getSingleton('adminhtml/session')->addSuccess(sprintf(Mage::helper('bendechrai_packageproductmanager')->__('Successfully added %d package(s) to the queue'), $packagesCreated));
      }

    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Could not open file'));
    }
      

  }

}
