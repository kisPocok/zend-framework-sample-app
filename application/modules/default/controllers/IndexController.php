<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->headTitle('Zend Framework prezi');
    }

    public function indexAction()
    {
        $this->view->name = "PHP meetup";
    }

}
