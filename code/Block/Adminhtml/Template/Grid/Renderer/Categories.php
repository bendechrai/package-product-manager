<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Categories extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

  /**
   * Render categories as
   *  * Full > Path > To > Category
   *  * One > Per > Line
   *  * Sorted > Alphabetically
   *
   * @access public
   * @param Varien_Object $row
   * @return string
   */
  public function render(Varien_Object $row)
  {
    $categoryIds = preg_replace('#[^0-9,]#', '', $row->getCategoryIds());
    $categoryIds = preg_replace('#[,]+#', ',', $categoryIds);
    $categoryIds = trim($categoryIds, ',');
    $categoryIds = explode(',', $categoryIds);

    $out = '<ul>';
    foreach($categoryIds as $categoryId) {

      $category = Mage::GetModel('catalog/category')->load($categoryId);
      if($category->getId()) {

        $fullname = $category->getName();

        $parent = Mage::GetModel('catalog/category')->load($category->getParentId());
        while($parent->getId()) {
          $fullname = $parent->getName() . ' > ' . $fullname;
          $parent = Mage::GetModel('catalog/category')->load($parent->getParentId());
        }

        $out .= '<li>' . Mage::helper('core')->escapeHtml($fullname) . '</li>';

      }

    }
    $out .= '</ul>';

    return $out;
  }
}
