<?php

class Admin_LoginController extends Pinnacle_Controllers_Action {

    public $_translate;

    public function init() {
        
        //$this->_translate = Zend_Registry::get("translate");
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout('login');
    }

    public function indexAction() {        
        $this->__forward("login");
    }

    public function loginAction() {
        $userObj = new Admin_Model_User();
        $loginForm = new Application_Form_LoginForm();
        if ($this->getRequest()->isPost()) {
            if ($loginForm->isValid($_POST)) {
                $params = $this->getRequest()->getParams();
                $isAuthenticated = $userObj->authenticateUser($params);
                if ($isAuthenticated) {
                    $this->_redirect('/admin/index', array("login" => true));
                    return;
                } else if (!$isAuthenticated) {
                    $this->_helper->flashMessenger->addMessage($this->_translate->_('invalid_login'));
                } else {
                    $this->_helper->flashMessenger->addMessage($isAuthenticated);
                }
            } else {
                $this->_helper->flashMessenger->addMessage($this->_translate->_('invalid_login'));
            }
        }
        $loginForm = new Application_Form_LoginForm();
        $this->view->assign('loginForm', $loginForm);
        $this->view->assign("errmsg", $this->_helper->flashMessenger->getCurrentMessages());
        $this->_helper->flashMessenger->clearMessages();
    }

    public function isloggedinAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $userObj = new User_Model_User();
        $result = $userObj->CheckOnlineUser();
        if (empty($result)) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function logoutAction() {
        $namespace = new Zend_Session_Namespace(SESSION_NAME);
        $namespace->unsetAll();
        Zend_Session::destroy(true);
        $this->__redirect("/admin");
    }

    public function forgotAction() {
        $this->_helper->layout->setLayout('login');

        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            if ($params['username']) {
                $userObj = new Admin_Model_User();
                $token = substr(str_shuffle("abcdefgjijklmnopqrstuvwxyzABCDEFGJIJKLMNOPQRSTUVWXYZ1234567890"), 0, 16);
                $resetOk = $userObj->addToken($params['username'], $token);
                if (is_array($resetOk)) {
                    $commonObj = new Application_Model_Common();

                    $to = '<' . $resetOk['Email'] . '>';
                    $sub = "Reset Password";
                    $bcc = '';
                    $from = "<noreply@misi.net.au>";
                    $body = "Click on below Link to Reset Password<br> <br>";

                    $body .= "http://" . $_SERVER['HTTP_HOST'] . "/admin/login/reset?token=" . $token;

                    try {
                        $isSent = $commonObj->sendMail($to, $sub, $bcc, $from, $body);
                        if ($isSent) {
                            $userObj->deactivateUser($resetOk['UserID']);
                            $this->view->assign("msg", "check_mail_send");
                        }
                    } catch (Exception $e) {
                        $this->view->assign("msg", "mail_not_send");
                    }
                } else
                    $this->view->assign("msg", "invalid_username");
            }
        }
    }

    public function resetAction() {
        $userObj = new User_Model_User();
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            if ($params['password'] == $params['confirmpassword']) {
                $userObj->setPassword($params);
                $this->__redirect("login.php", array("msg" => $this->_translate->_('password_reset_succesfully')));
            } else {
                $this->view->assign("msg", $this->_translate->_("password_mismatch"));
            }
        }
        $token = $this->getRequest()->getParam('token', '');

        if (!empty($token)) {
            $isValidToken = $userObj->verifyToken($token);
            //print_r($isValidToken);
            if ($isValidToken['msg'] != "valid") {
                $this->view->assign("msg", $isValidToken['msg']);
                $this->render("reset");
                return;
            }
        }

        $this->view->assign("UserID", $isValidToken['UserID']);
        $this->render("reset");
    }
}
?>
