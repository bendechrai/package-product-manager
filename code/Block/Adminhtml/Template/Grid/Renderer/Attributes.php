<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Attributes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  public function render(Varien_Object $row)
  {
    $attributes = json_decode($row->getAttributes(),1);

    $out = '<table>';

    foreach($attributes as $key=>$value) {
      $key = Mage::helper('bendechrai_packageproductmanager')->__($key);
      $value = Mage::helper('bendechrai_packageproductmanager')->__($value);
      $out.= "<tr><th>$key</th><td>$value</td></tr>";
    }

    $out.= '</table>';
    return $out;
  }
}
