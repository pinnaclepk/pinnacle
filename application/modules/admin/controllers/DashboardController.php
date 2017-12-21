<?php

class Dashboard_DashboardController extends Pinnacle_Controllers_Action {

    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout('login');
        $this->view->assign("pageTitle", $this->_translate->_("dashboard"));
    }

    public function indexAction() {
        $this->setLayout();
        $this->render("index");
    }
}

?>
