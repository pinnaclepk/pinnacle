<?php

class Application_Form_ChangePasswordForm extends Application_Form_Base {

    public function init() {
      
        $this->setAction('/profile/profile/changepassword')
                ->setMethod('post')
                ->setName("profile");
        $CurrentPassword = $this->createElement("password", "CurrentPassword");
        $CurrentPassword->setLabel($this->translate->_("current_password"));
        $CurrentPassword->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 3, "max" => 10));
        $CurrentPassword->setValidators(array($lengthValid));
        $CurrentPassword->setAttrib("maxlength", 10);
        //$CurrentPassword->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("CurrentPassword"))), true);
        $this->addElement($CurrentPassword);

        $NewPassword = $this->createElement("password", "Password");
        $NewPassword->setLabel($this->translate->_("new_password"));
        $NewPassword->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 3, "max" => 10));
        $NewPassword->setValidators(array($lengthValid));
        $NewPassword->setAttrib("maxlength", 10);
        $NewPassword->addValidator('Identical', false, array('token' => 'ConfirmPassword'));
        $this->addElement($NewPassword);

        $ConfirmPassword = $this->createElement("password", "ConfirmPassword");
        $ConfirmPassword->setLabel($this->translate->_("confirm_new_password"));
        $ConfirmPassword->setRequired(true);
        $lengthValid = new Zend_Validate_StringLength(array('min' => 3, "max" => 10));
        $ConfirmPassword->setValidators(array($lengthValid));
        $ConfirmPassword->setAttrib("maxlength", 10);
        $ConfirmPassword->addValidator('Identical', false, array('token' => 'Password'));

        $btnSave = $this->createElement('submit', 'btnSave');
        $btnSave->setLabel($this->translate->_("save"));
        $btnSave->setAttrib("class", $this->getButtonSaveClass());
        $this->addElement($btnSave);
        $this->addElement($ConfirmPassword);
    }

    public function isValid($formData) {
        $valid = parent::isValid($formData);
        if ($formData["Password"] != $formData["ConfirmPassword"]) {
            $valid = false;
        }
        return $valid;
    }

}

?>
