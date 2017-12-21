<?php

class Application_Form_CoinForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/coin/coin/add')
                ->setMethod('post')
                ->setName("CoinForm");

        $CoinID = $this->createElement("hidden", "CoinID");
        $this->addElement($CoinID);

        $CoinName = $this->createElement("text", "CoinName");
        $CoinName->setLabel($this->translate->_("coin_name"));
        $CoinName->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 2, "max" => 100));
        $CoinName->addValidator($lengthValid);
        $CoinName->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($CoinName);

        $Description = $this->createElement("textarea", "Description");
        $Description->setLabel($this->translate->_("description"));
        $this->addElement($Description);
        
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
