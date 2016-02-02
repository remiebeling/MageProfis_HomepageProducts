<?php 
class MageProfis_HomepageProducts_Block_Homepage extends Mage_Catalog_Block_Product_Abstract
{   protected $_products 	= null;
    protected $_category 	= null;
    protected $_category_id = null;
    protected $_item_limit = null;
    
    public function _construct()
    {
        $this->setCategoryId(Mage::getStoreConfig('homepageproducts/general/category'));
        $this->setItemLimit(Mage::getStoreConfig('homepageproducts/general/items'));
        
        parent::_construct();
        
        $this->addData(array('cache_lifetime' => 43200)); // 12 hours
        $this->addCacheTag(array(
            Mage_Catalog_Model_Product::CACHE_TAG,
        ));
    }
	
    public function getCacheKeyInfo()
    {        
        return array(
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            'MageProfis_HomepageProducts',
            $this->getCategoryId()
        );
    }
 
    protected function setCategoryId($id)
    {
        $this->_category_id = $id;
        return $this;      
    }
    
    public function getCategoryId()
    {
        if(is_null($this->_category_id))
        {
            return Mage::getStoreConfig('homepageproducts/general/category');
        }
        return $this->_category_id;
    }
    
    protected function setItemLimit($limit)
    {
        if($limit == "")
        {
            $limit = 12;    
        }    
        $this->_item_limit = $limit;
        return $this;
    }
    
    protected function hasCategoryId()
    {
        if(is_null($this->_category_id))
        {
          return false;    
        }
        return true;
    }
    
    protected function getCategory()
    {
        if(is_null($this->_category))
        {
            $this->_category = Mage::getModel('catalog/category')->load($this->_category_id);	
        }
        return $this->_category;
    }

    /*
     * get collection of all products that shall be shown on homepage
     */
    public function getProducts()
    {
        if(is_null($this->_products))
        {	
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('status', 1)
                    ->addAttributeToFilter('visibility', 4)
                    ->addCategoryFilter($this->getCategory())
                    ->addAttributeToSelect('*')
                    ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                    ->setPageSize($this->_item_limit)
                    ->addStoreFilter()
                    ->addTaxPercents()
                    ->addUrlRewrite(0);

            foreach($collection as $item)
            {
                $this->addCacheTag(Mage_Catalog_Model_Product::CACHE_TAG . '_' . $item->getId());
            }    

            $this->_products = $collection;
        }
        return $this->_products;		
    }
    
    protected function _toHtml()
    {
        if(!Mage::getStoreConfig('homepageproducts/general/active'))
        {
            return '';
        }
        return parent::_toHtml();
    }
    
  
}