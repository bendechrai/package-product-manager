<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Model_Package extends Mage_Core_Model_Abstract 
{

  protected function _construct()
  {
    $this->_init('bendechrai_packageproductmanager/package');
  }

  public function save()
  {

    $existingProductId = intval(Mage::GetModel('catalog/product')->getIdBySku($this->getSku()));
    $this->setCatalogProductExists($existingProductId > 0 && $existingProductId != $this->getMappedProductId());

    if(!$this->getMappedProductId()) $this->setListed(0);

    return parent::save();
  }

  public function getCatalogProduct()
  {
    if(is_null($this->catalogProduct)) {
      $this->catalogProduct = Mage::GetModel('catalog/product')->load($this->getMappedProductId());
    }
    return $this->catalogProduct;
  }

  public function getPrice() {

    $products = Mage::GetModel('bendechrai_packageproductmanager/product')
      ->getCollection()
      ->addFieldToFilter('package_id', $this->getPackageId());

    $total = 0;
    foreach($products as $product) {
      $total += $product->getQty() * $product->getCatalogProduct()->getPrice();
    }

    return $total * $this->getPriceMultiplier();
  }

  public function getProducts() {
    return Mage::GetModel('bendechrai_packageproductmanager/product')
      ->getCollection()
      ->addFieldToFilter('package_id', $this->getPackageId());
  }

}

