<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Render price as a list of all component products (multiplied by their qty), a sub total, the price multiplier as a percentage, and a final cost
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
  public function render(Varien_Object $row)
  {
    $out = '';
    $total = 0;

    foreach($row->getProducts() as $product) {
      $out .= "<div>+" . Mage::helper('core')->currency($product->getQty() * $product->getCatalogProduct()->getPrice(), true, false) . "</div>";
      $total += $product->getQty() * $product->getCatalogProduct()->getPrice();
    }

    $out .= "<div>=" . Mage::helper('core')->currency($total, true, false) . "</div>";
    $out .= "<div>@" . Mage::helper('core')->currency($row->getPriceMultiplier()*100, true, false) . "</div>";
    $out .= "<div>=" . Mage::helper('core')->currency($total * $row->getPriceMultiplier(), true, false) . "</div>";

    return $out;
  }
}
