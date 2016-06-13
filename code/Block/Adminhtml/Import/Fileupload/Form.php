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
                '<h3>File upload instructions</h3>' .
                '<p>Upload a comma separated file (CSV) with the following columns: <strong>sku, associated products, categories</strong></p>' .
                '<p><img src="'. $this->getSkinUrl('images/bendechrai/package-product-manager/spreadsheet_minimal.png') . '"/></p>' .
                '<p>Associated Products are in the format: <strong>qty@sku, qty@sku, ...</strong></p>' .
                '<p>Category IDs are in the format: <strong>id, id, id, ...</strong>, where the ID matches the Category ID in the <em>Manage Categories</em> page.</p>' .
                '<p><img src="'. $this->getSkinUrl('images/bendechrai/package-product-manager/category_id.png') . '"/></p>' .
                '<p>Optionally also include <strong>price multiplier</strong>, a decimal number representing the multiplier of the package cost to charge. For example, if the package consists of one table at $200, and six chairs at $40 each, with a price multiplier of 0.8, the package cost will be ((1*200) + (6*40)) * 0.8, or $352. If not specified, it defaults to <strong>1</strong></p>' .
                '<p>Finally, any remaining columns will be used if the column heading matches a product attributes code, such as <strong>name</strong>, <strong>description</strong>, <strong>short_description</strong>.</p>' .
                '<p><img src="'. $this->getSkinUrl('images/bendechrai/package-product-manager/spreadsheet_optional.png') . '"/></p>' .
                '<h3>Overwrite</h3>' .
                '<p>By default, a row in the CSV Import file will be ignored if the SKU exists in the Package Product definition already. If you tick this box, the Package Product definition will be deleted first. The new definition will be added unapproved and unlisted.</p>'
            ),
        ));

        return parent::_prepareForm();
    }
}
