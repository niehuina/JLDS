<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Class Mb_BannerImageCtl
 */
class Mb_BannerImageCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function index()
    {
        $data['banner_type'] = request_string('bannerType');
        include $this->view->getView();
    }

    public function setting()
    {
        $data['banner_type'] = request_string('bannerType');
        include $this->view->getView();
    }

    public function manage()
    {
        $menuName = request_string('menuName');
        include $this->view->getView();
    }
}