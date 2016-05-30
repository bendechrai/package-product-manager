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

    $products = Mage::GetModel('bendechrai_packageproductmanager/product')
      ->getCollection()
      ->addFieldToFilter('package_id', $row->getPackageId());

    $prices = array();
    $out = '';
    $total = 0;
    foreach($products as $product) {
      $out .= '+$' . sprintf('%0.2f', $product->getQty() * $product->getCatalogProduct()->getPrice()) . '<br/>';
      $total += $product->getQty() * $product->getCatalogProduct()->getPrice();
    }

    $out.= '=$' . sprintf('%0.2f', $total) . '<br/>';
    $out.= '@' . sprintf('%0.2f', $row->getPriceMultiplier()*100) . '%<br/>';
    $out.= '=$' . sprintf('%0.2f', $total * $row->getPriceMultiplier()) . '<br/>';

    return $out;
  }
}
