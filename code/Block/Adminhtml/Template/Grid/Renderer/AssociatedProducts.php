<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_AssociatedProducts extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  public function render(Varien_Object $row)
  {
    $out = '';

    foreach($row->getProducts() as $product) {
      $out .= "
        <div class=\"bd-ppm-grid-product\">
          {$product->getQty()}
          x
          <a href=\"{$this->getUrl('*/catalog_product/edit', array('id'=>$product->getCatalogProduct()->getId()))}\"
            title=\"{$product->getCatalogProduct()->getName()}\">{$product->getCatalogProduct()->getName()}</a>
          @
          $" . sprintf("%0.2f", $product->getCatalogProduct()->getPrice()) . "
        </div>
      ";
    }

    return $out;
  }
}
