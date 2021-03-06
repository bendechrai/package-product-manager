<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract
{

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/product', 'product_id');
  }   

}
