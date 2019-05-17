<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_ShippingMethodCtl extends Yf_AppController
{
    public $shipping_MethodModel = null;
    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        //include $this->view->getView();
        $this->shipping_MethodModel = new Shipping_MethodModel();
    }

    /**
     * 首页
     *
     * @access public
     */
    public function shippingMethod()
    {

        include $this->view->getView();
    }

    /**
     * 列表数据
     *
     * @access public
     */
    public function getMethodList()
    {

        $page = request_string('page');
        $rows = request_string('rows');

        $cond_row  = array();
        $order_row = array('shipping_method_id' => 'DESC');

        $data = array();

        $data = $this->shipping_MethodModel->getPageInfoList($cond_row, $order_row, $page, $rows);


        $this->data->addBody(-140, $data);
    }

    /**
     * 管理界面
     *
     * @access public
     */
    public function manage()
    {

        include $this->view->getView();
    }

    /**
     * 读取
     *
     * @access public
     */
//    public function get()
//    {
//        $search_id = request_int('search_id');
//
//        $cond_row['search_id'] = $search_id;
//
//        $rows = $this->shippingMethodModel->getSearchWordInfo($cond_row);
//
//        $data = array();
//
//        if ($rows)
//        {
//            $data = array_pop($rows);
//        }
//
//        $this->data->addBody(-140, $data);
//    }
//
    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {

        $data['shipping_method_Name']    = request_string('shipping_method_Name');
        $cond_row         = array(
            "shipping_method_Name" =>  $data['shipping_method_Name']
        );
        $existModel = $this->shipping_MethodModel->getByWhere($cond_row);
        if($existModel!=null){
            $msg    = __('名称已存在');
            $status = 250;
        }
        else {
            $flag = $this->shipping_MethodModel->addShippingMethod($data);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data['id'] = $data['shipping_method_id'];
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $shipping_method_id = request_int('shipping_method_id');

        $flag = $this->shipping_MethodModel->removeShippingMethod($shipping_method_id);

        if ($flag){
            $msg    = __('success');
            $status = 200;
        }
        else{
            $msg    = __('failure');
            $status = 250;
        }
        $data['shipping_method_id'] = array($shipping_method_id);
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['shipping_method_id']         = request_int('shipping_method_id');
        $data['shipping_method_Name']    = request_string('shipping_method_Name');
        $shipping_method_id = request_int('shipping_method_id');
        $cond_row         = array(
            "shipping_method_Name" =>  $data['shipping_method_Name']
        );
        $existList= $this->shipping_MethodModel->getByWhere($cond_row);
        if(!empty($existList)) {
            $existModel = current($existList);

            if ($existModel != null && $existModel['shipping_method_id'] != $data['shipping_method_id']) {
                $msg = __('名称已存在');
                $status = 250;
                $this->data->addBody(-140, $data,$msg, $status);
                return;
            }
        }
        $flag = $this->shipping_MethodModel->editShippingMethod($shipping_method_id, $data);
        $this->data->addBody(-140, $data);
    }
}

?>