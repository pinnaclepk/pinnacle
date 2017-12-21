<?php

class Application_Form_Base extends Zend_Form {

    public $translate;
    public $config;

    public function __construct($options = null) {
        $this->translate = Zend_Registry::get("translate");
        $this->config    = Zend_Registry::get("clientConfig");
        parent::__construct($options);
    }

    public function getButtonSaveClass() {
        return "btn btn-block btn-primary button_save font_helvatica";
    }

    public function addElement($element, $validationName = array(), $options = null) {
        $element->removeDecorator("Label");
        $element->removeDecorator("DD");
        $element->addDecorator('HtmlTag', array('tag' => 'span'));
        if ($element instanceof Zend_Form_Element_File) {
            $decorators = array('File');
        } else {
            $decorators = array('ViewHelper');
        }
        if ($element instanceof Zend_Form_Element_Text) {
            $element->addFilter('StringTrim');
        }
        $element->setDecorators(
                array($decorators)
        );
        
        /*         * ************************* For Validations - made common ******************* */
        /*if (isset($validationName) && !empty($validationName)) {
            $validation = $validationName['validationName'];
            switch ($validation) {
                case "LoginID":
                    $loginIDOptions = $validationName['options'];
                    if (isset($loginIDOptions) && !empty($loginIDOptions)) {

                        if (isset($loginIDOptions['required']) && $loginIDOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($loginIDOptions['regex']) && $loginIDOptions['regex'] == 1 && IDNUMBERREGEX != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => IDNUMBERREGEX));
                            $element->addValidator($regexValid);
                        }
                        if (isset($loginIDOptions['stringlength']) && $loginIDOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->nameMinLength, "max" => $this->config->nameMaxLength));
                            $element->setAttrib("maxlength", $this->config->nameMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;
                case "Name":
                    $nameOptions = $validationName['options'];
                    if (isset($nameOptions) && !empty($nameOptions)) {
                        if (isset($nameOptions['regex']) && $nameOptions['regex'] == 1 && NAMEREGEX != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => NAMEREGEX));
                            $element->addValidator($regexValid);
                        }
                        if (isset($nameOptions['stringlength']) && $nameOptions['stringlength'] == 1) {
                            if (isset($nameOptions['type']) && $nameOptions['type'] == "max") {
                                $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->nameMinLength, "max" => $this->config->nameMaxLengthMax));
                                $element->setAttrib("maxlength", $this->config->nameMaxLengthMax);
                            } else {
                                $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->nameMinLength, "max" => $this->config->nameMaxLength));
                                $element->setAttrib("maxlength", $this->config->nameMaxLength);
                            }
                            $element->addValidator($lengthValid);
                        }
                        if (isset($nameOptions['required']) && $nameOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                    }
                    break;
                case "Email":
                    $emailOptions = $validationName['options'];
                    if (isset($emailOptions) && !empty($emailOptions)) {
                        if (isset($emailOptions['required']) && $emailOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($emailOptions['regex']) && $emailOptions['regex'] == 1) {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => $this->config->emailRegex));
                            $element->addValidator($regexValid);
                        }
                        if (isset($emailOptions['stringlength']) && $emailOptions['stringlength'] == 1) {
                            $element->setAttrib("maxlength", $this->config->emailMaxLength);
                        }
                    }
                    break;
                case "Phone":
                    $phoneOptions = $validationName['options'];
                    if (isset($phoneOptions) && !empty($phoneOptions)) {
                        if (isset($phoneOptions['validdigit']) && $phoneOptions['validdigit'] == 1) {
                            $digitValid = new Zend_Validate_Digits();
                            $element->addValidator($digitValid);
                        }
                        if (isset($phoneOptions['required']) && $phoneOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($phoneOptions['stringlength']) && $phoneOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->phoneMinLength, "max" => $this->config->phoneMaxLength));
                            $element->setAttrib("maxlength", $this->config->phoneMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;

                case "Zipcode":
                    $zipcodeOptions = $validationName['options'];
                    if (isset($zipcodeOptions) && !empty($zipcodeOptions)) {
                        if (isset($zipcodeOptions['required']) && $zipcodeOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($zipcodeOptions['validdigit']) && $zipcodeOptions['validdigit'] == 1) {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => $this->config->zipcodeRegex));
                            $element->addValidator($regexValid);
                        }
                        if (isset($zipcodeOptions['stringlength']) && $zipcodeOptions['stringlength'] == 1) {
                            $zipcodelengthValid = new Zend_Validate_StringLength(array('min' => $this->config->zipcodeMinLength, "max" => $this->config->zipcodeMaxLength));
                            $element->setAttrib("maxlength", $this->config->zipcodeMaxLength);
                            $element->addValidator($zipcodelengthValid);
                        }
                    }
                    break;
                case "IDNumber":
                    $idNumberOptions = $validationName['options'];
                    if (isset($idNumberOptions) && !empty($idNumberOptions)) {
                        if (isset($idNumberOptions['required']) && $idNumberOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($idNumberOptions['regex']) && $idNumberOptions['regex'] == 1 && IDNUMBERREGEX != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => IDNUMBERREGEX));
                            $element->addValidator($regexValid);
                        }
                        if (isset($idNumberOptions['stringlength']) && $idNumberOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->idnumberMinLength, "max" => $this->config->idnumberMaxLength));
                            $element->setAttrib("maxlength", $this->config->idnumberMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;
                case "Port":
                    $portOptions = $validationName['options'];
                    if (isset($portOptions) && !empty($portOptions)) {
                        if (isset($portOptions['required']) && $portOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($portOptions['regex']) && $portOptions['regex'] == 1 && PORTREGEX != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => PORTREGEX));
                            $element->addValidator($regexValid);
                        }
                        if (isset($portOptions['stringlength']) && $portOptions['stringlength'] == 1) {
                            $element->addValidator('Between', false, array('min' => $this->config->portMinLength, 'max' => $this->config->portMaxLength, 'inclusive' => true));
                        }
                    }
                    break;
                case "Description":
                    $descOptions = $validationName['options'];
                    if (isset($descOptions) && !empty($descOptions)) {
                        if (isset($descOptions['required']) && $descOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($descOptions['stringlength']) && $descOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->descriptionMinLength, "max" => $this->config->descriptionMaxLength));
                            $element->setAttrib("maxlength", $this->config->descriptionMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;
                case "Code":
                    $codeOptions = $validationName['options'];
                    if (isset($codeOptions) && !empty($codeOptions)) {
                        if (isset($codeOptions['required']) && $codeOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($codeOptions['regex']) && $codeOptions['regex'] == 1 && CODEREGEX != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => CODEREGEX));
                            $element->addValidator($regexValid);
                        }
                        if (isset($codeOptions['stringlength']) && $codeOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->codeMinLength, "max" => $this->config->codeMaxLength));
                            $element->setAttrib("maxlength", $this->config->codeMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;
                case "Amount":
                    $amountOptions = $validationName['options'];
                    if (isset($amountOptions) && !empty($amountOptions)) {
                        if (isset($amountOptions['floatvalidation']) && $amountOptions['floatvalidation'] == 1) {
                            $lengthValid16 = new Zend_Validate_StringLength(array('min' => $this->config->amountMinLength, "max" => $this->config->amountMaxLength));
                            $element->addValidators(array($lengthValid16, new Zend_Validate_Float(), new Application_Form_Validate_Precision(array('dec_prec' => $this->config->decprec))));
                        }
                        if (isset($amountOptions['greaterthanzero']) && $amountOptions['greaterthanzero'] == 1) {
                            $amountValid = new Zend_Validate_GreaterThan(array('min' => 0));
                            $amountValid->setMessage($this->translate->_("error_value_greaterthan"), Zend_Validate_GreaterThan::NOT_GREATER);
                            $element->setAttrib("maxlength", $this->config->amountMaxLength);
                            $element->addValidator($amountValid);
                        }
                        if (isset($amountOptions['required']) && $amountOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($amountOptions['minmax']) && $amountOptions['minmax'] == 1) {
                            $element->addValidator(new Application_Form_Validate_Custom($this, array("validation" => array("Range"))), true);
                        }
                    }
                    break;
                case "Address":
                    $addressOptions = $validationName['options'];
                    if (isset($addressOptions) && !empty($addressOptions)) {

                        if (isset($addressOptions['required']) && $addressOptions['required'] == 1) {
                            $element->setRequired(true);
                        }
                        if (isset($addressOptions['regex']) && $addressOptions['regex'] == 1 && $this->config->addressRegex != '') {
                            $regexValid = new Zend_Validate_Regex(array('pattern' => $this->config->addressRegex));
                            $element->addValidator($regexValid);
                        }
                        if (isset($addressOptions['stringlength']) && $addressOptions['stringlength'] == 1) {
                            $lengthValid = new Zend_Validate_StringLength(array('min' => $this->config->AddressMinLength, "max" => $this->config->AddressMaxLength));
                            $element->setAttrib("maxlength", $this->config->AddressMaxLength);
                            $element->addValidator($lengthValid);
                        }
                    }
                    break;


                Default :
                    return false;
                    break;
            }
        }*/
        parent::addElement($element, $name = null, $options = null);
    }

