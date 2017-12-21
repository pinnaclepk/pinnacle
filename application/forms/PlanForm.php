<?php

class Application_Form_PlanForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/admin/plan/add')
                ->setMethod('post')
                ->setName("PlanForm");

        $PlanID = $this->createElement("hidden", "PlanID");
        $this->addElement($PlanID);
        
        $coinObj = new Application_Model_Common();
        $CoinID = '';
        $coinData = $coinObj->getCoin($CoinID);        
        $coinData = array("" => $this->translate->_("select")) + $coinData;
        $CoinID = $this->createElement("select", "CoinID");
        $CoinID->setLabel($this->translate->_("coin_name"));        
        $CoinID->setMultiOptions($coinData);
        $CoinID->setRequired(true);
        $this->addElement($CoinID);

        $PlanName = $this->createElement("text", "PlanName");
        $PlanName->setLabel($this->translate->_("plan_name"));
        $PlanName->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 2, "max" => 100));
        $PlanName->addValidator($lengthValid);
        $PlanName->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($PlanName);

        $Description = $this->createElement("textarea", "Description");
        $Description->setLabel($this->translate->_("description"));
        $this->addElement($Description);
        
        $Price = $this->createElement("text", "Price");
        $Price->setLabel($this->translate->_("price"));
        $this->addElement($Price);
        
        $HashPower = $this->createElement("text", "HashPower");
        $HashPower->setLabel($this->translate->_("hashpower"));
        $this->addElement($HashPower);
        
        $IsActive = $this->createElement("select", "IsActive");
        $IsActive->setLabel($this->translate->_("status"));
        $IsActive->setMultiOptions(getStatusValues());
        $this->addElement($IsActive);

        $btnSave = $this->createElement("submit", "btnSave");
        $btnSave->setLabel($this->translate->_("save"));
        $btnSave->setAttrib("class", $this->getButtonSaveClass());
        $this->addElement($btnSave);
    }

}

?>
