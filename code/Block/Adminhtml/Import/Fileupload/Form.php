<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Import_Fileupload_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/import', array('_current' => true)),
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _prepareForm()
    {
        $fieldset = $this->getForm()
            ->addFieldset('bendechrai_packageproductmanager_import_form_fs1', array(
                'legend' => Mage::helper('bendechrai_packageproductmanager')->__('CSV File')
            ));

        $fieldset->addField('csvfile', 'file', array(
            'label'    => 'CSV File',
            'name'     => 'csvfile',
            'class'    => 'file',
            'required' => true,
        ));

        $fieldset->addField('note', 'note', array(
            'text'     => Mage::helper('bendechrai_packageproductmanager')->__(
                '<strong>File upload instructions</strong><br/>' .
                'Upload a comma separated file (CSV) with the following columns: <strong>sku, associated products</strong></br>' .
                'Associated Products are in the format: <strong>qty@sku, qty@sku, ...</strong> For example: <strong>1@filter1, 2@hose7, 6@sparkplugs</strong><br/>' .
                'Optionally also include <strong>price multiplier</strong>, a decimal number representing the multiplier of the package cost to charge. For example, if the package consists of 2 products at $50 each, and one at $75, and a price multiplier of 1.2, the package cost will be ((2*50) + (1*75)) * 0.8, or $140.<br/>' .
                'Finally, any remaining columns will be used if the column heading matches a product attributes code, such as <strong>name</strong>, <strong>description</strong>, <strong>short_description</strong>.<br/>' .
                'Here is sample CSV:<br/>' .
                '<pre>sku,associated products,price multiplier,name,description,short_description' . "\n" . 
                'dining_furniture,1@table,6@chair,0.8,"Dining Furniture","Buy a table and 6 chairs and get a 20% discount!","Table and six chairs"</pre>'
            ),
        ));

        return parent::_prepareForm();
    }
}
