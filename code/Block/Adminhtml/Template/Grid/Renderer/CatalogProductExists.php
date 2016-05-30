<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_CatalogProductExists extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Does this package clash with an existing product? If yes, include a link to that product.
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
  public function render(Varien_Object $row)
  {
    if($row->getCatalogProductExists()) {
      $product_id = Mage::GetModel('catalog/product')->getIdBySku($row->getSku());
      return 'Yes (<a href="'.$this->getUrl('*/catalog_product/edit', array('id'=>$product_id)).'">'.$product_id.'</a>)';
    } else {
      return 'No';
    }
  }
}
