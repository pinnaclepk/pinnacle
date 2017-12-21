<?php

class Admin_ProfileController extends Pinnacle_Controllers_Action {

    public $config;

    public function init() {
        parent::init();

        $this->config = Zend_Registry::get("clientConfig");
        $this->view->assign("pageTitle", $this->_translate->_("user_profile"));
        $this->_session = Zend_Registry::get("session");
        $this->_helper->layout->disableLayout();
    }

    /*public function indexAction() {
        $userForm = new Application_Form_ProfileForm();
        $userID = $this->_session->UserID;
        //if (!empty($userID)) {
        if (isset($_FILES['Photo']) && !empty($_FILES['Photo']['name'])) {
            $ext = substr($_FILES['Photo']['name'], strrpos($_FILES['Photo']['name'], ".") + 1);
            $commObj = new Application_Model_Common();
            if (!$commObj->validateImage($ext)) {
                $msg = "invalid_file_format";
                $this->__redirect("profile/profile/index/errmsg/" . $msg);
            }
        }
        $userObj = new User_Model_User();

        $userData = $userObj->getUserByID($userID);

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getParams();
            $formData['IsActive'] = $userData['IsActive'];
            if ($userForm->isValid($formData)) {
                $msg = '';

                $result = $userObj->editUser($formData, $_FILES);
                if ($result) {
                    $msg = "profile_updated_success";
                }
                $this->getRequest()->setParams(array());
                $this->__redirect("profile/profile/index/errmsg/" . $msg);
                return;
            }
            $userForm->populate($formData);
        } else {
            $userForm->populate($userData);
        }
        $this->view->assign("errmsg", $this->_translate->_($this->getRequest()->getParam('errmsg', '')));

        $this->view->assign("ProfileData", $userData);
        $this->view->assign('ProfileForm', $userForm);
        $this->view->assign('UserID', $userID);
        $this->view->assign("opBarRequired", false);
        //}
    }*/

    public function changepasswordAction() {
        $changepasswordForm = new Application_Form_ChangePasswordForm();
        $profileID = $this->_session->UserID;
        $msg = '';
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getParams();
            $changepasswordForm->getElement('CurrentPassword')
                    ->setRequired(true);
            if ($changepasswordForm->isValid($formData)) {

                $userObj = new Admin_Model_User();

                $result = $userObj->changeUserPassword($formData);
                if ($result) {
                    $msg = $this->_translate->_("password_change_success");
                    //$this->__forward("index", "changepassword", "profile", array("errmsg" => $msg));
                } else {
                    $msg = $this->_translate->_("invalid_password");
                }
            }
        }
        $this->view->assign("errmsg", $msg);
        $this->view->assign("ProfileID", $profileID);
        $this->view->assign("changepasswordForm", $changepasswordForm);
    }

}

?>
