<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Product list
 *
 * @category   Mage
 * @package    Extensions_Collections
 * @author     Jawwad Nissar <jawwad.nissar@progos.org>
 */
class Extensions_Collections_Block_Women extends Mage_Core_Block_Template {

    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'catalog/product_list_toolbar';

    /**
     * Attribute Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_attributeCollection;

    /**
     * Trending Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;
    /**
     * Retrieve Attribute Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
	protected function _getAttributeCollection()	
    {
		if (is_null($this->_attributeCollection)) {
            $attribute = Mage::getModel('catalog/resource_eav_attribute')->load(157);
            $attributeOptions = $attribute ->getSource()->getAllOptions();
/*          echo "<pre>";
            print_r($_img);
            echo "</pre>";
*/          $this->_attributeCollection = $attributeOptions;

        }
        return $this->_attributeCollection;
      
    }


    /**
     * Retrieve Attrinute Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function getLoadedAttributeCollection()
    {
        return $this->_getAttributeCollection();
    }

    /**
     * Retrieve Trending Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function getLoadedProductCollection()
    {
        return $this->_getTrendingProductCollection();
    }
	
    /**
     * Retrieve Attribute Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
	protected function _getTrendingProductCollection()	
    {
		if (is_null($this->_productCollection)) {
			$productIds = Mage::getStoreConfig('sellers_options/trending'); 
			$clothingProductIdsArray = explode(',',$productIds['clothing']);
			$catId = '3';
			$collection = Mage::getModel('catalog/product')
							->getCollection()
							->addAttributeToFilter('entity_id', array('in' => $clothingProductIdsArray))
						->addAttributeToFilter('gender', 25)			
							->joinField(
									'category_id', 'catalog/category_product', 'category_id', 
									'product_id = entity_id', null, 'left'
								)
								->addAttributeToSelect('*')
								->addAttributeToFilter('category_id', array($catId));
			$this->_productCollection = $collection;
		}
        return $this->_productCollection;      
    }

}