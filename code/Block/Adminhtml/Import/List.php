<?php

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
}
