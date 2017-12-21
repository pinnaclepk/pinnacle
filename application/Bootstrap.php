<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initViews() {
        Zend_Layout::startMvc();
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        $view->setHelperPath(APPLICATION_PATH . "/views/helpers/");
        $vr = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($vr);
        Zend_Registry::set('view', $view);
        $application = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
        Zend_Registry::set('application', $application);
        $view->addScriptPath(APPLICATION_PATH . '/views/scripts');
    }
    
    protected function _initParams() {
        $application = Zend_Registry::get('application');

        $clientConfig = new Zend_Config_Ini(APPLICATION_PATH . "/configs/config.ini", 'general');
        Zend_Registry::set('clientConfig', $clientConfig);
        //Zend_Registry::set("ENABLE_CACHE", false);

        if (!is_dir($application->log_path)) {
            mkdir($application->log_path, 0775, true);
        }
        Zend_Registry::set("LogDir", $application->log_path);

        $translateDir = APPLICATION_PATH . '/../language/' . $clientConfig->defaultLanguage . '/';
        if (!is_dir($translateDir)) {
            mkdir($translateDir, 0777, true);
        }
        $translate = new Zend_Translate(array(
            'adapter' => 'ini',
            'disableNotices' => true,
            'content' => realpath($translateDir),
            'locale' => $clientConfig->defaultLanguage
        ));

        Zend_Registry::set("translate", $translate);
        if (APPLICATION_ENV != 'production') {
            $writer = new Zend_Log_Writer_Stream(PUBLIC_PATH . '/logs/translation.log');
            $log = new Zend_Log($writer);
            $translate->setOptions(
                    array(
                        'log' => $log,
                        'logUntranslated' => true,
                    )
            );
        }
        $view = Zend_Registry::get('view');
        $view->translate = $translate;
        require_once(APPLICATION_PATH . "/configs/defined.php");
        require_once(APPLICATION_PATH . "/classes/generic.php");
    }

    public function _initSession() {
        $session_name = 'ZEND_SESSION';
        $session = new Zend_Session_Namespace($session_name);
        Zend_Registry::set("session", $session);
        if (!isset($session->initialized)) {
            Zend_Session::regenerateId();
            $session->initialized = true;
        }
    }
    
    protected function _initAutoload() {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $directory_point = APPLICATION_PATH . '/modules/';
        $directory = opendir($directory_point);
        while (($module_folder = readdir($directory))) {

            $loader = new Zend_Application_Module_Autoloader(array('namespace' => ucwords($module_folder), 'basePath' => $directory_point . $module_folder));
            unset($loader);
        }
        closedir($directory);
        $front = Zend_Controller_Front::getInstance();
        //$route_login = array('module' => 'admin', 'controller' => 'login', 'action' => 'login');
        //Zend_Controller_Front::getInstance()->getRouter()->addRoute('login', new Zend_Controller_Router_Route('login.php', $route_login));
    }

}

