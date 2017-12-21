<?php

class Admin_CoinController extends Pinnacle_Controllers_Action {

    public function init() {
        parent::init();
        $this->setModelName('Admin_Model_Coin');
        $this->setFunctionName('getCoinByID');
        $this->_helper->layout->disableLayout();
        $this->view->assign("pageTitle", $this->_translate->_("coin"));
    }

    public function indexAction() {
        
        $this->setLayout();
        $coinObj = new Admin_Model_Coin();
        $searchFields = array();
        if ($this->getRequest()->isPost()) {
            $searchData = $this->getRequest()->getParams();
            if ($searchData["action"] == "index") {
                $this->view->assign("searchData", $searchData);
                $searchFields = array(
                    array("op" => "LIKE", "column" => 'coin.CoinName', "value" => isset($searchData['CoinName']) ? $searchData['CoinName'] : ''),
                    array("op" => "=", "column" => 'coin.IsActive', "value" => isset($searchData['IsActive']) ? $searchData['IsActive'] : ''),
                );
            }
        }
        $grid = $coinObj->getGridCoin($searchFields);
        $grid->setColumnsHidden(array('CoinID'));
        $grid->updateColumn('Serialno', array('title' => $this->_translate->_('sr'), 'style' => 'text-align:right;', 'class' => 'width_40'));
        $grid->updateColumn('CoincategoryName', array('title' => $this->_translate->_('coincategory_name')));
        $grid->updateColumn('CoinName', array('title' => $this->_translate->_('coin_name')));
        $grid->updateColumn('status', array('title' => $this->_translate->_('status'), 'class' => 'width_80', 'format' => 'status'));

        $viewcol = new Bvb_Grid_Extra_Column();
        $viewcol->position('right')
                ->order(-2)
                ->name('viewcustom')
                ->title('')
                ->class('width_20')
                ->decorator("<a href='javascript:void(0)' onclick=\"getData('/admin/coin/view/id/{{CoinID}}')\"> View</a>");
        $grid->addExtraColumns($viewcol);
        $this->view->assign("pages", $grid);
        $this->view->assign("errmsg", $this->getRequest()->getParam('errmsg', ''));
        $this->render('index');
    }

    public function addAction() {
        $this->_add();
    }

    public function _add($action = "add") {
        $form = new Application_Form_CoinForm();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getParams();
            if ($form->isValid($formData)) {
                $msg = '';
                $coinObj = new Admin_Model_Coin();
                if (empty($formData["CoinID"])) {
                    $result = $coinObj->addCoin($formData);
                    $msg = $this->_translate->_("coin_add_success");
                } else {
                    $result = $coinObj->editCoin($formData);
                    $msg = $this->_translate->_("coin_update_success");
                }
                if ($result) {
                    $this->getRequest()->setParams(array());
                    $this->__forward("index", "coin", "admin", array("errmsg" => $msg));
                    return;
                } else {
                    $this->view->assign("errmsg", $this->_translate->_('error_operation_failed'));
                }
            }
            
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
        $coinID = $this->getRequest()->getParam("id");
        if (!empty($coinID)) {
            $coinObj = new Admin_Model_Coin;
            $coinData = $coinObj->getCoinByID($coinID);

            $coinForm = new Application_Form_CoinForm();

            $coinForm->populate($coinData);
            $this->view->assign("CoinData", $coinData);
            $this->view->assign('form', $coinForm);
            $this->view->assign('CoinID', $coinID);
            $this->view->assign('edit', true);
            $this->render("add");
        }
    }

    public function viewAction() {
        $coinObj = new Admin_Model_Coin();
        $coinID = $this->getRequest()->getParam("id");
        $coinData = $coinObj->getCoinByID($coinID);

        $this->view->assign('CoinData', $coinData);
        $this->view->assign('CoinID', $coinID);
    }

    public function deleteAction() {
        $coinID = $this->getRequest()->getParam("id");
        $modelObj = new Admin_Model_Coin();
        if (!$modelObj->deleteCoin($coinID)) {
            $errmsg = $this->_translate->_('not_deleted');
        } else {
            $errmsg = $this->_translate->_('coin_deleted');
        }
        $this->__forward("index", "coin", "admin", array("errmsg" => $errmsg));
    }
    
    public function getcoinAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $CoincategoryID = $this->getRequest()->getParam("CoincategoryID", '');
        if (!empty($CoincategoryID)) {
            $coinObj = new Admin_Model_Coin();
            $coinData = $coinObj->getCoinByCategoryID($CoincategoryID);
            echo json_encode($coinData);
        }
        exit;
    }
}
