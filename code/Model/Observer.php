<?php
/**
 * BenDechrai_PackageProductManager extension
 * @category   BenDechrai
 * @package    BenDechrai_PackageProductManager
 * @copyright  Ben Dechrai
 * @author     Ben Dechrai <ben@dechrai.com> https://bendechrai.com.
 */

class BenDechrai_PackageProductManager_Model_Observer
{

    public function advancedstockProductAftersave($observer) {
        Mage::log("advancedstockProductAftersave", null, 'packageproductmanager.log');
        $product = $observer->getProduct();
        $this->_refreshProductAvailability($product->getId());
    }

    /**
     * When quote items cause a product qty change, make sure any
     * related package product qtys are updated too.
     *
     * @access public
     * @param Varien_Event_Observer $observer
     */
    public function salesQuoteItemQtySetAfter($observer) {
        Mage::log("salesQuoteItemQtySetAfter", null, 'packageproductmanager.log');
        $orderItem = $observer->getEvent()->getorder_item();
        $productId = $orderItem->getProductId();
        $this->_refreshProductAvailability($productId);
    }

    private function _refreshProductAvailability($productId) {
        Mage::log("_refreshProductAvailability", null, 'packageproductmanager.log');
        Mage::log("Product ID $productId", null, 'packageproductmanager.log');

        // Build collection of product links, that link to this product, and are Package Product links
        $packageIds = Mage::GetModel('catalog/product_link')
                            ->getCollection()
                            ->distinct(true)
                            ->addFieldToSelect('product_id')
                            ->addFieldToFilter('linked_product_id', $productId)
                            ->load()
                            ->getColumnValues('product_id');

        // For all packages
        foreach($packageIds as $packageId) {
            Mage::log("Refresh qtys for package $packageId", null, 'packageproductmanager.log');
            Mage::Helper('SalesOrderPlanning/ProductAvailabilityStatus')->RefreshForOneProduct($packageId);
        }
    }

}
