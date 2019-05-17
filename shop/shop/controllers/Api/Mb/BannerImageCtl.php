<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Class Api_Mb_BannerImageCtl
 */
class Api_Mb_BannerImageCtl extends Api_Controller
{
    public $mbBannerImageModel;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->mbBannerImageModel = new Mb_BannerImageModel();
    }

    public function settingList()
    {
        $bannerType = request_string('bannerType');

        $cond_row = array();
        $cond_row['banner_type'] = $bannerType;

        $order_row = array();
        $order_row['banner_order'] = 'asc';
        $list = $this->mbBannerImageModel->listByWhere($cond_row, $order_row);
        $rData = current($list['items']);

        $msg    = __('success');
        $status = 200;

        $this->data->addBody(-140, $rData, $msg, $status);
    }

    public function bannerImageList()
    {
        $type = request_string('bannerType');

        $cond_row = array();
        $cond_row['banner_type'] = $type;

        $order_row = array();
        $order_row['banner_order'] = 'asc';

        $bill_type_list = $this->mbBannerImageModel->listByWhere($cond_row, $order_row);

        $data = $bill_type_list;
        $msg    = __('success');
        $status = 200;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 手机端模板
     */
    public function addBannerImage()
    {
        $param                           = request_row('param');

        $insert_data['mb_banner_image_id']  = $param['mb_banner_image_id'];
        $insert_data['banner_image']        = $param['banner_image'];
        $insert_data['banner_url']          = $param['banner_url'];
        $insert_data['banner_order']        = $param['banner_order'];

        $banner_image_id = $this->mbBannerImageModel->addBannerImage($insert_data, true);

        $data = array();

        if ($banner_image_id)
        {
            $insert_data['mb_banner_image_id'] = $banner_image_id;
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

    public function removeBannerImage()
    {
        $banner_image_id = request_int('mb_banner_image_id');
        $flag = $this->mbBannerImageModel->removeBannerImage($banner_image_id);

        $data = array();
        if ($flag)
        {
            $data['banner_image_id'] = $banner_image_id;
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

    public function editBannerImage()
    {
        $param           = request_row('param');
        $banner_image_id = $param['mb_banner_image_id'];

        $update_data['banner_image']        = $param['banner_image'];
        $update_data['banner_url']          = $param['banner_url'];
        $update_data['banner_order']        = $param['banner_order'];

        $flag = $this->mbBannerImageModel->editBannerImage($banner_image_id, $update_data);

        $data = array();
        if ($flag !== false)
        {
            $update_data['mb_banner_image_id'] = $banner_image_id;
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