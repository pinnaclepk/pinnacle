<?php

class Pinnacle_Controllers_Action extends Zend_Controller_Action {

    protected $_translate;
    protected $_session;
    protected $_module;
    protected $_controller;
    protected $_action;
    protected $_model;

    public function init() {
        
        $this->_translate = Zend_Registry::get("translate");
        $this->_session = Zend_Registry::get("session");
        $this->_module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        $this->_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->_action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        Zend_Registry::set('actionName', $this->_action);
    }

    public function __forward($action, $controller = null, $module = null, $params = array()) {

        if (APPLICATION_ENV != 'testing') {

            $this->_forward($action, $controller, $module, $params);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            echo $params['errmsg'];
        }
    }

    public function __redirect($url, $params = array()) {
        if (APPLICATION_ENV != 'testing') {
            $this->_redirect($url, $params);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            echo "Login Success";
        }
    }

    protected function setModelName($modelName) {
        $this->_model = $modelName;
    }

    protected function setFunctionName($functionName) {
        $this->_function = $functionName;
    }

    protected function getFunctionName() {
        return $this->_function;
    }

    public function viewAction() {
        $obj = new $this->_model();
        $ID = $this->getRequest()->getParam("id");
        $func = $this->_function;
        $this->data = $obj->{$func}($ID);
        $this->view->assign('data', $this->data);
        $this->view->assign('ID', $ID);
    }

    public function setLayout() {
        if (APPLICATION_ENV != "testing") {

            if (!$this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->layout->setLayout('layout');
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                $this->_helper->layout->disableLayout();
            }
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
        }
    }

}
