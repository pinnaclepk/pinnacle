<?php

class Application_Form_UserForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/user/user/add')
                ->setMethod('post')
                ->setName("UserForm");

        $UserID = $this->createElement("hidden", "UserID");
        $this->addElement($UserID);
        
        $commonObj = new Application_Model_Common();
        $UserTypeID = '';
        $fields = "usertype.UserTypeID, usertype.TypeName";
        $usertypeData = $commonObj->getUserType($UserTypeID, $fields);
        $usertypeData = array("" => $this->translate->_("select")) + $usertypeData;
        $UserTypeID = $this->createElement("select", "UserTypeID");
        $UserTypeID->setLabel($this->translate->_("usertype"));        
        $UserTypeID->setRequired(true);
        $UserTypeID->setAttribs(array('onChange' => 'javascript:getSponsors();'));
        $UserTypeID->setMultiOptions($usertypeData);
        $this->addElement($UserTypeID);
        
        $SponsorID = $this->createElement("select", "SponsorID");
        $SponsorID->setLabel($this->translate->_("sponsor"));
        $SponsorID->setRegisterInArrayValidator(false);
        $this->addElement($SponsorID);

        $IsActive = $this->createElement("select", "IsActive");
        $IsActive->setLabel($this->translate->_("status"));
        $IsActive->setMultiOptions(getStatusValues());
        $this->addElement($IsActive);
        
        $UserName = $this->createElement("text", "Username");
        $UserName->setLabel($this->translate->_("login_id"));
        $UserName->setAttrib("autocomplete", 'off');
        $UserName->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($UserName, array("validationName" => "LoginID", "options" => array("required" => true, "regex" => true, "stringlength" => true)));

        $FName = $this->createElement("text", "FName");
        $FName->setLabel($this->translate->_("first_name"));
        $this->addElement($FName, array("validationName" => "Name", "options" => array("required" => true, "regex" => true, "stringlength" => true)));

        $LName = $this->createElement("text", "LName");
        $LName->setLabel($this->translate->_("last_name"));
        $this->addElement($LName, array("validationName" => "Name", "options" => array("required" => true, "regex" => true, "stringlength" => true)));
        
        $Password = $this->createElement("password", "Password");
        $Password->setLabel($this->translate->_("password"));
        $Password->setRequired(true);
        $Password->setAttrib("autocomplete", 'off');
        $Password->addValidator('Identical', false, array('token' => 'ConfirmPassword'));
        //$Password->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("SamePassword"))), true);
        $this->addElement($Password);

        $ConfirmPassword = $this->createElement("password", "ConfirmPassword");
        $ConfirmPassword->setLabel($this->translate->_("confirm_password"));
        $ConfirmPassword->setRequired(true);
        $ConfirmPassword->setAttrib("autocomplete", 'off');
        $ConfirmPassword->addValidator('Identical', false, array('token' => 'Password'));
        $this->addElement($ConfirmPassword);
        
        $JoiningDate = $this->createElement("text", "JoiningDate");
        $JoiningDate->setLabel($this->translate->_("joining_date"));
        $JoiningDate->setRequired(true);
        $JoiningDate->setValue(date("d/m/Y"));
        $this->addElement($JoiningDate);
        
        $Email = $this->createElement("text", "Email");
        $Email->setLabel($this->translate->_("email"));
        $Email->setRequired(true);
        $emailValid = new Zend_Validate_EmailAddress();
        $emailValid->setMessage($this->translate->_("error_please_enter_valid_email"));
        $Email->addValidator($emailValid);
        //$Email->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Unique"))), true);
        $this->addElement($Email);
        
        $Phone = $this->createElement("text", "Phone");
        $Phone->setLabel($this->translate->_("phone"));
        $Phone->setRequired(true);
        $this->addElement($Phone);        
        
        $stateObj = new Application_Model_Common();
        $StateID = '';
        $fields = "state.StateID, state.StateName";
        $stateData = $stateObj->getState($StateID, $fields);        
        $stateData = array("" => $this->translate->_("select")) + $stateData;
        $StateID = $this->createElement("select", "StateID");
        $StateID->setLabel($this->translate->_("state"));              
        $StateID->setMultiOptions($stateData);
        $this->addElement($StateID);
        
        $MobileNo = $this->createElement("text", "MobileNo");
        $MobileNo->setLabel($this->translate->_("mobile_number"));
        $MobileNo->setRequired(true);
        $this->addElement($MobileNo);

        $DOB = $this->createElement("text", "DOB");
        $DOB->setLabel($this->translate->_("dob"));
        $DOB->setRequired(true);
        $this->addElement($DOB);

        $Address = $this->createElement("text", "Address");
        $Address->setLabel($this->translate->_("address"));
        $Address->setRequired(true);
        $this->addElement($Address);
        
        $ZipCode = $this->createElement("text", "ZipCode");
        $ZipCode->setLabel($this->translate->_("zip_code"));
        $lengthValid = new Zend_Validate_StringLength(array('min' => 1, "max" => 4));
        $digitValid  = new Zend_Validate_Digits();
        $ZipCode->addValidators(array($digitValid, $lengthValid));
        $ZipCode->setAttribs(array('maxlength' => '4'));
        $ZipCode->setRequired(true);
        $this->addElement($ZipCode);        

        $btnSave = $this->createElement('submit', 'btnSave');
        $btnSave->setLabel($this->translate->_("save"));
        $btnSave->setAttrib("class", $this->getButtonSaveClass());
        $this->addElement($btnSave);
    }

}

?>
