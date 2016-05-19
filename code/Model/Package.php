<?php
class BenDechrai_PackageProductManager_Model_Package extends Mage_Core_Model_Abstract 
{

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/package');
  }

  public function save()
  {
    $this->setCatalogProductExists((intval(Mage::GetModel('catalog/product')->getIdBySku($this->getSku())) > 0));
    return parent::save();
  }

}

