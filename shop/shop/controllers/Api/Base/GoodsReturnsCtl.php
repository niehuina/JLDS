<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_GoodsReturnsCtl extends Yf_AppController
{
    public $goods_ReturnsModel =null;
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
        $this->goods_ReturnsModel = new Goods_ReturnsModel();
    }

    /**
     * 首页
     *
     * @access public
     */
    public function serviceType()
    {

        include $this->view->getView();
    }

    /**
     * 列表数据
     *
     * @access public
     */
    public function getReturnsList()
    {

        $page = request_string('page');
        $rows = request_string('rows');

        $cond_row  = array();
        $order_row = array('returns_setting_id' => 'DESC');

        $data = array();

        $data = $this->goods_ReturnsModel->getPageInfoList($cond_row, $order_row, $page, $rows);


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
        $data['returns_setting_Name']    = request_string('returns_setting_Name');
        $cond_row         = array(
            "returns_setting_Name" =>  $data['returns_setting_Name']
        );
        $existModel = $this->goods_ReturnsModel->getByWhere($cond_row);
        if($existModel!=null){
            $msg    = __('名称已存在');
            $status = 250;
        }
        else{
            $flag = $this->goods_ReturnsModel->addReturns($data);
            if ($flag){
                $msg    = __('success');
                $status = 200;
            }
            else{
                $msg    = __('failure');
                $status = 250;
            }
            $data['id'] = $data['returns_setting_id'];
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
        $returns_setting_id = request_int('returns_setting_id');

        $flag = $this->goods_ReturnsModel->removeReturns($returns_setting_id);

        if ($flag){
            $msg    = __('success');
            $status = 200;
        }
        else {
            $msg    = __('failure');
            $status = 250;
        }
        $data['returns_setting_id'] = array($returns_setting_id);
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['returns_setting_id']         = request_int('returns_setting_id');
        $data['returns_setting_Name']    = request_string('returns_setting_Name');
        $returns_setting_id = request_int('returns_setting_id');

        $cond_row         = array(
            "returns_setting_Name" =>  $data['returns_setting_Name']
        );
        $existList= $this->goods_ReturnsModel->getByWhere($cond_row);
        if(!empty($existList)) {
            $existModel = current($existList);

            if ($existModel != null && $existModel['returns_setting_id'] != $data['returns_setting_id']) {
                $msg = __('名称已存在');
                $status = 250;
                $this->data->addBody(-140, $data,$msg, $status);
                return;
            }
        }
        $flag = $this->goods_ReturnsModel->editReturns($returns_setting_id, $data);
        $this->data->addBody(-140, $data);
    }
}

?>