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
      $overwrite = ($this->getRequest()->getParam('overwrite') == 'overwrite');
      $this->_importFromCsv($_FILES['csvfile']['tmp_name'], $overwrite);
    }

    $this->loadLayout()->_setActiveMenu('catalog/packageproductmanager');
    $this
      ->_title($this->__('Package Product Manager'))
      ->_title($this->__('Packages'))
      ->_title($this->__('Import'))
      ->_addBreadcrumb($this->__('Package Product Manager'), $this->__('Package Product Manager'))
      ->_addBreadcrumb($this->__('Packages'), $this->__('Packages'))
      ->_addBreadcrumb($this->__('Import'), $this->__('Import'))
      ->renderLayout();
  }

  public function massApprovePackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->setApproved(1);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) were approved.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massUnapprovePackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->setApproved(0);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) were unapproved.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massReplaceExistingPackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->setReplaceExisting(1);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) marked as allowed to replace existing catalog products.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massDontReplaceExistingPackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->setReplaceExisting(0);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) marked as not allowed to replace existing catalog products.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massRefreshPackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) were refreshed.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massRelistPackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);
          $packageModel->setListed(0);
          $packageModel->save();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d packages(s) were refreshed.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  /**
   * Takes a file name, imports contents, and creates BenDechrai_PackageProductManager_Model_Package
   *
   * @access private
   * @param string $filename
   * @param boolean $overwrite
   * @return void
   */
  private function _importFromCsv($filename, $overwrite=false) {

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

        $attributes = array();
        foreach($row as $key=>$value) {
          $key = $firstRow[$key];
          if(in_array($key, $productAttributes)) {
            $attributes[$key] = $value;
          }
        }
        
        // Overwrite?
        if($overwrite) {
          $package->load($sku, 'sku');
          $package->delete();
        }

        // Create the package
        $package->unsetData();
        $package->setSku($sku);
        $package->setAssociatedProducts($associatedProducts);
        $package->setPriceMultiplier($priceMultiplier);
        $package->setAttributes(json_encode($attributes));

        // Try saving - might fail if duplicate sku
        try {
          $package->save();

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

          $packagesCreated++;

        } catch(Exception $e) {
          $package->delete();
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
