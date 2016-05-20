<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if(is_null($row->imageurl)) {
          return "No image available";
        } else {
          return "<img src=". $row->imageurl ." width='97px'/>";
        }
    }
}