    public function isValid($data) {
       
        $valid = parent::isValid($data);

        $messagesForTest = array();
        $exclude = array('Zend_Form_Element_Submit', 'Zend_Form_Element_Button', 'Zend_Form_Element_Reset');
        $str = '';
        foreach ($this->getElements() as $element) {
            if ((!array_key_exists($element->getName(), $data)) && (!in_array($element->getType(), $exclude))) {
                $str .= "Form Element '<i>{$element->getName()}</i>' -- {$element->getType()} not found <br>";
            }
            if ($element->hasErrors()) {
                $oldClass = $element->getAttrib('class');
                if (!empty($oldClass)) {
                    $element->setAttrib('class', $oldClass . ' errors');
                } else {
                    if ($element->getType() == 'Zend_Form_Element_File') {
                        $element->setAttrib('class', 'errorsFile');
                    } else {
                        $element->setAttrib('class', 'errors');
                    }
                    $element->setAttrib('class', 'errors');
                    $validators = $element->getValidators();
                    $errors = $element->getErrors();
                    $errMsg = $element->getMessages();
                    $strElement = $element->getName();
                    foreach ($errors as $error) {
                        switch ($error) {
                            case "isEmpty":
                                $requiredMsg = $this->translate->_("error_required");
                                break;
                            case "notAlpha":
                                $requiredMsg = $this->translate->_("error_string_must_contain_only_alphabets");
                                break;
                            case "regexNotMatch":
                                $requiredMsg = $this->translate->_("error_not_valid_string");
                                break;
                            case "stringLengthTooShort":
                                $requiredMsg = sprintf($this->translate->_("error_string_length_short"), $validators['Zend_Validate_StringLength']->getMin(), $validators['Zend_Validate_StringLength']->getMax());
                                break;
                            case "stringLengthTooLong":
                                $requiredMsg = sprintf($this->translate->_("error_string_length_long"), $validators['Zend_Validate_StringLength']->getMin(), $validators['Zend_Validate_StringLength']->getMax());
                                break;
                            case "notAlnum":
                                $requiredMsg = $this->translate->_("error_string_must_contain_only_alphabets_or_numeric_characters");
                                break;
                            case "notFloat":
                                $requiredMsg = $this->translate->_("error_not_valid_decimal");
                                break;
                            case "notDigits":
                                $requiredMsg = $this->translate->_("error_not_valid_digits");
                                break;
                            case "emailAddressInvalidFormat":
                                $requiredMsg = $this->translate->_("error_not_valid_email_address");
                                break;
                            case "phoneInvalidFormat":
                                $requiredMsg = $this->translate->_("error_invalid_phone_format");
                                break;
                            case "uniqueMailOrPhone":
                                $requiredMsg = $this->translate->_("error_phone_or_email_already_exists");
                                break;
                            case "requiredMailOrPhone":
                                $requiredMsg = $this->translate->_("error_email_or_phone_required");
                                break;
                            case "exists":
                                $requiredMsg = $this->translate->_("error_already_exists");
                                break;
                            case "amountNotInRange":
                                $requiredMsg = $this->translate->_("error_amount_not_in_range");
                                break;
                            case "notSame":
                                $requiredMsg = $this->translate->_("error_did_not_match_password");
                                break;
                            case "notBetween":
                                $requiredMsg = $this->translate->_("error_not_within_range");
                                break;
                            case "notGreater":
                                $requiredMsg = $this->translate->_("error_maximum_value_must_be_greater_than_minimum_value");
                                break;
                            case "NotMatchCurrentPassword":
                                $requiredMsg = $this->translate->_("error_existing_password_is_wrong");
                                break;
                            case "fileExtensionFalse":
                                $requiredMsg = $this->translate->_("error_file_extension_is_not_valid");
                                break;
                            case "fileUploadErrorIniSize":
                                $requiredMsg = $this->translate->_("error_please_select_file");
                                break;
                            default :
                                if (isset($errMsg[$error])) {
                                    $requiredMsg = $errMsg[$error];
                                } else {
                                    $requiredMsg = $error . ' ' . $this->translate->_("error_invalid_input");
                                }
                                break;
                        }
                        if ($requiredMsg) {
                            break;
                        }
                    }
                    $element->setAttrib('title', $requiredMsg);
                    if (APPLICATION_ENV == "testing") {
                        $messagesForTest[$element->getName()] = $requiredMsg;
                    }
                }
            }
        }
        return $valid;
    }

}
