<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Class Mb_BillTypeCtl
 */
class Mb_BillTypeCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function index()
    {
        include $this->view->getView();
    }

    public function manage()
    {
        include $this->view->getView();
    }
}