<?php

class Admin_PlanController extends Pinnacle_Controllers_Action {

    public function init() {
        parent::init();
        $this->setModelName('Admin_Model_Plan');
        $this->setFunctionName('getPlanByID');
        $this->_helper->layout->disableLayout();
        $this->view->assign("pageTitle", $this->_translate->_("plan"));
    }

    public function indexAction() {
        $this->setLayout();
        $planObj = new Admin_Model_Plan();
        $searchFields = array();
        if ($this->getRequest()->isPost()) {
            $searchData = $this->getRequest()->getParams();
            if ($searchData["action"] == "index") {
                $this->view->assign("searchData", $searchData);
                $searchFields = array(
                    array("op" => "=", "column" => 'plan.CoinID', "value" => isset($searchData['CoinID']) ? $searchData['CoinID'] : ''),
                    array("op" => "LIKE", "column" => 'plan.PlanName', "value" => isset($searchData['PlanName']) ? $searchData['PlanName'] : ''),
                    array("op" => "=", "column" => 'plan.IsActive', "value" => isset($searchData['IsActive']) ? $searchData['IsActive'] : ''),
                );
            }
        }
        $grid = $planObj->getGridPlan($searchFields);
        $grid->setColumnsHidden(array('PlanID'));
        $grid->updateColumn('Serialno', array('title' => $this->_translate->_('sr'), 'style' => 'text-align:right;', 'class' => 'width_40'));
        $grid->updateColumn('CoinName', array('title' => $this->_translate->_('coin_name')));
        $grid->updateColumn('PlanName', array('title' => $this->_translate->_('plan_name')));
        $grid->updateColumn('status', array('title' => $this->_translate->_('status'), 'class' => 'width_80', 'format' => 'status'));

        $viewcol = new Bvb_Grid_Extra_Column();
        $viewcol->position('right')
                ->order(-2)
                ->name('viewcustom')
                ->title('')
                ->class('width_20')
                ->decorator("<a href='javascript:void(0)' onclick=\"getData('/admin/plan/view/id/{{PlanID}}')\"> View</a>");
        $grid->addExtraColumns($viewcol);
        $this->view->assign("pages", $grid);
        $this->view->assign("errmsg", $this->getRequest()->getParam('errmsg', ''));
        $this->render('index');
    }

    public function addAction() {
        $this->_add();
    }

    public function _add($action = "add") {
        $form = new Application_Form_PlanForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getParams();
            if ($form->isValid($formData)) {
                $msg = '';
                $planObj = new Admin_Model_Plan();
                if (empty($formData["PlanID"])) {
                    $result = $planObj->addPlan($formData);
                    $msg = $this->_translate->_("plan_add_success");
                } else {
                    $result = $planObj->editPlan($formData);
                    $msg = $this->_translate->_("plan_update_success");
                }
                if ($result) {
                    $this->getRequest()->setParams(array());
                    $this->__forward("index", "plan", "admin", array("errmsg" => $msg));
                    return;
                } else {
                    $this->view->assign("errmsg", $this->_translate->_('error_operation_failed'));
                }
            }
            else
                print_r($form->getErrors());
           
            $form->populate($formData);
        }
        $this->view->assign("form", $form);
        $this->render("add");
    }

    public function editAction() {
        if ($this->getRequest()->isPost()) {
            $this->_add("edit");
            return;
        }
        $planID = $this->getRequest()->getParam("id");
        if (!empty($planID)) {
            $planObj = new Admin_Model_Plan;
            $planData = $planObj->getPlanByID($planID);

            $planForm = new Application_Form_PlanForm();

            $planForm->populate($planData);
            $this->view->assign("PlanData", $planData);
            $this->view->assign('form', $planForm);
            $this->view->assign('PlanID', $planID);
            $this->view->assign('edit', true);
            $this->render("add");
        }
    }

    public function viewAction() {
        $planObj = new Admin_Model_Plan();
        $planID = $this->getRequest()->getParam("id");
        $planData = $planObj->getPlanByID($planID);

        $this->view->assign('PlanData', $planData);
        $this->view->assign('PlanID', $planID);
    }

    public function deleteAction() {
        $planID = $this->getRequest()->getParam("id");
        $modelObj = new Admin_Model_Plan();
        if (!$modelObj->deletePlan($planID)) {
            $errmsg = $this->_translate->_('not_deleted');
        } else {
            $errmsg = $this->_translate->_('plan_deleted');
        }
        $this->__forward("index", "plan", "admin", array("errmsg" => $errmsg));
    }
    
    public function getplanAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $CoinID = $this->getRequest()->getParam("CoinID", '');
        if (!empty($CoinID)) {
            $planObj = new Admin_Model_Plan();
            $planData = $planObj->getPlanByCategoryID($CoinID);
            echo json_encode($planData);
        }
        exit;
    }
}
