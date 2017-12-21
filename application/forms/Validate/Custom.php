<?php

class Application_Form_Validate_Custom extends Zend_Validate_Abstract {

    public $_session;

    const ISEMPTY = 'isEmpty';
    const EXISTS = 'exists';
    const SAME_PASSWORD = "samepassword";
    const PASSWORDNOTMATCH = 'NotMatchCurrentPassword';

    protected $_messageTemplates = array(
        self::ISEMPTY => "Required",
        self::EXISTS => "Already Exists",
    );
    private $_form;
    private $_options;

    public function __construct($form, $options = array()) {
        $this->_form = $form;
        $this->_options = $options;
        $this->_session = Zend_Registry::get("session");
    }

    public function isValid($value, $context = null) {
        $this->_setValue($value);
        $isValid = true;
        if (count($this->_options["validation"])) {
            //if (is_array($this->_options["validation"]) && !empty($this->_options["validation"])) {
                foreach ($this->_options["validation"] as $validation) {
                    switch ($validation) {
                        case "Unique":
                            $formName = $this->_form->getName();
                            switch ($formName) {
                                case "UserForm" :
                                    $userObj = new Admin_Model_User();
                                    if (empty($context['UserID'])) {
                                        $isUserExists = $userObj->userExist($value);
                                        if ($isUserExists > 0) {
                                            $this->_error(self::EXISTS);
                                            $isValid = false;
                                            break;
                                        }
                                    }
                                    break;                                    
                                
                                case "CoinForm" :
                                    //if (empty($context['ReasoncategoryID'])) {
                                        $coinObj = new Admin_Model_Coin();
                                        $isCoinExists = $coinObj->coinExist($context);
                                        if ($isCoinExists > 0) {
                                            $this->_error(self::EXISTS);
                                            $isValid = false;
                                        }
                                    //}
                                    break;   
                                    case "PlanForm" :
                                    //if (empty($context['ReasoncategoryID'])) {
                                        $planObj = new Admin_Model_Plan();
                                        $isPlanExists = $planObj->planExist($context);
                                        if ($isPlanExists > 0) {
                                            $this->_error(self::EXISTS);
                                            $isValid = false;
                                        }
                                    //}
                                    break;
                                    case "PackageForm" :
                                    //if (empty($context['ReasoncategoryID'])) {
                                        $packageObj = new Package_Model_Package();
                                        $isPackageExists = $packageObj->packageExist($context);
                                        if ($isPackageExists > 0) {
                                            $this->_error(self::EXISTS);
                                            $isValid = false;
                                        }
                                    //}
                                    break;
                                    
                                Default :
                                    $isValid = false;
                                    break;
                            }
                            break;
                        case "CurrentPassword" :
                            $formName = $this->_form->getName();
                            switch ($formName) {
                                case "profile" :
                                    $profileID = $this->_session->UserID;
                                    $userObj = new User_Model_User();
                                    $userProfileData = $userObj->getUserByID($profileID);
                                    $typedPassword = md5(trim($context["CurrentPassword"]));
                                    $currentPassword = $userProfileData["Password"];
                                    if ($currentPassword != $typedPassword) {
                                        $this->_error(self::PASSWORDNOTMATCH);
                                        $isValid = false;
                                        break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                    }
                }
            //}
        }
        return $isValid;
    }

}

?>
