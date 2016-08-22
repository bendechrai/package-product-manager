<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

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
      Mage::Helper('bendechrai_packageproductmanager')->importFromCsv($_FILES['csvfile']['tmp_name'], $overwrite);
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

          // Load existing package model
          $packageModel->load($packageId);

          // Check this product has nothing mapped
          if(is_null($packageModel->getMappedProductId())) {

            // Load the clashing catalog product
            $clashingCatalogProduct = Mage::GetModel('catalog/product')->load(Mage::GetModel('catalog/product')->getIdBySku($packageModel->getSku()));

            // If clashing product is a package product
            if($clashingCatalogProduct->getTypeId() === MageRevolution_PackageProductType_Model_Product_Type_Package::TYPE_CODE) {

              // Map this package model to the clashing product
              $packageModel->setMappedProductId($clashingCatalogProduct->getId());
              $packageModel->setCatalogProductExists(0);
              $packageModel->setReplaceExisting(0);

            } else {

              // Mark this package model as replacing existing
              $packageModel->setReplaceExisting(1);

            }

            $packageModel->save();
          }
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

  public function massUseExistingPackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    $mapped = 0;
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        foreach($packageIds as $packageId) {

          // Load and save package product defintition, so its metadata are all up to date
          $packageModel->load($packageId);
          $packageModel->save();

          // If catalog product exists
          if($packageModel->getCatalogProductExists()) {

            // Get catalog product
            $catalogProduct = Mage::GetModel('catalog/product')->load(Mage::GetModel('catalog/product')->getIdBySku($packageModel->getSku()));

            // If catalog product is not already mapped to this package product definition
            if($catalogProduct->getId() !== $packageModel->getMappedProductId()) {

              // Is the catalog product of type Package Product?
              if($catalogProduct->getTypeId() === MageRevolution_PackageProductType_Model_Product_Type_Package::TYPE_CODE) {

                // It's a package product. Map to this package product definition
                $packageModel->setMappedProductId($catalogProduct->getId());
                $packageModel->save();
                $mapped++;

              } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__(
                  '%s: type mismatch, catalog product %d is not a "Package Product"',
                  $packageModel->getSku(),
                  $catalogProduct->getId()
                ));
              }

            }
          }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('bendechrai_packageproductmanager')->__(
            'Total of %d package(s) were remapped to point to existing catalog products.', $mapped
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
            'Total of %d packages(s) were relisted.', count($packageIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

  public function massDeletePackagesAction()
  {
    $packageIds = $this->getRequest()->getParam('package_id');
    if(!is_array($packageIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bendechrai_packageproductmanager')->__('Please select one or more packages.'));
    } else {
      try {
        $packageModel = Mage::getModel('bendechrai_packageproductmanager/package');
        $deletedCount = 0;
        foreach ($packageIds as $packageId) {
          $packageModel->load($packageId);

          if(intval($packageModel->getMappedProductId()) > 0) {
            Mage::getSingleton('adminhtml/session')->addError("Product {$packageModel->getSku()} not deleted, because it exists in the catalog");
            continue;
          }

          if($packageModel->getApproved()) {
            Mage::getSingleton('adminhtml/session')->addError("Product {$packageModel->getSku()} not deleted, because it's approved for listing");
            continue;
          }

          if($packageModel->getListed()) {
            Mage::getSingleton('adminhtml/session')->addError("Product {$packageModel->getSku()} not deleted, because it's marked as listed");
            continue;
          }

          if($packageModel->delete()) $deletedCount++;
          
        }

        if($deletedCount>0) {
          Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bendechrai_packageproductmanager')->__('Total of %d packages(s) were deleted.', $deletedCount));
        }

      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
     
    $this->_redirect('*/*/index');
  }

}
