<?php
class BenDechrai_PackageProductManager_Model_Product extends Mage_Core_Model_Abstract 
{

  private $catalogProduct = null;

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/product');
  }

  public function getCatalogProduct()
  {
    if(is_null($this->catalogProduct)) {
      $this->catalogProduct = Mage::GetModel('catalog/product')->load($this->getCatalogProductEntityId());
    }
    return $this->catalogProduct;
  }

  public function unsetData() {
    $this->catalogProduct = null;
    return parent::unsetData();
  }

}

