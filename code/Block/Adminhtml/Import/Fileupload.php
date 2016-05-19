<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Import_Fileupload extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId   = 'id';
        $this->_blockGroup = 'bendechrai_packageproductmanager';
        $this->_controller = 'adminhtml_import';
        $this->_mode       = 'fileupload';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bendechrai_packageproductmanager')->__('Import')
        );
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('bendechrai_packageproductmanager')->__('Import Package Products');
    }
}
