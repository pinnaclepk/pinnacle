<?php

class Application_Form_StateForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/state/state/add')
                ->setMethod('post')
                ->setName("StateForm");

        $stateID = $this->createElement("hidden", "StateID");
        $this->addElement($stateID);

        $stateName = $this->createElement("text", "Name");
        $stateName->setLabel($this->translate->_("state"));
        $stateName->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 2, "max" => 100));
        $stateName->addValidator($lengthValid);
        $stateName->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($stateName);

        $StateCode = $this->createElement("text", "Code");
        $StateCode->setLabel($this->translate->_("state_code"));
        $StateCode->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 1, "max" => 2));
        $StateCode->addValidator($lengthValid);
        $StateCode->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($StateCode);

        $btnSave = $this->createElement("submit", "btnSave");
        $btnSave->setLabel($this->translate->_("save"));
        $btnSave->setAttrib("class", $this->getButtonSaveClass());
        $this->addElement($btnSave);
    }

}

?>
