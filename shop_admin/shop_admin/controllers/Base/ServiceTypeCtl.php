<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_ServiceTypeCtl extends AdminController
{
    function serviceType()
    {
        include $view = $this->view->getView();;
    }

    function manage()
    {
        include $view = $this->view->getView();;
    }
}

?>