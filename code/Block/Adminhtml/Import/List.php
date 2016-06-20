<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Block_Adminhtml_Import_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{

  public function __construct()
  {
    $this->_controller = 'adminhtml_import';
    $this->_blockGroup = 'bendechrai_packageproductmanager';
    $this->_headerText = Mage::helper('bendechrai_packageproductmanager')->__('Package Product Manager');
    parent::__construct();

    $this->_removeButton('add');
    $this->addButton('bendechrai_packageproductmanager_import', array(
      'label'     => 'Import',
      'onclick'   => 'setLocation(\' '  . Mage::helper("adminhtml")->getUrl('*/*/import') . '\')',
    ));
  }

  public function _toHtml() {
    $block = $this->getLayout()->createBlock(
      'Mage_Core_Block_Template',
      'importHelp',
      array('template' => 'BenDechrai/PackageProductManager/import/help.phtml')
    );
    return $block->toHtml() . parent::_toHtml();
  }
}

