<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->_helper->layout->disableLayout();
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('front');
    }

    public function indexAction()
    {
        // action body
    }


}

