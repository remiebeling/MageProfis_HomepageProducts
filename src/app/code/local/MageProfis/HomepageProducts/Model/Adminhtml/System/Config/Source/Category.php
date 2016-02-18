<?php
class MageProfis_HomepageProducts_Model_Adminhtml_System_Config_Source_Category
{
    public function toOptionArray()
    {
        $out = array();
        
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('name')
            ->addIsActiveFilter();

        foreach ($categories as $id=>$attributeSet) {
            $out[] = array('value' => $id, 'label' => $attributeSet->getName());
        }

        return $out;
    }
}