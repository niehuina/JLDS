<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_UserCtl extends Buyer_Controller
{
    public $userInfoModel = null;
    public $userGradeModel = null;
    public $userResourceModel = null;
    public $userAddressModel = null;
    public $userPrivacyModel = null;
    public $userBaseModel = null;
    public $userTagModel = null;
    public $userTagRecModel = null;
    public $userFriendModel = null;
    public $messageTemplateModel = null;

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

        $this->userInfoModel        = new User_InfoModel();
        $this->userGradeModel       = new User_GradeModel();
        $this->userResourceModel    = new User_ResourceModel();
        $this->userAddressModel     = new User_AddressModel();
        $this->userPrivacyModel     = new User_PrivacyModel();
        $this->userBaseModel        = new User_BaseModel();
        $this->userTagModel         = new User_TagModel();
        $this->userTagRecModel      = new User_TagRecModel();
        $this->userFriendModel      = new User_FriendModel();
        $this->messageTemplateModel = new Message_TemplateModel();
    }
    /**
     * 会员信息--paycenter
     *
     * @access public
     */
    public function linkUserInfo()
    {

        $url = Yf_Registry::get('ucenter_api_url') . '?ctl=User&met=getUserInfo';
        location_to($url);
        die();
    }
    /**
     *获取会员信息
     *
     * @access public
     */
    public function getUserInfo()
    {
        $user_id = Perm::$userId;

        //获取一级地址
        $district_parent_id = request_int('pid', 0);
        $baseDistrictModel  = new Base_DistrictModel();
        $district           = $baseDistrictModel->getDistrictTree($district_parent_id);

        $data = $this->userInfoModel->getInfo($user_id);
        $data = $data[$user_id];

        $privacy = $this->userPrivacyModel->getPrivacy($user_id);
        $privacy = $privacy[$user_id];

        if ('json' == $this->typ)
        {
            $data['district'] = $district;
            $data['privacy']  = $privacy;

            $data['shop_type'] = 0;
            //wap端添加分销明细
            if(Perm::$shopId){
                $Shop_BaseModel  = new Shop_BaseModel();
                $shop_base = $Shop_BaseModel->getOne(Perm::$shopId);
                $data['shop_type'] = $shop_base['shop_type'];
            }

            $User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
            $User_FavoritesShopModel = new User_FavoritesShopModel();
            $User_FootprintModel = new User_FootprintModel();

            $data['favorites_goods_num'] = $User_FavoritesGoodsModel->getFavoritesGoodsNum($user_id);
            $data['favorites_shop_num'] = $User_FavoritesShopModel->getFavoritesShopNum($user_id);
            $data['footprint_goods_num'] = $User_FootprintModel->getFootprintNum(array('user_id'=> $user_id));

            $data['directseller_is_open'] = Web_ConfigModel::value('Plugin_Directseller');
            //是否开启分销插件，前端判断店铺类型为1
            $data['distribution_is_open'] = Web_ConfigModel::value('Plugin_Distribution');

            //获取用户积分
            $data['points'] = $this->userResourceModel->getOne($user_id);

            //获取当前用户余额信息
            $data['money'] = $this->userInfoModel->getBtInfo();

            //获取用户的订单信息
            $data['order_count'] = $this->userInfoModel->getUserOrderCount($user_id);

            return $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfo()
    {
        $user_id = Perm::$userId;

        $year    = request_int('year');
        $month   = request_int('month');
        $day     = request_int('day');
        $user_qq = request_string('user_qq');
        $user_ww = request_string('user_ww');
        $rows    = request_row('privacy');


        $edit_user_row['user_birthday']   = $year . "-" . $month . "-" . $day;
        $edit_user_row['user_sex']        = request_int('user_sex');
        $edit_user_row['user_realname']   = request_string('user_realname');
        $edit_user_row['user_provinceid'] = request_int('province_id');
        $edit_user_row['user_cityid']     = request_int('city_id');
        $edit_user_row['user_areaid']     = request_int('area_id');
        $edit_user_row['user_area']       = request_string('address_area');
        $edit_user_row['user_qq']         = $user_qq;
        $edit_user_row['user_ww']         = $user_ww;

        //开启事物
        $rs_row = array();
        $this->userInfoModel->sql->startTransactionDb();

        $flagPrivacy = $this->userPrivacyModel->editPrivacy($user_id, $rows);
        check_rs($flagPrivacy, $rs_row);
        $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);
        check_rs($flag, $rs_row);

        $flag = is_ok($rs_row);
        if ($flag !== false && $this->userInfoModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->userInfoModel->sql->rollBackDb();
            $status = 250;
            $msg    = __('failure');

        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     *获取会员头像
     *
     * @access public
     */
    public function getUserImg()
    {

        include $this->view->getView();
    }

    /**
     * 修改会员头像
     *
     * @access public
     */
    public function editUserImg()
    {
        $user_id = Perm::$userId;

        $edit_user_row['user_logo'] = request_string('user_logo');

        $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

        if ($flag === false)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $status = 200;
            $msg    = __('success');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     *获取会员等级
     *
     * @access public
     */
    public function getUserGrade()
    {
        $user_id = Perm::$userId;

        $re       = $this->userInfoModel->getOne($user_id);
        $resource = $this->userResourceModel->getOne($user_id);

        $re['user_growth'] = $resource['user_growth'];

        $user_grade_id = $re['user_grade'];

        $data = $this->userGradeModel->getOne($user_grade_id);

        $data = $this->userGradeModel->getUserExpire($data);

        $gradeList = $this->userGradeModel->getGradeList();

        $data = $this->userGradeModel->getGradeGrowth($data, $gradeList, $re);

        if ('json' == $this->typ)
        {
            $data['gradeList'] = $gradeList;
            $data['re']        = $re;
            $data['resource']  = $resource;
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    /**
     *获取会员标签
     *
     * @access public
     */
    public function tag()
    {
        $user_id              = Perm::$userId;
        $order_row['user_id'] = $user_id;

        $data = $this->userTagRecModel->getTagRecList($order_row);
        $re   = array();

        if ($data['items'])
        {
            $user_tag_ids = array_column($data['items'], 'user_tag_id');

            $tag = array();

            if ($data['items'])
            {
                $tag['user_tag_id:not in'] = $user_tag_ids;
            }

            $tag_row['user_tag_id:in'] = $user_tag_ids;

            $ce = $this->userTagModel->getTagList($tag_row);

            $user_tag = array_column($ce['items'], 'user_tag_id');

            $nameAll = array();
            foreach ($ce['items'] as $key => $val)
            {
                $nameAll[$val['user_tag_id']] = $val;
            }
            foreach ($data['items'] as $key => $val)
            {

                if (in_array($val['user_tag_id'], $user_tag))
                {
                    $data['items'][$key]['user_tag_name'] = $nameAll[$val['user_tag_id']]['user_tag_name'];
                }
            }

            $re = $this->userTagModel->getTagList($tag);
        }
        else
        {
            $re = $this->userTagModel->getTagList();
        }
        if ('json' == $this->typ)
        {
            $data['re'] = $re;
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    /**
     *编辑会员兴趣标签
     *
     * @access public
     */
    public function editTagRec()
    {
        $user_id = Perm::$userId;

        $id_row = array();
        $id_row = request_row('mid');

        $edit_rec_row['user_id']      = $user_id;
        $edit_rec_row['tag_rec_time'] = get_date_time();


        //开启事物
        $rs_row = array();
        $this->userTagRecModel->sql->startTransactionDb();

        $order_row['user_id'] = $user_id;

        $de = $this->userTagRecModel->getTagRecList($order_row);
        if ($de['items'])
        {
            $user_tag = array_column($de['items'], 'tag_rec_id');


            $updata_flag = $this->userTagRecModel->removeRec($user_tag);
            check_rs($updata_flag, $rs_row);
        }
        foreach ($id_row as $v)
        {
            $edit_rec_row['user_tag_id'] = $v;
            $flag                        = $this->userTagRecModel->addRec($edit_rec_row);
            check_rs($flag, $rs_row);
        }


        $flag = is_ok($rs_row);
        if ($flag !== false && $this->userTagRecModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->userTagRecModel->sql->rollBackDb();
            $msg    = __('failure');
            $status = 250;
        }


        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *获取会员地址信息
     *
     * @access public
     */
    public function address()
    {
        $user_id = Perm::$userId;
        $act     = request_string('act');

        //获取一级地址
        $district_parent_id = request_int('pid', 0);
        $Base_DistrictModel  = new Base_DistrictModel();
        $district           = $Base_DistrictModel->getDistrictTree($district_parent_id);

        if ($act == 'edit')
        {
            $userId          = Perm::$userId;
            $user_address_id = request_int('id');


            $data = $this->userAddressModel->getAddressInfo($user_address_id);
        }
        elseif ($act == 'add')
        {
            $userId = Perm::$userId;

            $data = array();
        }
        elseif ($act == 'edit_delivery')
        {
            $userId = Perm::$userId;
            $data   = array();
        }
        else
        {
            $order_row['user_id'] = $user_id;

            $data = $this->userAddressModel->getAddressList($order_row);
        }

        if ("json" == $this->typ)
        {
            //
            $district_id_row = array_merge(
                array_column($data, 'user_address_province_id'),
                array_column($data, 'user_address_city_id'),
                array_column($data, 'user_address_area_id')
            );

            $district_id_row = array_filter($district_id_row);

            if ($district_id_row)
            {
                $district_rows = $Base_DistrictModel = $Base_DistrictModel->getDistrict($district_id_row);

                foreach ($data as $k=>$address_row)
                {
                    $address_row['address_info'] = sprintf('%s %s %s', @$district_rows[$address_row['user_address_province_id']]['district_name'], @$district_rows[$address_row['user_address_city_id']]['district_name'], @$district_rows[$address_row['user_address_area_id']]['district_name']);
                    $data[$k] = $address_row;
                }
            }

            //对APP过来的请求，数组重新处理
            if($_GET['format']=='app'){
                unset($nd);
                foreach($data as $v){
                    $nd[] = $v;
                }
                $data = $nd;
            }

            $data_rows['address_list'] = $data;
            $this->data->addBody(-140, $data_rows);
        }
        else
        {
            include $this->view->getView();
        }

    }

    /**
     *获取用户默认收货地址
     *
     * @access public
     */
    public function getUserConfigAddress()
    {
        $user_id = Perm::$userId;
        $order_row['user_id'] = $user_id;
        $order_row['user_address_default'] = 1;

        $data = $this->userAddressModel->getByWhere($order_row);
        $data = current($data);
        $this->data->addBody(-140, $data);
    }

    /**
     *编辑会员地址信息
     *
     * @access public
     */
    public function editAddressInfo()
    {
        $user_id              = Perm::$userId;
        $user_address_id      = request_int('user_address_id');
        $user_address_contact = request_string('user_address_contact');
        $user_address_area    = request_string('address_area');
        $user_address_address = request_string('user_address_address');
        $user_address_phone   = request_string('user_address_phone');
        $user_address_default = request_string('user_address_default');

        $edit_address_row['user_id']                  = $user_id;
        $edit_address_row['user_address_contact']     = $user_address_contact;
        $edit_address_row['user_address_province_id'] = request_int('province_id');
        $edit_address_row['user_address_city_id']     = request_int('city_id');
        $edit_address_row['user_address_area_id']     = request_int('area_id');
        $edit_address_row['user_address_area']        = $user_address_area;
        $edit_address_row['user_address_address']     = $user_address_address;
        $edit_address_row['user_address_phone']       = $user_address_phone;
        $edit_address_row['user_address_default']     = $user_address_default;
        $edit_address_row['user_address_time']        = get_date_time();

        $map  = Baidu_Map::instance();
        $location = $map -> getGPSByAddress($user_address_area . ' ' .$user_address_address );
        $edit_address_row['lng']  = $location['lng'];
        $edit_address_row['lat']  = $location['lat'];

        if(!$user_address_phone || !Yf_Utils_String::isMobile($user_address_phone)){
            return $this->data->addBody(-140, array(), __('手机号码格式有误'), 250);
        }
        //验证用户
        $cond_row = array(
            'user_id' => $user_id,
            'user_address_id' => $user_address_id,
        );

        $re = $this->userAddressModel->getByWhere($cond_row);

        if (!$re)
        {
            $msg    = __('failure');
            $status = 250;
        }
        else
        {
            //开启事物
            $rs_row = array();
            $this->userAddressModel->sql->startTransactionDb();

            if ($user_address_default == '1')
            {

                $order_row['user_id']              = $user_id;
                $order_row['user_address_default'] = '1';
                $de                                = $this->userAddressModel->getAddressList($order_row);

                if (!empty($de))
                {
                    $updata_flag = $this->userAddressModel->editAddressInfo($de);
                    check_rs($updata_flag, $rs_row);
                }
            }


            $flag = $this->userAddressModel->editAddress($user_address_id, $edit_address_row);

            check_rs($flag, $rs_row);

            $flag = is_ok($rs_row);
            if ($flag !== false && $this->userAddressModel->sql->commitDb())
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {
                $this->userAddressModel->sql->rollBackDb();
                $msg    = __('failure');
                $status = 250;
            }

            $edit_address_row['user_address_id'] = $user_address_id;
            $data                                = $edit_address_row;
            $this->data->addBody(-140, $data, $msg, $status);
        }

    }

    /**
     *增加会员地址信息
     *
     * @access public
     */
    public function addAddressInfo()
    {
        $user_id = Perm::$userId;

        $user_address_contact = request_string('user_address_contact');
        $user_address_area    = request_string('address_area');
        $user_address_address = request_string('user_address_address');
        $user_address_phone   = request_string('user_address_phone');
        $user_address_default = request_string('user_address_default');
        if(!$user_address_phone || !Yf_Utils_String::isMobile($user_address_phone)){
            return $this->data->addBody(-140, array(), __('手机号码格式有误'), 250);
        }


        $edit_address_row['user_id']                  = $user_id;
        $edit_address_row['user_address_contact']     = $user_address_contact;
        $edit_address_row['user_address_province_id'] = request_int('province_id');
        $edit_address_row['user_address_city_id']     = request_int('city_id');
        $edit_address_row['user_address_area_id']     = request_int('area_id');
        $edit_address_row['user_address_area']        = $user_address_area;
        $edit_address_row['user_address_address']     = $user_address_address;
        $edit_address_row['user_address_phone']       = $user_address_phone;
        $edit_address_row['user_address_default']     = $user_address_default;
        $edit_address_row['user_address_time']        = get_date_time();

        $map  = Baidu_Map::instance();
        $location = $map -> getGPSByAddress($user_address_area . ' ' .$user_address_address );
        $edit_address_row['lng']  = $location['lng'];
        $edit_address_row['lat']  = $location['lat'];

        $cond_row['user_id'] = $user_id;

        $re = $this->userAddressModel->getCount($cond_row);

        if ($re > 19)
        {

            $status = 250;
            $msg    = __('failure');

        }
        else
        {

            //开启事物
            $rs_row = array();
            $this->userAddressModel->sql->startTransactionDb();

            //判断是否设默认，默认改变前面的状态
            if ($user_address_default == '1')
            {

                $order_row['user_id']              = $user_id;
                $order_row['user_address_default'] = '1';
                $de                                = $this->userAddressModel->getAddressList($order_row);

                if (!empty($de))
                {
                    $updata_flag = $this->userAddressModel->editAddressInfo($de);
                }
            }
            check_rs($updata_flag, $rs_row);
            $flag = $this->userAddressModel->addAddress($edit_address_row, true);
            $addess_id = $flag;
            fb($flag);
            check_rs($flag, $rs_row);
            $flag = is_ok($rs_row);
            if ($flag !== false && $this->userAddressModel->sql->commitDb())
            {
                $edit_address_row['user_address_id'] = $addess_id;
                $status                              = 200;
                $msg                                 = __('success');
            }
            else
            {
                $this->userAddressModel->sql->rollBackDb();

                $status = 250;
                $msg    = __('failure');
            }
        }

        $data = $edit_address_row;
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *删除会员地址信息
     *
     * @access public
     */
    public function delAddress()
    {
        $user_id         = Perm::$row['user_id'];
        $user_address_id = request_string('id');
        if('json' == request_string('typ'))
        {
            $user_address_id = request_int('id');
        }

        //验证用户
        $cond_row = array(
            'user_id' => $user_id,
            'user_address_id' => $user_address_id
        );
        $re       = $this->userAddressModel->getByWhere($cond_row);

        if ($re)
        {
            $flag = $this->userAddressModel->removeAddress($user_address_id);
        }
        else
        {
            $flag = false;
        }

        if ($flag !== false)
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *查找好友
     *
     * @access public
     */
    public function friend()
    {
        $user_id = Perm::$userId;
        $act     = request_string('op');

        $user_name = request_string("searchname");

        if ($act == 'follow')
        {
            $cond_row['user_id'] = $user_id;
            $order_row           = array('friend_addtime' => 'DESC');

            $Yf_Page           = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):30;
            $rows              = $Yf_Page->listRows;
            $offset            = request_int('firstRow', 0);
            $page              = ceil_r($offset / $rows);

            $friend_list = $this->userFriendModel->getFriendAllDetail($cond_row, $order_row, $page, $rows);

            $Yf_Page->totalRows = $friend_list['totalsize'];
            $page_nav           = $Yf_Page->prompt();


            $data                = array();
            $data['friend_list'] = $friend_list;
            $this->data->addBody(-140, $data);

            $this->view->setMet('follow');


        }
        elseif ($act == 'fan')
        {
            $cond_row['friend_id'] = $user_id;
            $order_row             = array('friend_addtime' => 'DESC');

            $Yf_Page           = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):30;
            $rows              = $Yf_Page->listRows;
            $offset            = request_int('firstRow', 0);
            $page              = ceil_r($offset / $rows);

            $friend_list = $this->userFriendModel->getBeFriendAllDetail($cond_row, $order_row, $page, $rows);

            $Yf_Page->totalRows = $friend_list['totalsize'];
            $page_nav           = $Yf_Page->prompt();


            $data                = array();
            $data['friend_list'] = $friend_list;
            $this->view->setMet('fan');
        }
        else
        {

            if ($user_name)
            {
                $type            = 'user_name:LIKE';
                $cond_row[$type] = '%' . $user_name . '%';
                $order_row       = array();

                $Yf_Page           = new Yf_Page();
                $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):30;
                $rows              = $Yf_Page->listRows;
                $offset            = request_int('firstRow', 0);
                $page              = ceil_r($offset / $rows);

                $cond_row['user_id:!='] = $user_id;

                $user_list = $this->userInfoModel->getInfoList($cond_row, $order_row, $page, $rows);

                $friend_row                 = array();
                $friend_row['friend_id:IN'] = array_column($user_list['items'], 'user_id');

                $friend_row['user_id'] = $user_id;

                $friend_list = $this->userFriendModel->getFriendAll($friend_row);

                $friend_id = array();
                $friend_id = array_column($friend_list, 'friend_id');

                //获取已经加好友的
                foreach ($user_list['items'] as $key => $val)
                {

                    if (in_array($val['user_id'], $friend_id))
                    {
                        $user_list['items'][$key]['status'] = 1;
                    }
                    else
                    {
                        $user_list['items'][$key]['status'] = 0;
                    }
                }

                $Yf_Page->totalRows = $user_list['totalsize'];
                $page_nav           = $Yf_Page->prompt();

                $data['user_list'] = $user_list;
            }
            else
            {
                //推荐标签列表
                $cond_row['user_tag_recommend'] = 1;
                $sort                           = array('user_tag_sort' => 'DESC');

                $data = $this->userTagModel->getTagList($cond_row, $sort, 1, 10);

                if (!empty($data['items']))
                {
                    foreach ($data['items'] as $key => $val)
                    {
                        $Yf_Page           = new Yf_Page();
                        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
                        $rows              = $Yf_Page->listRows;
                        $offset            = request_int('firstRow', 0);
                        $page              = ceil_r($offset / $rows);

                        //查询已经是好友的，排除掉
                        $friend_row['user_id'] = $user_id;

                        $friend_list = $this->userFriendModel->getFriendAll($friend_row);

                        $user = array();

                        $user = array_column($friend_list, 'friend_id');

                        array_push($user, $user_id);
                        $tag_row['user_id:not in'] = $user;
                        $tag_row['user_tag_id']    = $val['user_tag_id'];
                        $order_row                 = array();

                        $tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row, $page, $rows);
                        $users_id = array();
                        if($tag['items']){

                            $users_id = array_column($tag['items'], 'user_id');
                            $detail   = $this->userInfoModel->getInfo($users_id);
                        }
                        //标签下除好友的总会员个数
                        $count                        = count($tag['items']);
                        $data['items'][$key]['count'] = $count;

                        foreach ($tag['items'] as $k => $v)
                        {
                            if(in_array($v['user_id'],$users_id)){
                                $tag['items'][$k]['detail'] = $detail[$v['user_id']];
                            }
                        }
                        $data['items'][$key]['user'] = $tag;
                    }
                }

            }

        }
        if ('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }

    }

    /**
     *关注标签下面没有关注的会员
     *
     * @access public
     */
    public function addFriends()
    {
        $user_id = Perm::$userId;

        $cond_row['user_id']        = $user_id;
        $cond_row['friend_addtime'] = get_date_time();
        //查询已经是好友的，排除掉
        $friend_row['user_id'] = $user_id;

        $friend_list = $this->userFriendModel->getFriendAll($friend_row);

        $user = array();
        $user = array_column($friend_list, 'friend_id');

        array_push($user, $user_id);
        $tag_row['user_id:not in'] = $user;
        $tag_row['user_tag_id']    = request_int('id');
        $order_row                 = array();

        $tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row);

        //开启事物
        $rs_row = array();
        $this->userFriendModel->sql->startTransactionDb();

        foreach ($tag['items'] as $key => $val)
        {
            $user_id = $val['user_id'];
            $detail  = $this->userInfoModel->getInfo($user_id);
            $detail  = $detail[$user_id];

            $cond_row['friend_id']    = $val['user_id'];
            $cond_row['friend_name']  = $detail['user_name'];
            $cond_row['friend_image'] = $detail['user_logo'];

            $flag = $this->userFriendModel->addFriend($cond_row);
            check_rs($flag, $rs_row);
        }

        $flag = is_ok($rs_row);
        if ($flag !== false && $this->userFriendModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->userFriendModel->sql->rollBackDb();
            $msg    = __('failure');
            $status = 250;
        }


        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *关注一个会员
     *
     * @access public
     */
    public function addFriendDetail()
    {
        $user_id = Perm::$userId;

        $cond_row['user_id']        = $user_id;
        $cond_row['friend_addtime'] = get_date_time();

        $userId = request_int('id');
        $detail = $this->userInfoModel->getOne($userId);
        if (!$detail)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {

            $cond_row['friend_id']    = $userId;
            $cond_row['friend_name']  = $detail['user_name'];
            $cond_row['friend_image'] = $detail['user_logo'];

            $flag = $this->userFriendModel->addFriend($cond_row);

            if ($flag !== false)
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {
                $msg    = __('failure');
                $status = 250;
            }
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *取消一个会员
     *
     * @access public
     */
    public function cancelFriendDetail()
    {
        $user_id = Perm::$userId;

        $user_friend_id = request_int('id');

        $cond_row['user_id']        = $user_id;
        $cond_row['user_friend_id'] = $user_friend_id;

        $de = $this->userFriendModel->getFriendInfo($cond_row);

        if (!$de)
        {

            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $flag = $this->userFriendModel->removeFriend($user_friend_id);

            if ($flag !== false)
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {

                $msg    = __('failure');
                $status = 250;
            }
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 密码修改
     *
     * @access public
     */
    public function passwd()
    {

        $url = Yf_Registry::get('ucenter_api_url') . '?ctl=User&met=passwd';
        location_to($url);
        die();
    }
    /**
     *获取会员安全信息
     *
     * @access public
     */
    public function security()
    {

        $url = Yf_Registry::get('ucenter_api_url') . '?ctl=User&met=security';
        location_to($url);
        die();


        $user_id = Perm::$userId;
        $op      = request_string('op');

        $user_id = Perm::$userId;

        $data = $this->userInfoModel->getInfo($user_id);
        $data = $data[$user_id];

        if ($op == 'email')
        {
            $this->view->setMet('email');
        }
        elseif ($op == 'mobile')
        {
            $this->view->setMet('mobile');
        }
        elseif ($op == 'mobiles')
        {
            $name = __('手机');
            $this->view->setMet('security_identity');
        }
        elseif ($op == 'emails')
        {
            $name = __('邮箱');
            $this->view->setMet('security_identity');
        }

        if ('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    //手机绑定验证
    public function getMobile()
    {
        $user_id                 = Perm::$userId;
        $cond_row['user_mobile'] = request_string('verify_field');
        $cond_row['user_id:!=']  = $user_id;

        $de = $this->userInfoModel->getUserInfo($cond_row);

        if($de)
        {

            $msg    = __('failure');
            $status = 250;
        }
        else
        {

            $status = 200;
            $msg    = __('success');

        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    //生成验证码,并且发送验证码
    public function getMobileYzm()
    {

        $mobile = request_string('mobile');
        $user_id                 = Perm::$userId;
        $cond_row['user_mobile'] = $mobile;
        $cond_row['user_id:!=']  = $user_id;

        $data   = array('sms_time'=>60);
        //检测该手机号是否已被其他用户绑定
        $ce = $this->userInfoModel->getUserInfo($cond_row);

        if($ce)
        {
            $msg    = __('该手机号已被其他会员绑定，请更换其他手机号');
            $status = 250;
        }else
        {
            $code_cond_row['code'] = 'verification';

            $de = $this->messageTemplateModel->getTemplateDetail($code_cond_row);

            $msg = $de['content_phone'];

            $code_key = $mobile;
            $code     = VerifyCode::getCode($code_key);
            $msg       = str_replace("[weburl_name]", $this->web['web_name'], $msg);
            $msg       = str_replace("[yzm]", $code, $msg);

            $str = Sms::send($mobile, $msg);

            $data['code'] = $code;

            $status = 200;
//			$msg = "success";
        }


        $this->data->addBody(-140, $data, $msg, $status);

    }

    //检测验证码
    public function checkMobileYzm()
    {

        $yzm    = request_string('yzm');
        $mobile = request_string('mobile');

        if (VerifyCode::checkCode($mobile, $yzm))
        {

            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $msg    = __('failure');
            $status = 250;

        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //绑定手机
    public function editMobileInfo()
    {
        $user_id                             = Perm::$userId;
        $user_mobile        				 = request_string('user_mobile');
        $yzm             					 = request_string('yzm');

        fb(VerifyCode::checkCode($user_mobile, $yzm));
        fb($user_id);
        fb($yzm);
        if(!VerifyCode::checkCode($user_mobile, $yzm)){
            $msg    = __('failure');
            $status = 240;
        }else{
            $edit_user_row['user_mobile']        = $user_mobile;
            $edit_user_row['user_mobile_verify'] = 1;

            $de = $this->userInfoModel->getOne($user_id);
            if(!$de){
                $msg    = __('failure');
                $status = 250;
            }else{
                $user_level_id = 1;

                if ($de['user_email_verify'])
                {
                    $user_level_id = $user_level_id + 1;
                }

                $edit_user_row['user_level_id'] = $user_level_id + 1;

                $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

                if ($flag == false)
                {
                    $msg    = __('failure');
                    $status = 250;

                }
                else
                {

                    $status = 200;
                    $msg    = __('success');
                }
            }
        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //邮箱绑定验证
    public function getEmail()
    {
        $user_id                = Perm::$userId;
        $cond_row['user_email'] = request_string('verify_field');
        $cond_row['user_id:!='] = $user_id;

        $de = $this->userInfoModel->getUserInfo($cond_row);

        if ($de)
        {

            $msg    = __('failure');
            $status = 250;
        }
        else
        {

            $status = 200;
            $msg    = __('success');

        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    //邮箱生成验证码,并且发送验证码
    public function getEmailYzm()
    {

        $email = request_string('email');
        $user_id                 = Perm::$userId;
        $cond_row['user_email'] = $email;
        $cond_row['user_id:!=']  = $user_id;

        $ce = $this->userInfoModel->getUserInfo($cond_row);

        if($ce)
        {
            $msg    = __('failure');
            $status = 250;
        }else
        {
            $cond_row['code'] = 'verification';

            $de = $this->messageTemplateModel->getTemplateDetail($cond_row);

            $me    = $de['content_email'];
            $title = $de['title'];

            $code_key = $email;
            $code     = VerifyCode::getCode($code_key);
            $me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
            $me       = str_replace("[yzm]", $code, $me);
            $title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

            $str = Email::sendMail($email, Perm::$row['user_account'], $title, $me);

            $status = 200;
            $msg    = 'success';
        }
        $data   = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    //邮箱检测验证码
    public function checkEmailYzm()
    {

        $yzm   = request_string('yzm');
        $email = request_string('email');

        if (VerifyCode::checkCode($email, $yzm))
        {

            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $msg    = __('failure');
            $status = 250;

        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //绑定邮箱
    public function editEmailInfo()
    {
        $user_id                            = Perm::$userId;
        $user_email        				    = request_string('user_email');
        $yzm             					= request_string('yzm');
        if(!VerifyCode::checkCode($user_email, $yzm)){
            $msg    = __('failure');
            $status = 240;
        }else{
            $edit_user_row['user_email']        = request_string('user_email');
            $edit_user_row['user_email_verify'] = 1;

            $de = $this->userInfoModel->getOne($user_id);

            $user_level_id = 1;

            if ($de['user_mobile_verify'] == 1)
            {

                $user_level_id = $user_level_id + 1;
            }

            $edit_user_row['user_level_id'] = $user_level_id + 1;

            $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

            if ($flag == false)
            {
                $msg    = __('failure');
                $status = 250;
            }
            else
            {
                $status = 200;
                $msg    = __('success');
            }
        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //解除绑定,生成验证码,并且发送验证码
    public function getYzm()
    {

        $type = request_string('type');
        $val = request_string('val');

        $cond_row['code'] = 'Lift verification';

        $de = $this->messageTemplateModel->getTemplateDetail($cond_row);
        if($type == 'mobile'){
            $me = $de['content_phone'];

            $code_key = $val;
            $code     = VerifyCode::getCode($code_key);
            $me       = str_replace("[weburl_name]", $this->web['web_name'], $me);
            $me       = str_replace("[yzm]", $code, $me);

            $str = Sms::send($val, $me);
        }else{
            $me    = $de['content_email'];
            $title = $de['title'];

            $code_key = $val;
            $code     = VerifyCode::getCode($code_key);
            $me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
            $me       = str_replace("[yzm]", $code, $me);
            $title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

            $str = Email::sendMail($val, Perm::$row['user_account'], $title, $me);
        }
        $status = 200;
        $data   = array();
        $msg = "success";
        $this->data->addBody(-140, $data, $msg, $status);

    }

    //检测解除验证码
    public function checkYzm()
    {

        $yzm    = request_string('yzm');
        $type   = request_string('type');
        $val    = request_string('val');

        if (VerifyCode::checkCode($val, $yzm))
        {

            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $msg    = __('failure');
            $status = 250;

        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //解除绑定
    public function editAllInfo()
    {
        $type      = request_string('type');
        $yzm       = request_string('yzm');
        $val       = request_string('val');
        $user_id   = Perm::$userId;

        if(!VerifyCode::checkCode($val, $yzm))
        {
            $msg    = __('failure');
            $status = 240;
        }else
        {
            if($type == 'mobile'){
                $edit_user_row['user_mobile']    = '';
                $edit_user_row['user_mobile_verify'] = 0;
            }else{
                $edit_user_row['user_email']    = '';
                $edit_user_row['user_email_verify'] = 0;
            }


            $de = $this->userInfoModel->getOne($user_id);

            $user_level_id = $de['user_level_id']*1;

            $edit_user_row['user_level_id'] = $user_level_id-1;

            $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

            if ($flag == false)
            {
                $msg    = __('failure');
                $status = 250;

            }
            else
            {

                $status = 200;
                $msg    = __('success');
            }
        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    //显示子账号设置
    public function getSubUser()
    {
        $user_id = Perm::$userId;
        $User_SubUserModel = new User_SubUserModel();

        $cond_row['user_id'] = $user_id;
        $order_row           = array();

        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):30;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);

        $sub_user_list = $User_SubUserModel->getSubUserList($cond_row, $order_row, $page, $rows);

        $Yf_Page->totalRows = $sub_user_list['totalsize'];
        $page_nav           = $Yf_Page->prompt();


        $data                = array();
        $data = $sub_user_list;

        if ('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    public function subUser()
    {
        $id = request_int('sub_user_id');
        $User_SubUserModel = new User_SubUserModel();
        $User_InfoModel = new User_InfoModel();

        if($id)
        {
            $data = $User_SubUserModel->getOne($id);
            //查找用户信息
            $user_info = $User_InfoModel->getUserInfo(array('user_id'=>$id));
            $data['user_name'] = $user_info['user_name'];
        }

        if ('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    //添加及编辑关联子账号
    public function editSubUser()
    {
        $id = request_int('sub_user_id');
        $user_name = request_string('user_name');
        $sub_user_active = request_string('sub_user_active');

        $user_id = Perm::$userId;
        $User_SubUserModel = new User_SubUserModel();
        $User_InfoModel = new User_InfoModel();

        //开启事务
        $User_SubUserModel->sql->startTransactionDb();

        $flag = false;
        $msg = '';
        //存在id表示：编辑，不存在id表示：新增
        if($id)
        {
            //验证修改用户是否是当前用户的关联子账户
            $array = array();
            $array['sub_user_id'] = $id;
            $array['user_id'] = $user_id;
            $sub_user = $User_SubUserModel->getByWhere($array);

            if($sub_user)
            {
                $edit_row = array();
                $rs_row = array();
                $edit_row['sub_user_active']  = $sub_user_active;
                $edit_flag = $User_SubUserModel->editSub($id,$edit_row);
                check_rs($edit_flag,$rs_row);
                $flag = is_ok($rs_row);
            }
            else
            {
                $flag = false;
                $msg    = __('该用户不是您的子账号');
            }
        }
        else
        {
            if($user_name)
            {
                //判断user_name是否已经是其他用户的子账号
                $user_info = $User_InfoModel->getUserInfo(array('user_name'=>$user_name));
                if($user_info)
                {
                    $sub_user = $User_SubUserModel->getOne($user_info['user_id']);
                    if($sub_user)
                    {
                        $flag = false;
                        $msg    = __('该用户已绑定主管账号');
                    }
                    else
                    {
                        $add_row = array();
                        $add_row['sub_user_id'] = $user_info['user_id'];
                        $add_row['user_id'] = $user_id;
                        $add_row['sub_user_active'] = $sub_user_active;

                        $flag = $User_SubUserModel->addSub($add_row);
                    }

                }
                else
                {
                    $flag = false;
                    $msg    = __('该用户不存在');
                }
            }else
            {
                $flag = false;
                $msg    = __('用户名不能为空');
            }

        }
        $data = array();

        if($flag && $User_SubUserModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $User_SubUserModel->sql->rollBackDb();
            $msg    = $msg ? $msg : __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function delSubUser()
    {
        $id = request_int('sub_user_id');

        $user_id = Perm::$userId;
        $User_SubUserModel = new User_SubUserModel();

        //开启事务
        $User_SubUserModel->sql->startTransactionDb();

        $array = array();
        $array['sub_user_id'] = $id;
        $array['user_id'] = $user_id;
        $sub_user = $User_SubUserModel->getByWhere($array);

        //判断要解绑的用户是否是当前用户的子账户
        $msg = '';
        if($sub_user)
        {
            $flag = $User_SubUserModel->removeSub($id);
        }
        else
        {
            $flag = false;
            $msg    = __('该用户不是您的子账号');
        }

        if($flag && $User_SubUserModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $User_SubUserModel->sql->rollBackDb();
            $msg    = $msg ? $msg : __('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 账单明细
     */
    public function getRecordListForWap()
    {
//        $Yf_Page           = new Yf_Page();
//        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
//        $rows              = $Yf_Page->listRows;
//        $offset            = request_int('firstRow', 0);
//        $page              = ceil_r($offset / $rows);

        $page            = request_int('curpage', 0);
        $rows            = request_int('page', 10);

        $data['page'] = $page;
        $data['rows'] = $rows;
        $data['user_id'] = request_string('user_id');
        $data['type_id'] = request_string('type');
        $data['is_deliver'] = request_int('is_deliver');
        $rs = $this->getPaycenterApi($data, 'Api_Paycen_PayRecord', 'getRecordListForWap');
        $rs['data']['type_id'] = request_string('type');

        $this->data->addBody(-140, $rs['data'], $rs['msg'], $rs['status']);
    }

    //获取线下人员
    public function getUserChildren()
    {
        $User_InfoModel = new User_InfoModel();

        $level    = request_string('level', '1');
        $page    = request_int('curpage', 1);
        $rows    = request_int('page', 20);
        $cond_row['user_parent_id'] = Perm::$userId;
        $cond_row['user_grade'] = $level;
        $order_row['user_statu'] = 'asc';
        $order_row['user_regtime'] = 'asc';
        $data = $User_InfoModel->getInfoList($cond_row, $order_row, $page, $rows);

        $this->data->addBody(-140, $data);
    }

    /**
     * 总返利金额
     */
    public function getRecordAmountByUserId()
    {
        $data['user_id'] = request_string('user_id');
        $data['trade_type_id'] = request_row('type');
        $data['user_type'] = 1;
        $data['status'] = 2;
        $rs = $this->getPaycenterApi($data, 'Api_Paycen_PayRecord', 'getRecordAmountByUserId');

        $User_InfoModel = new User_InfoModel();
        $user_info = $User_InfoModel->getOne($data['user_id']);
        $rs['data']['user_grade'] = $user_info['user_grade'];
        $rs['data']['self_user_id'] = Web_ConfigModel::value('self_user_id');
        $this->data->addBody(-140, $rs['data'], $rs['msg'], $rs['status']);
    }

    //获取返利记录
    public function getProfit()
    {
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);
        $status            = request_int('status', '');

        $formvars = array();
        $formvars['page'] = $page;
        $formvars['rows'] = $rows;
        $formvars['user_id'] = Perm::$userId;
        $formvars['trade_type_id'] = request_int('type', 15);//订单差价返利
        $formvars['user_type'] = 1;
        $formvars['status'] = $status;

        $rs = $this->getPaycenterApi($formvars, 'Api_Paycen_PayRecord', 'getRecordListByUserId');

        $formvars['status'] = 2;
        $rs1 = $this->getPaycenterApi($formvars, 'Api_Paycen_PayRecord', 'getRecordAmountByUserId');
        $rs['data']['amount'] = $rs1['data']['amount'];

        $this->data->addBody(-140, $rs['data'], $rs['msg'], $rs['status']);
    }


    public function certificationForWap(){
        $data['user_id']=request_string('user_id');
        $rs = $this->getPaycenterApi($data, 'Api_User_Info', 'certificationForWap');
        $UserInfoModel = new User_InfoModel();
        $rs['data']['user_name']=$UserInfoModel->getOne($data['user_id'])['user_name'];
        if($rs['status']==200){
            $status  = 200;
            $msg     = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }
        $data = array();
        return $this->data->addBody(-140, $rs['data'], $msg, $status);

    }
    public function editCertificationForWap(){
        $user_id = request_string('user_id');
        $edit['user_realname'] = request_string('realname');
        $edit['user_identity_card'] = request_string('idcardno');
        $edit['user_identity_type'] = request_string('type');
//        $existUser=$User_InfoModel->getByWhere(array('user_identity_card'=>$edit['user_identity_card']));
//        if(count($existUser)>0 && current($existUser)['user_id']!=$user_id){
//            return $this->data->addBody(-140, array(), __('身份证号已存在'), 250);
//        }
        $edit['user_identity_font_logo'] = request_string('font_logo');
        $edit['user_identity_face_logo'] = request_string('face_logo');
        $edit['user_identity_start_time'] = request_string('startDate');
        $edit['user_identity_end_time'] = request_string('endDate');
        $edit['user_identity_statu'] = 1;
        $edit['user_id']=request_string('user_id');
        $rs = $this->getPaycenterApi($edit, 'Api_User_Info', 'editCertificationForWap');

        //更新shop
        $UserInfoModel = new User_InfoModel();
        $edit_row['user_realname']=request_string('realname');
        $UserInfoModel->editInfo($user_id,$edit_row);

        //更新Ucenter
        $formvars            = $edit_row;
        $formvars['user_id']  = $user_id;
        $rs_ucenter = $this->getUcenterApi($formvars, 'Api_User', 'updateUserInfoForWap');
        if($rs['status']==200){
            $status  = 200;
            $msg     = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }
        $data = array();
        return $this->data->addBody(-140, $data, $msg, $status);

    }
    public function isExistIdentity()
    {
        $user['user_id'] = request_string('user_id');
        $user['user_identity_card'] = request_string('idcardno');
        $user['user_identity_type'] = request_string('type');
        $formvars['us'] = request_int('u');
        $formvars['ks']       = request_string('k');
        $rs_check = $this->getPaycenterApi($formvars, 'Login', 'check');
        $rs = $this->getPaycenterApi($user, 'Api_User_Info', 'isExistIdentity');
        if($rs['status']==200){
            $status  = 200;
            $msg     = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }
        return $this->data->addBody(-140, $rs['data']);
    }

    public function updateUserInfo()
    {
        $user_id = request_string('user_id');
        $user_nickname = request_string('user_name');
        $user_birthday = request_string('user_birthday');
        $user_logo=request_string('user_logo');

        if($user_nickname){
            $edit['user_nickname']     = $user_nickname;
        }
        if($user_birthday){
            $edit['user_birthday']     = $user_birthday;
        }
        if($user_logo){
            $edit['user_logo']     = $user_logo;
        }

        //ucenter 个人信息下都需要去更改
        $this->userInfoModel->sql->startTransactionDb();
        $formvars            = $edit;
        $formvars['user_id']  = $user_id;
        $rs = $this->getUcenterApi($formvars, 'Api_User', 'updateUserInfoForWap');

        //paycenter 更改头像昵称
        if($user_logo || $user_nickname ) {
            unset($formvars['app_id']);
            $pay_rs = $this->getPaycenterApi($formvars, 'Api_User_Info', 'updateUserInfoForWap');
        }
        //webpos更改
        if($user_birthday){
            $web_pos_rs=$this->userInfoModel->updateWebpos($user_id,$user_birthday,'');
        }
        //shop更改
        if($user_logo || $user_birthday) {
            $flag = $this->userInfoModel->editInfo($user_id, $edit);
        }

        if ($rs['status']==200){
            $this->userInfoModel->sql->commitDb();
            $status                              = 200;
            $msg                                 = __('success');
        }
        else{
            $this->userInfoModel->sql->rollBackDb();
            $status = 250;
            $msg    = __('failure');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    public function addDepositForWap(){
        $data['deposit_amount'] = request_string('deposit_amount');
        $data['user_id'] = request_string('user_id');
        $data['user_account'] = request_string('user_account');

        $rs = $this->getPaycenterApi($data, 'Api_User_Info', 'addDeposit');
        $this->data->addBody(-140, $rs['data'], $rs['msg'], $rs['status']);
    }

    /**
     * 修改支付密码
     */
    public function editUserPayPassword()
    {
        $data['user_id'] = request_string('user_id');
        $data['password'] = request_string('password');
        $rs = $this->getPaycenterApi($data, 'Api_User_Info', 'editUserPayPassword');

        $this->data->addBody(-140, $rs['data'], $rs['msg'], $rs['status']);
    }

    /**
     * 个人仓库管理
     */
    public function stock_check()
    {
        $typ = request_string('typ');
        if($typ == 'e'){
            include $this->view->getView();
        }else{
            $real_stock_list = request_string('real_stock_list');
            $real_stock_list = decode_json($real_stock_list);

            $Stock_CheckModel = new Stock_CheckModel();
            $Stock_CheckGoodsModel = new Stock_CheckGoodsModel();

            //如果是高级合伙人
            $user_id = request_int('user_id');
            if($user_id != Web_ConfigModel::value('self_user_id')) {
                $User_Stock_Model = new User_StockModel();

                $cond_row['user_id'] = $user_id;
                $goods_stock_list = $User_Stock_Model->getByWhere($cond_row);
            }else{
                $Goods_CommonModel = new Goods_CommonModel();
                $Goods_BaseModel = new Goods_BaseModel();

                $cond_row['shop_id'] = Web_ConfigModel::value('self_shop_id');
                $goods_stock_list = $Goods_BaseModel->getByWhere($cond_row);
            }

            $Stock_CheckModel->sql->startTransactionDb();
            $rs_row = array();
            //添加盘点记录表
            $add_row = array();
            $add_row['user_id'] = $user_id;
            $add_row['user_name'] = '';
            $add_row['check_date'] = date('Y-m-d');
            $add_row['check_date_time'] = get_date_time();
            $check_id = $Stock_CheckModel->addCheck($add_row, true);
            check_rs($check_id, $rs_row);

            try {
                foreach ($goods_stock_list as $stock_id => $goods_stock) {
                    $real_num = isset($real_stock_list[$stock_id]) ? $real_stock_list[$stock_id] : $goods_stock['goods_stock'];
                    //添加盘点明细表
                    $add_goods_row = array();
                    $add_goods_row['check_id'] = $check_id;
                    $add_goods_row['user_id'] = $user_id;
                    $add_goods_row['goods_id'] = $goods_stock['goods_id'];
                    $add_goods_row['common_id'] = $goods_stock['common_id'];
                    $add_goods_row['goods_name'] = $goods_stock['goods_name'];
                    $add_goods_row['goods_stock'] = $goods_stock['goods_stock'];
                    $add_goods_row['real_goods_stock'] = $real_num;
                    //根据实际数据与库存数量相比，得出盘盈/盘亏/账实相符
                    if($real_num > $goods_stock['goods_stock']){
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_SURPLUS;
                    }else if($real_num < $goods_stock['goods_stock']){
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_LOSSES;
                    }else{
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_NORMAL;
                    }
                    $add_goods_row['check_date_time'] = get_date_time();
                    $add_flag = $Stock_CheckGoodsModel->addCheckGoods($add_goods_row);
                    check_rs($add_flag, $rs_row);

                    if($user_id != Web_ConfigModel::value('self_user_id')) {
                        //修改商品库存
                        if ($real_num != $goods_stock['goods_stock']) {
                            $edit_row = array();
                            $edit_row['goods_stock'] = $real_num;
                            $edit_flag = $User_Stock_Model->editUserStock($stock_id, $edit_row);
                            check_rs($edit_flag, $rs_row);
                        }
                    }else{
                        //修改店铺商品库存
                        if ($real_num != $goods_stock['goods_stock']) {
                            $edit_row = array();
                            $edit_row['goods_stock'] = $real_num;
                            $edit_flag = $Goods_BaseModel->editBase($stock_id, $edit_row,false);
                            check_rs($edit_flag, $rs_row);

                            $edit_row2 = array();
                            $edit_row2['common_stock'] = $real_num;
                            $edit_flag2 = $Goods_CommonModel->editCommon($goods_stock['common_id'], $edit_row2);
                            check_rs($edit_flag2, $rs_row);
                        }
                    }
                }
            }catch (Exception $e){
                $Stock_CheckModel->sql->rollBackDb();
                $msg =  __('failure');
                $status = 250;
            }

            if(is_ok($rs_row) && $Stock_CheckModel->sql->commitDb()){
                $msg =  __('success');
                $status = 200;
            }else{
                $Stock_CheckModel->sql->rollBackDb();
                $m = $Stock_CheckModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }

    public function stock_goods()
    {
        $page    = request_int('page', 1);
        $rows    = request_int('rows', 20);
        $goods_key = request_string('goods_key', '');

        $user_id = request_int('user_id');
        if($user_id != Web_ConfigModel::value('self_user_id')) {
            $User_Stock_Model = new User_StockModel();
            $cond_row['user_id'] = $user_id;
            if (!empty($goods_key)) {
                $cond_row['goods_name:like'] = '%' . $goods_key . '%';
            }
            $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
            $goods = $User_Stock_Model->getUserStockList($cond_row, $order_row, $page, $rows);

            $this->data->addBody(-140, $goods);
        }else{
            $cront_row = array(
                'shop_id' => Web_ConfigModel::value('self_shop_id'),
                'common_state' => Goods_CommonModel::GOODS_STATE_NORMAL,
                'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW
            );
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row['common_name:like'] = '%' . $goods_key . '%';
            }
            $order_row['CONVERT(common_name USING gbk)'] = 'asc';
            $Goods_CommonModel = new Goods_CommonModel();
            $Goods_BaseModel = new Goods_BaseModel();
            $goods_common_list = $Goods_CommonModel->listByWhere($cront_row, $order_row, $page, $rows, true);
            foreach($goods_common_list['items'] as $key=>$common){
                $goods_base = $Goods_BaseModel->getOneByWhere(['common_id'=>$common['common_id']]);

                $goods_common_list['items'][$key]['stock_id'] = $goods_base['goods_id'];
                $goods_common_list['items'][$key]['goods_id'] = $goods_base['goods_id'];
                $goods_common_list['items'][$key]['goods_name'] = $goods_base['goods_name'];
                $goods_common_list['items'][$key]['goods_stock'] = $goods_base['goods_stock'];
            }

            $this->data->addBody(-140, $goods_common_list);
        }

    }

    public function stock_self_use()
    {
        $typ = request_string('typ');
        if($typ == 'e'){
            include $this->view->getView();
        }else{
            $out_num_list = request_string('out_num_list');
            $out_num_list = decode_json($out_num_list);

            $User_StockOutModel = new User_StockOutModel();

            //如果是高级合伙人
            $user_id = request_int('user_id');
            if($user_id != Web_ConfigModel::value('self_user_id')) {
                $User_Stock_Model = new User_StockModel();

                $cond_row = array();
                $cond_row['user_id'] = $user_id;
                $goods_stock_list = $User_Stock_Model->getByWhere($cond_row);
            }else{
                $Goods_CommonModel = new Goods_CommonModel();
                $Goods_BaseModel = new Goods_BaseModel();

                $cond_row['shop_id'] = Web_ConfigModel::value('self_shop_id');
                $goods_stock_list = $Goods_BaseModel->getByWhere($cond_row);
            }

            $prefix = sprintf('%s-%s-%s', Yf_Registry::get('shop_app_id'), date('Ymd'), $user_id);
            $Number_SeqModel = new Number_SeqModel();
            $order_number = $Number_SeqModel->createSeq($prefix);
            $order_id = sprintf('%s-%s', 'ZY', $order_number);

            $User_StockOutModel->sql->startTransactionDb();
            $rs_row = array();
            foreach ($out_num_list as $stock_id=>$out_num)
            {
                $goods_stock = $goods_stock_list[$stock_id];
                if($out_num > 0 && $out_num <= $goods_stock['goods_stock']) {
                    $add_row = array();
                    $add_row['out_order_id'] = $order_id;
                    $add_row['user_id'] = $user_id;
                    $add_row['user_name'] = $user_id;
                    $add_row['goods_id'] = $goods_stock['goods_id'];
                    $add_row['common_id'] = $goods_stock['common_id'];
                    $add_row['goods_name'] = $goods_stock['goods_name'];
                    $add_row['out_num'] = $out_num;
                    $add_row['out_type'] = User_StockOutModel::OUT_SELF;
                    $add_row['out_time'] = get_date_time();

                    $add_flag = $User_StockOutModel->addStockOut($add_row);
                    check_rs($add_flag, $rs_row);

                    if($user_id != Web_ConfigModel::value('self_user_id')) {
                        //修改商品库存
                        if ($out_num) {
                            $edit_row = array();
                            $edit_row['goods_stock'] = $out_num * -1;
                            $edit_flag = $User_Stock_Model->editUserStock($stock_id, $edit_row, true);
                            check_rs($edit_flag, $rs_row);
                        }
                    }else{
                        //修改店铺商品库存
                        if ($out_num) {
                            $edit_row = array();
                            $edit_row['goods_stock'] = $out_num * -1;
                            $edit_flag = $Goods_BaseModel->editBase($stock_id, $edit_row, true);
                            check_rs($edit_flag, $rs_row);

                            $edit_row2 = array();
                            $edit_row2['common_stock'] = $out_num * -1;
                            $edit_flag2 = $Goods_CommonModel->editCommon($goods_stock['common_id'], $edit_row2, true);
                            check_rs($edit_flag2, $rs_row);
                        }
                    }
                }

            }

            if(is_ok($rs_row) && $User_StockOutModel->sql->commitDb()){
                $msg =  __('success');
                $status = 200;
            }else{
                $User_StockOutModel->sql->rollBackDb();
                $m = $User_StockOutModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }
}

?>