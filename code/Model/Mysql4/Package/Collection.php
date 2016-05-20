<?php
class BenDechrai_PackageProductManager_Model_Mysql4_Package_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/package');
  }

  public function limitToList() {
    $this->addFieldToFilter('approved', '1');
    $this->addFieldToFilter('listed', '0');

    // Ensure, if catalog product exists, it can be replaced
    $this->getSelect()->where('catalog_product_exists=0 OR catalog_product_exists=replace_existing');

    return $this;
  }

}
