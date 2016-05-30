<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

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
