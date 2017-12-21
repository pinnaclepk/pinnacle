<?php

class Application_Model_Module {

    private $_translate;

    function __construct() {
        $this->_translate = Zend_Registry::get("translate");
    }

    public function getModuleOptions() {
        return array("Module", "View", "Add", "Edit", "Delete", "Import", "Export"); //"Log",
    }

    public function getModuleAccessInfo() {
        return array(
            MODULE_USER => array("name" => "user", "label" => $this->_translate->_("user"), "access" => array(true, true, true, true, false, false)),
              MODULE_COIN => array("name" => "coin", "label" => $this->_translate->_("coin"), "access" => array(true, true, true, true, false, false)),
            MODULE_PLAN => array("name" => "plan", "label" => $this->_translate->_("plan"), "access" => array(true, true, true, true, false, false)),
            MODULE_PACKAGE => array("name" => "package", "label" => $this->_translate->_("package"), "access" => array(true, true, true, true, false, false)),
            
        );
    }

    public function getMenuArray() {

        return array(

            MODULE_USER => array($this->_translate->_("members") => array(
                    $this->_translate->_("user") => 'user/index')
            ),
            MODULE_COIN => array($this->_translate->_("settings") => array(
                    $this->_translate->_("coin") => 'coin/index')
            ),
            MODULE_PLAN => array($this->_translate->_("settings") => array(
                    $this->_translate->_("plan") => 'plan/index')
            ),
            MODULE_PACKAGE => array($this->_translate->_("settings") => array(
                    $this->_translate->_("package") => 'package/index')
            ),
            
        );
    }
}
