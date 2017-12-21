<?php

class Application_Form_LoginForm extends Application_Form_Base {

    public function init() {

        $this->setAction('/login/login/login')
                ->setMethod('post');

        $userName = $this->createElement("text", "UserName");
        $userName->setLabel($this->translate->_("username"));
        $userName->setValue("Username");
        $userName->setRequired(true);
        $userName->addErrorMessage($this->translate->_("required"));
        $userName->setAttrib("class", "form-control");
        $userName->setAttrib("onclick", "this.value=''");
        $this->addElement($userName);

        $password = $this->createElement("password", "Password");
        $password->setLabel($this->translate->_("password"));
        $password->renderPassword = true;
        $password->setValue("Password");
        $password->setAttrib("onclick", "this.value=''");
        $password->setRequired(true);
        $password->setAttrib("class", "form-control");
        $password->addErrorMessage($this->translate->_("required"));
        $this->addElement($password);

        $btnLogin = $this->createElement('submit', 'Login');
        $btnLogin->setValue("SignIn");
        $btnLogin->setAttrib("class", "btn btn-primary btn-block btn-flat");
        $this->addElement($btnLogin);
    }

}

?>
