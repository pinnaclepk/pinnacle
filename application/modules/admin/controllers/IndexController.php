<?php

class Admin_IndexController extends Pinnacle_Controllers_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('layout');
    }

    public function indexAction() {
        $this->setLayout();
        if (Zend_Registry::isRegistered("session")) {
            $session = Zend_Registry::get("session");

            $application = Zend_Registry::get("application");

            $moduleName = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
            if (!empty($session->UserID)) {
            } else {
                $this->__redirect("/admin/login");
            }
        } else {
            $this->__redirect("/admin/login");
        }
    }

}
