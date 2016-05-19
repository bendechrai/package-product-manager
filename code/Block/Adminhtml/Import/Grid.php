<?php

class BenDechrai_PackageProductManager_Block_Adminhtml_Import_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  public function __construct()
  {
    parent::__construct();
    $this->setId('id');
    $this->setDefaultSort('package_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('bendechrai_packageproductmanager/package')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('sku', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('SKU'),
          'align'     => 'left',
          'index'     => 'sku',
      ));

      $this->addColumn('associated_products', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('Associated Products'),
          'align'     => 'left',
          'index'     => 'associated_products',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_AssociatedProducts',
      ));

      $this->addColumn('catalog_product_exists', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('Catalog Product Exists?'),
          'align'     => 'center',
          'index'     => 'catalog_product_exists',
          'type'      => 'options',
          'options'   => array(
            '0' => 'No',
            '1' => 'Yes',
          ),
      ));

      $this->addColumn('replace_existing', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('Replace Existing?'),
          'align'     => 'center',
          'index'     => 'replace_existing',
          'type'      => 'options',
          'options'   => array(
            '0' => 'No',
            '1' => 'Yes',
          ),
      ));

      $this->addColumn('approved', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('Approved'),
          'align'     => 'center',
          'index'     => 'approved',
          'type'      => 'options',
          'options'   => array(
            '0' => 'Pending',
            '1' => 'Approved',
          ),
      ));

      $this->addColumn('listed', array(
          'header'    => Mage::helper('runautoparts_partsdb')->__('Listed'),
          'align'     => 'center',
          'index'     => 'listed',
          'type'      => 'options',
          'options'   => array(
            '0' => 'Unlisted',
            '1' => 'Listed',
          ),
      ));

      return parent::_prepareColumns();
  }

  protected function _prepareMassaction()
  {
    parent::_prepareMassaction();

    $this->setMassactionIdField('package_id');
    $this->getMassactionBlock()->setFormFieldName('package_id');

    $this->getMassactionBlock()->addItem('Approve', array(
      'label'    => Mage::helper('runautoparts_partsdb')->__('Approve'),
      'url'      => $this->getUrl('*/*/massApprovePackages'),
      'confirm'  => Mage::helper('runautoparts_partsdb')->__('This will mark these packages as approved, and could result in them being added to the store catalog. Are you sure?')
    ));

    $this->getMassactionBlock()->addItem('Unapprove', array(
      'label'    => Mage::helper('runautoparts_partsdb')->__('Unapprove'),
      'url'      => $this->getUrl('*/*/massUnapprovePackages'),
      'confirm'  => Mage::helper('runautoparts_partsdb')->__('This will unapprove these packages. It will not remove any packages already added to the catalog. Are you sure?')
    ));

    return $this;
  }

}
