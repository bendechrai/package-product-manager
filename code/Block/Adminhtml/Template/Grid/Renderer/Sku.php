<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Render SKUs as link to mapped product if it exists
   *   Product name (qty @ price)
   * with product name linked to the catalog product
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
  public function render(Varien_Object $row)
  {
    if(!is_null($row->getMappedProductId())) {
      return '<a href="'.$this->getUrl('*/catalog_product/edit', array('id'=>$row->getMappedProductId())).'"
        title="'.$row->getCatalogProduct()->getName().'">'.$row->getSku().'</a>';
    } else {
      return $row->getSku();
    }
  }
}
