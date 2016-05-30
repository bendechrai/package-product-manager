<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_AssociatedProducts extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Render associated products as:
   *   Product name (qty @ price)
   * with product name linked to the catalog product
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
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
