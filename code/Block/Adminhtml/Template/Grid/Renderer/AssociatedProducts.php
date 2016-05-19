<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_AssociatedProducts extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  public function render(Varien_Object $row)
  {
    $out = '';

    $products = Mage::GetModel('bendechrai_packageproductmanager/product')
      ->getCollection()
      ->addFieldToFilter('package_id', $row->getPackageId());

    foreach($products as $product) {
      $out .= "
        <div class=\"bd-ppm-grid-product\">{$product->getQty()} x {$product->getCatalogProduct()->getName()}</div>
      ";
    }

    return $out;
  }
}
