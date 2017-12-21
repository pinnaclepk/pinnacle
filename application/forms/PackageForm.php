<?php

class Application_Form_PackageForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/Package/Package/add')
                ->setMethod('post')
                ->setName("PackageForm");

        $PackageID = $this->createElement("hidden", "PackageID");
        $this->addElement($PackageID);
        
        $PackageName = $this->createElement("text", "PackageName");
        $PackageName->setLabel($this->translate->_("package_name"));
        $PackageName->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 2, "max" => 100));
        $PackageName->addValidator($lengthValid);
        $PackageName->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($PackageName);

        $Description = $this->createElement("textarea", "Description");
        $Description->setLabel($this->translate->_("description"));
        $this->addElement($Description);
        
        $Price = $this->createElement("text", "Price");
        $Price->setLabel($this->translate->_("price"));
        $this->addElement($Price);
        
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
