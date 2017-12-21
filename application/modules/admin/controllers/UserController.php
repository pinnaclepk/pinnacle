<?php

class Admin_UserController extends Pinnacle_Controllers_Action {

    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout('login');
        $this->view->assign("pageTitle", $this->_translate->_("user"));
    }

    public function indexAction() {

        $this->setLayout();
        $userObj = new Admin_Model_User();
        $searchFields = array();
        if ($this->getRequest()->isPost()) {
            $searchData = $this->getRequest()->getParams();
            if ($searchData["action"] == "index") {
                $this->view->assign("searchData", $searchData);
                $searchFields = array(
                    array("op" => "LIKE", "column" => 'user.UserName', "value" => isset($searchData['UserName']) ? $searchData['UserName'] : ''),
                    array("op" => "LIKE", "column" => 'user.Email', "value" => isset($searchData['Email']) ? $searchData['Email'] : ''),
                    array("op" => "=", "column" => 'user.IsActive', "value" => isset($searchData['IsActive']) ? $searchData['IsActive'] : '')
                );
            }
        }
        $grid = $userObj->getGridUsers($searchFields);

        $grid->setColumnsHidden(array('UserID', 'UpdateTime'));
        $grid->updateColumn('Serialno', array('title' => $this->_translate->_("sr"), 'style' => 'text-align:right;', 'class' => 'width_80'));
        $grid->updateColumn('Username', array('title' => $this->_translate->_("username")));
        $grid->updateColumn('Name', array('title' => $this->_translate->_("name")));
        //$grid->updateColumn('LName', array('title' => $this->_translate->_("last_name")));
        $grid->updateColumn('JoiningDate', array('title' => $this->_translate->_("joining_date")));
        $grid->updateColumn('MobileNo', array('title' => $this->_translate->_("mobile_number")));
        $grid->updateColumn('status', array('title' => $this->_translate->_('status'), 'class' => 'width_80', 'format' => 'status'));
        $viewcol = new Bvb_Grid_Extra_Column();
        $viewcol->position('right')
                ->order(-2)
                ->name('viewcustom')
                ->title('')
                ->class('width_80')
                //->decorator("<a href='javascript:void(0)' onclick=\"getData('/user/user/view/id/{{UserID}}')\"><div class='grid_icon grid_icon_view'> </div></a>");
                ->decorator("<a href='javascript:void(0)' onclick=\"getData('/admin/user/view/id/{{UserID}}')\">View</a>");
        $grid->addExtraColumns($viewcol);

        $this->view->assign("pages", $grid);
        $this->view->assign("errmsg", $this->getRequest()->getParam('errmsg', ''));
        $this->render('index');
    }

    public function viewAction() {
        $userObj = new Admin_Model_User();
        $userID = $this->getRequest()->getParam("id");
        $userData = $userObj->getUserByID($userID);
        $this->view->assign('UserData', $userData);
        $this->view->assign('UserID', $userID);
    }

    public function addAction() {
        $this->_add();
    }

    private function _add($action = "add") {

        $userForm = new Application_Form_UserForm();

        if ($this->getRequest()->isPost()) {
            if (isset($_FILES['Photo']) && !empty($_FILES['Photo']['name'])) {
                $ext = substr($_FILES['Photo']['name'], strrpos($_FILES['Photo']['name'], ".") + 1);
                $commObj = new Application_Model_Common();
                if (!$commObj->validateImage($ext)) {
                    $msg = "invalid_file_format";
                    $this->__redirect("admin/profile/index/errmsg/" . $msg);
                }
            }
            $userObj = new Admin_Model_User();
            $formData = $this->getRequest()->getParams();
            if ($action == "edit") {

                //if (!isset($formData['Password']) && trim($formData['Password']) == '') { // password field empty, remove validators
                $userForm->getElement('Password')
                        ->setRequired(false)
                        ->clearValidators();

                $userForm->getElement('ConfirmPassword')
                        ->setRequired(false)
                        ->clearValidators();
                //}
            }

            if ($userForm->isValid($formData)) {

                $msg = '';
                if (empty($formData["UserID"])) {
                    $result = $userObj->addUser($formData, $_FILES);
                    $msg = $this->_translate->_("user_added_success");
                } else {
                    $result = $userObj->editUser($formData, $_FILES);
                    $msg = $this->_translate->_("user_updated_success");
                }
                if ($result) {
                    $this->getRequest()->setParams(array());
                    $msgArr = array();
                    if ($result !== FLG_NO_CHANGE) {
                        $msgArr = array("errmsg" => $msg);
                    }
                    $this->__forward("index", "user", "admin", $msgArr);
                    return;
                } else {
                    $this->view->assign("errmsg", $this->_translate->_('error_operation_failed'));
                }
            } else {
                print_r($userForm->getErrors());
                if ($action == "edit" && !empty($formData["UserID"])) {
                    $userData = $userObj->getUserByID($formData["UserID"]);
                    $this->view->assign('UserData', $userData);
                    $this->view->assign('UserID', $formData["UserID"]);
                }
            }
            $userForm->populate($formData);
        }

        $this->view->assign("userForm", $userForm);
        $this->render("add");
    }

    public function editAction() {
        if ($this->getRequest()->isPost()) {
            $this->_add("edit");
            return;
        }
        $userID = $this->getRequest()->getParam("id");
        if (!empty($userID)) {
            $userObj = new Admin_Model_User();
            //$commonObj = new Application_Model_Common();
            $userData = $userObj->getUserByID($userID);
            $sponsorList = array();
            if($userData['UserTypeID'])
                $sponsorList = $userObj->getSponsorList($userData['UserTypeID'], true);
            
            print_r($sponsorList);
            
            $userForm = new Application_Form_UserForm();
            
            $userForm->SponsorID->setMultiOptions((array(""=>"Select") + $sponsorList));
            
            $userForm->populate($userData);
            $this->view->assign("UserData", $userData);
            //$this->view->assign("Sponsor", $sponsorList);
            $this->view->assign('userForm', $userForm);
            $this->view->assign('UserID', $userID);
            $this->view->assign('edit', true);
            $this->render("add");
        }
    }

    public function deleteAction() {
        $userID = $this->getRequest()->getParam("id");
        $modelObj = new Admin_Model_User();
        if (!$modelObj->delete($userID)) {
            $errmsg = $this->_translate->_('not_deleted');
        } else {
            $errmsg = $this->_translate->_('user_deleted');
        }
        $this->__forward("index", "user", "admin", array("errmsg" => $errmsg));
    }
    
    public function getsponsorlistAction()
    {
        $userTypeID = $this->getRequest()->getParam("UserTypeID");
        if (!empty($userTypeID)) {            
            $userObj = new Admin_Model_User();
            $sponsorData = $userObj->getSponsorList($userTypeID, true);
        }
        
        echo json_encode($sponsorData);
        exit;
    }
    
}

?>
