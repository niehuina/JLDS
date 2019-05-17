<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_ShippingMethodCtl extends AdminController
{
    function shippingMethod()
    {
        include $view = $this->view->getView();;
    }

    function manage()
    {
        include $view = $this->view->getView();;
    }
}

?>