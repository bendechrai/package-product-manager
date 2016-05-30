<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

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

        $fieldset->addField('overwrite', 'checkbox', array(
            'label'    => 'Overwrite',
            'name'     => 'overwrite',
            'class'    => 'checkbox',
            'value'    => 'overwrite',
            'after_element_html' => '&nbsp;<small>(if SKUs in the CSV file match SKUs in the Package Product Maganer, should they overwrite?)</small>',
        ));

        $fieldset = $this->getForm()
            ->addFieldset('bendechrai_packageproductmanager_import_form_fs2', array(
                'legend' => Mage::helper('bendechrai_packageproductmanager')->__('Instructions'),
            ));

        $fieldset->addField('note', 'note', array(
            'text'     => Mage::helper('bendechrai_packageproductmanager')->__(
                '<h4>File upload instructions</h4>' .
                '<p>Upload a comma separated file (CSV) with the following columns: <strong>sku, associated products</strong></p>' .
                '<p>Associated Products are in the format: <strong>qty@sku, qty@sku, ...</strong> For example: <strong>1@filter1, 2@hose7, 6@sparkplugs</strong></p>' .
                '<p>Optionally also include <strong>price multiplier</strong>, a decimal number representing the multiplier of the package cost to charge. For example, if the package consists of 2 products at $50 each, and one at $75, and a price multiplier of 1.2, the package cost will be ((2*50) + (1*75)) * 0.8, or $140.</p>' .
                '<p>Finally, any remaining columns will be used if the column heading matches a product attributes code, such as <strong>name</strong>, <strong>description</strong>, <strong>short_description</strong>.</p>' .
                '<p>Here is sample CSV:</p>' .
                '<pre>sku,associated products,price multiplier,name,description,short_description' . "\n" . 
                'dining_furniture,1@table,6@chair,0.8,"Dining Furniture","Buy a table and 6 chairs and get a 20% discount!","Table and six chairs"</pre>' . 
                '<h4>Overwrite</h4>' .
                '<p>By default, a row in the CSV Import file will be ignored if the SKU exists in the Package Product definition already. If you tick this box, the Package Product definition will be deleted first. The new definition will be added unapproved and unlisted.</p>'
            ),
        ));

        return parent::_prepareForm();
    }
}
