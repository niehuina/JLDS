<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_ServiceTypeCtl extends Yf_AppController
{
    public $volunteer_servicesypeModel =null;
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
        $this->volunteer_servicesypeModel = new Volunteer_ServicesTypeModel();
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
    public function getServiceTypeList()
    {

        $page = request_string('page');
        $rows = request_string('rows');

        $cond_row  = array();
        $order_row = array('services_type_id' => 'DESC');

        $data = array();

        $data = $this->volunteer_servicesypeModel->getPageInfoList($cond_row, $order_row, $page, $rows);


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
        $data['services_type_Name']    = request_string('services_type_Name');
        $cond_row         = array(
            "services_type_Name" =>  $data['services_type_Name']
        );
        $existModel = $this->volunteer_servicesypeModel->getByWhere($cond_row);
        if($existModel!=null){
            $msg    = __('名称已存在');
            $status = 250;
        }
        else {
            $flag = $this->volunteer_servicesypeModel->addServiceType($data);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data['id'] = $data['services_type_id'];
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
        $services_type_id = request_int('services_type_id');

        $flag = $this->volunteer_servicesypeModel->removeServiceType($services_type_id);

        if ($flag)
        {
            $msg    = __('success');
            $status = 200;

        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data['services_type_id'] = array($services_type_id);

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['services_type_id']         = request_int('services_type_id');
        $data['services_type_Name']    = request_string('services_type_Name');
        $services_type_id = request_int('services_type_id');
        $cond_row         = array(
            "services_type_Name" =>  $data['services_type_Name']
        );
        $existList= $this->volunteer_servicesypeModel->getByWhere($cond_row);
        if(!empty($existList)) {
            $existModel = current($existList);
            if ($existModel != null && $existModel['services_type_id'] != $data['services_type_id']) {
                $msg = __('名称已存在');
                $status = 250;
                $this->data->addBody(-140, $data,$msg, $status);
                return;
            }
        }
        $flag = $this->volunteer_servicesypeModel->editServiceType($services_type_id, $data);

        $this->data->addBody(-140, $data);
    }
}

?>