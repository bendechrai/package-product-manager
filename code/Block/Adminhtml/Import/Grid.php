<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

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
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('SKU'),
          'align'     => 'left',
          'index'     => 'sku',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Sku',
      ));

//      $this->addColumn('image', array(
//          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Image'),
//          'align'     => 'center',
//          'index'     => 'image',
//          'width'     => '97',
//          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Image',
//          'filter'    => false,
//      ));

      $this->addColumn('attributes', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Attributes'),
          'align'     => 'left',
          'index'     => 'attributes',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Attributes',
      ));

      $this->addColumn('associated_products', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Associated Products'),
          'align'     => 'left',
          'index'     => 'associated_products',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_AssociatedProducts',
      ));

      $this->addColumn('categories', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Categories'),
          'align'     => 'left',
          'index'     => 'category_ids',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Categories',
      ));

      $this->addColumn('price', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Price'),
          'align'     => 'right',
          'index'     => 'price_multiplier',
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_Price',
      ));

      $this->addColumn('catalog_product_exists', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Catalog Product Exists?'),
          'align'     => 'center',
          'index'     => 'catalog_product_exists',
          'type'      => 'options',
          'options'   => array(
            '0' => 'No',
            '1' => 'Yes',
          ),
          'renderer'  => 'BenDechrai_PackageProductManager_Block_Adminhtml_Template_Grid_Renderer_CatalogProductExists',
      ));

      $this->addColumn('replace_existing', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Replace Existing?'),
          'align'     => 'center',
          'index'     => 'replace_existing',
          'type'      => 'options',
          'options'   => array(
            '0' => 'No',
            '1' => 'Yes',
          ),
      ));

      $this->addColumn('approved', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Approved'),
          'align'     => 'center',
          'index'     => 'approved',
          'type'      => 'options',
          'options'   => array(
            '0' => 'Pending',
            '1' => 'Approved',
          ),
      ));

      $this->addColumn('listed', array(
          'header'    => Mage::helper('bendechrai_packageproductmanager')->__('Listed'),
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

    $this->getMassactionBlock()->addItem('Relist', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Relist'),
      'url'      => $this->getUrl('*/*/massRelistPackages'),
      'confirm'  => Mage::helper('bendechrai_packageproductmanager')->__('This will mark these packages as unlisted, so they are updated during the next scheduled task. Are you sure?')
    ));

    $this->getMassactionBlock()->addItem('Approve', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Approve'),
      'url'      => $this->getUrl('*/*/massApprovePackages'),
      'confirm'  => Mage::helper('bendechrai_packageproductmanager')->__('This will mark these packages as approved, and could result in them being added to the store catalog. Are you sure?')
    ));

    $this->getMassactionBlock()->addItem('Unapprove', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Unapprove'),
      'url'      => $this->getUrl('*/*/massUnapprovePackages'),
    ));

    $this->getMassactionBlock()->addItem('Replace Existing', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Replace Existing'),
      'url'      => $this->getUrl('*/*/massReplaceExistingPackages'),
      'confirm'  => Mage::helper('bendechrai_packageproductmanager')->__('The packages you\'ve selected will be able to replace existing products in the catalog, even if they are not the same actual item. Are you sure?')
    ));

    $this->getMassactionBlock()->addItem('Don\'t Replace Existing', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Don\'t Replace Existing'),
      'url'      => $this->getUrl('*/*/massDontReplaceExistingPackages'),
    ));

    $this->getMassactionBlock()->addItem('Use Existing', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Use Existing'),
      'url'      => $this->getUrl('*/*/massUseExistingPackages'),
      'confirm'  => Mage::helper('bendechrai_packageproductmanager')->__('If packages you\'ve selected have SKUs used by existing package products, this action maps these together. Any revisions will then happen to these existing package products. Are you sure?')
    ));

    $this->getMassactionBlock()->addItem('Refresh', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Refresh'),
      'url'      => $this->getUrl('*/*/massRefreshPackages'),
    ));

    $this->getMassactionBlock()->addItem('Delete', array(
      'label'    => Mage::helper('bendechrai_packageproductmanager')->__('Delete'),
      'url'      => $this->getUrl('*/*/massDeletePackages'),
      'confirm'  => Mage::helper('bendechrai_packageproductmanager')->__('You are deleting package products from the maganer. This will *NOT* delete products from the catalog. Continue?')
    ));

    return $this;
  }

  public function getRowUrl($row)
  {
    // Row click toggles mass action checkbox
    return "javascript:$$('input[name=package_id][type=checkbox][value={$row->getId()}]')[0].click();";
  }

}
