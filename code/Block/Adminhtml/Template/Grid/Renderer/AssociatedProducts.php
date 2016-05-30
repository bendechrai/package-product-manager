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

      $href = $this->getUrl('*/catalog_product/edit', array('id'=>$product->getCatalogProduct()->getId()));
      $title = $product->getCatalogProduct()->getName();
      $qty = $product->getQty();
      $price = Mage::helper('core')->currency($product->getCatalogProduct()->getPrice(), true, false);

      $out .= "<div><a href=\"{$href}\" title=\"{$title}\">{$title}</a> ({$qty} @ {$price})</div>";

    }

    return $out;
  }
}
