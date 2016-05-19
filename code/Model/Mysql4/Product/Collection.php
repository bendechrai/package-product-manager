<?php
class BenDechrai_PackageProductManager_Model_Mysql4_Product_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/product');
  }

}
