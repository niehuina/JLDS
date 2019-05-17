<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Class Api_Mb_BillTypeCtl
 */
class Api_Mb_BillTypeCtl extends Api_Controller
{
    public $mbBillTypeModel;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->mbBillTypeModel = new Mb_BillTypeModel();
    }

    public function billTypeList()
    {
        $bill_type_list = $this->mbBillTypeModel->listByWhere();

        $data = $bill_type_list;
        $msg    = __('success');
        $status = 200;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function addBillType()
    {
        $param                           = request_row('param');

        $insert_data['type_no']          = $param['type_no'];
        $insert_data['type_name']        = $param['type_name'];

        $type_id = $this->mbBillTypeModel->addBillType($insert_data, true);

        $data = array();

        if ($type_id)
        {
            $insert_data['type_id'] = $type_id;
            $data = $insert_data;
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function removeBillType()
    {
        $type_id = request_int('type_id');
        $flag = $this->mbBillTypeModel->removeBillType($type_id);

        $data = array();
        if ($flag)
        {
            $data['type_id'] = $type_id;
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function editBillType()
    {
        $param           = request_row('param');
        $type_id = $param['type_id'];

        $update_data['type_no']          = $param['type_no'];
        $update_data['type_name']        = $param['type_name'];

        $flag = $this->mbBillTypeModel->editBillType($type_id, $update_data);

        $data = array();
        if ($flag !== false)
        {
            $update_data['type_id'] = $type_id;
            $data = $update_data;
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }
}