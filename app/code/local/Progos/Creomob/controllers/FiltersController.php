<?php


class Progos_Creomob_FiltersController extends Mage_Core_Controller_Front_Action{
    
    
    
    
    public function categoryFilterTree() {
        $categories = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addIsActiveFilter()
//                        ->addLevelFilter(3)
                        ->addAttributeToFilter('level', array('gt' => 1))
                        ->addAttributeToFilter('is_active', '1')
                        ->addAttributeToFilter('include_in_menu', '1')
                        ->addAttributeToSort('path', 'asc')
                        ->load()->toArray();
        return $categories;
    }
    
    public function categoryFilterTreeAction() {
        $categories = $this->categoryFilterTree();
        $data = array();
        foreach ($categories as $category) {
            $data[] = $category;
        }
        header("Content-Type: application/json");
        print_r(json_encode($data));
        die;
    }
    
    public function getAttribute($attributeCode) {
        $attributeId = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setCodeFilter($attributeCode)->getFirstItem()->getAttributeId();
        
        $attributeOptions = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setAttributeFilter($attributeId)
                ->setStoreFilter(0)
                ->setPositionOrder()
                ->load()
                ->toOptionArray();
        
        return $attributeOptions;

        $attrs = array();
        foreach ($attributeOptions AS $attributeOption) {
            $attrs[] = array('code'=>$attributeOption['value'],'label'=>$attributeOption['label']);
        }
        
        header("Content-Type: application/json");
        print_r(json_encode($attrs));
        die;
    }

    public function colorFilterAction() {
        $attributeCode = "color";
        $attributeOptions = $this->getAttribute($attributeCode);
        $attrs = array();
        foreach ($attributeOptions AS $attributeOption) {
            $attrs[] = array('code'=>$attributeOption['value'],'label'=>$attributeOption['label']);
        }
        
        header("Content-Type: application/json");
        print_r(json_encode($attrs));
        die;
    }
    
    public function sizeFilterAction() {
        $attributeCode = "size";
        $attributeOptions = $this->getAttribute($attributeCode);
        $attrs = array();
        foreach ($attributeOptions AS $attributeOption) {
            $attrs[] = array('code'=>$attributeOption['value'],'label'=>$attributeOption['label']);
        }
        
        header("Content-Type: application/json");
        print_r(json_encode($attrs));
        die;
    }
    
    public function filtersAction(){
        $categories = $this->categoryFilterTree();
        $data = array();
        foreach ($categories as $category) {
            $data['categories'][] = $category;
        }
        $attrs = array();
        
        $attributeCode = "color";
        $attributeOptions = $this->getAttribute($attributeCode);
//        $attribute = Mage::getModel('catalog/resource_eav_attribute')
//            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
//        $attributeOptions = $attribute ->getSource()->getAllOptions(false); 
        foreach ($attributeOptions AS $attributeOption) {
            $attrs['color'][] = $attributeOption;
        }
        
        $attributeCode = "size";
//        $attributeOptions = $this->getAttribute($attributeCode);
        $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
        $attributeOptions = $attribute ->getSource()->getAllOptions(false); 
        foreach ($attributeOptions AS $attributeOption) {
            $attrs['size'][] = $attributeOption;
        }
        
        $attributeCode = "gender";
        $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
        $attributeOptions = $attribute ->getSource()->getAllOptions(false); 
        foreach ($attributeOptions AS $attributeOption) {
            $attrs['gender'][] = $attributeOption;
        }
        
        $attributeCode = "styles";
//        $attributeOptions = $this->getAttribute($attributeCode);
        $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
        $attributeOptions = $attribute ->getSource()->getAllOptions(false); 
        foreach ($attributeOptions AS $attributeOption) {
            //$attrs['gender'][] = array('code'=>$attributeOption['value'],'label'=>$attributeOption['label']);
            $attrs['styles'][] = $attributeOption;
        }
            
        $data['attrs'] = $attrs;
        
        header("Content-Type: application/json");
        print_r(json_encode($data));
        die;
        
    }
    
    public function filtersv2Action($categoryId){
        
        $categoryId = (int)$this->getRequest()->getParam('cid');
        if($categoryId){
            //$attributes = $this->getAttributesFromCategoryId($categoryId);
            $helper = Mage::helper('creomob/data');
            $attributes = $helper->getAttributesFromCategoryId($categoryId);
            
            $attrs = array();
            
            foreach ($attributes as $attribute_code){
                
                
                $attribute = Mage::getModel('catalog/resource_eav_attribute')
                    ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attribute_code);
                $attributeOptions = $attribute ->getSource()->getAllOptions(false); 
                $attributeLabel = $attribute->getStoreLabel();
                foreach ($attributeOptions AS $attributeOption) {
                    //$attrs[$attribute_code][] = $attributeOption;
                    $attrs[$attribute_code]['label'] = $attributeLabel;
                    $attrs[$attribute_code]['options'][] = $attributeOption;
                }
            }
            header("Content-Type: application/json");
            print_r(json_encode($attrs));
            die;
            
        }
    }
    
}