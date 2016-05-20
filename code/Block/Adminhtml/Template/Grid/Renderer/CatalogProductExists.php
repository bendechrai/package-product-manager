<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_CatalogProductExists extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

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
