<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Attributes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Render attributes as a table of the key/value pairs
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
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
