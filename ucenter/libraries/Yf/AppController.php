 <?php
/**
 * 主控制器
 * 
 * 处理默认运行的方法，让不同功能的控制器继承
 * 
 * @category   Framework
 * @package    Controller
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_AppController extends Yf_Controller
{
    /**
     * 默认控制的模型
     * 
     * @access public
     * @var Object $Yf_Model
     */
    public $model = null;

    /**
     * 调用模板类，控制视图
     * 
     * @access public
     * @var string|null
     */
    public $view = null;

    /**
     * format json data  for Ajax  
     *
     * @var Yf_Data
     */
    public $data = null;

    /**
     * Constructor
     *
     * @global string $themes 视图风格
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        if (true || 'e' == $this->typ)
        {
            global $themes;
            global $themes_name;
            global $pro_path_row;

            $this->view = new Yf_View($this->ctl, $this->met);

            $this->view->stc = Yf_Registry::get('static_url');
            $this->view->img = Yf_Registry::get('static_url')  . '/images';
            $this->view->css = Yf_Registry::get('static_url')  . '/css';
            $this->view->js  = Yf_Registry::get('static_url')  . '/js';
    

			$this->view->stc_com = str_replace('/' . APP_DIR_NAME . '/static/' . $themes_name, '/' . APP_DIR_NAME . '/static/common', $this->view->stc);
			$this->view->img_com = $this->view->stc_com  . '/images';
			$this->view->css_com = $this->view->stc_com  . '/css';
			$this->view->js_com  = $this->view->stc_com  . '/js';


			if (isset($pro_path_row[1]))
			{
				$this->view->url  = $pro_path_row[1] . '';
			}
			else
			{
				$this->view->url  = '';
			}


            //
            $this->data = new Yf_Data();
        }
        else
        {
            $this->data = new Yf_Data();
        }

        $this->init();
    }


	/**
	 * 不要建议使用
	 *
	 * @param string $method 方法名称
	 * @param string $args  参数
	 * @return void
	 */
	public function __call($method, $args)
	{
		error_header(404, 'Page Not Found');
		//echo '请检查 $act 及 $met 是否正确， 类不存在传入的方法！';
		throw new Exception('请检查 $act 及 '.$this->met.' 是否正确， 类不存在传入的方法！');
		//die();
	}

    /**
     * 运行 $met 方法
     * 
     * @access public
     */
    public function run()
    {
		call_user_func(array($this, $this->met));
		/*
        if (method_exists($this, $this->met))
        {
            call_user_func(array($this, $this->met));
        }
        else
        {
            error_header(404, 'Page Not Found');
            //echo '请检查 $act 及 $met 是否正确， 类不存在传入的方法！';
            throw new Exception('请检查 $act 及 '.$this->met.' 是否正确， 类不存在传入的方法！');
            //die();
        }
		*/
    }

    /**
     * 处理后的数据，用来返还给客户端
     * 
     * @access public
     * @return array 返回ajax需要的json数组
     */
    public function getDataRows()
    {
        $rs = $this->data->getDataRows();

        return $rs;
    }

    /**
     * 默认运行的主方法，可以被覆盖。
     * 
     * @access public
     */
    public function index()
    {
        phpinfo();
    }

    /**
     * 初始化方法，有构造函数调用
     * 
     * @access public
     */
    public function init()
    {
    }


    public function getData()
    {
        if ('e' == $this->typ)
        {
			$d = ob_get_contents();
			ob_end_clean();
			ob_start();
		}
		else
		{
			$d = $this->getDataRows();
		}

        return $d;
    }

	/**
	 * 显示消息输出
	 *
	 * @access public
	 */
	public function showMsg($msg='发生错误', $msg_type='错误', $status=250)
	{
		if ('e' == $this->typ)
		{
			include $this->view->getMsgPath();

			exit();
		}
		else
		{
			$data = new Yf_Data();
			$data->setError($msg, array(), $status);


			$d = $data->getDataRows();
			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}

		return false;
	}

    /**
     * 确保当前用户是在微信中打开，并且获取用户信息
     *
     * @param string $url 获取到微信授权临时票据（code）回调页面的URL
     */
    public function getWxUserInfo($url = '') {

        $WxModel = new WxModel();

        //微信标记（自己创建的）
        $wxSign = $_COOKIE['wxSign'];
        //先看看本地cookie里是否存在微信唯一标记，
        //假如存在，可以通过$wxSign到cookie里取出微信个人信息（因为在第一次取到微信个人信息，我会将其保存一份到redis服务器里缓存着）
        if (!empty($wxSign)) {
            //如果存在，则从cookie里取出缓存了的数据
            $userInfo = $_COOKIE("weixin:sign_{$wxSign}");
            if (!empty($userInfo)) {
                //获取用户的openid
                $this->wxId = $userInfo['openid'];
                //将其存在cookie里
                setcookie('wxId', $this->wxId, 60*60*24*7);
                return $userInfo;
            }
        }

        //获取授权临时票据（code）
        $code = $_GET['code'];
        if (empty($code)) {
            if (empty($url)) {
                $url = rtrim($_SERVER['QUERY_STRING'], '/');
                //到WxModel.php里获取到微信授权请求URL,然后redirect请求url
                redirect($WxModel->getOAuthUrl(baseUrl($url)));
            }
        }
        /***************这里开始第二步：通过code获取access_token****************/
        $result = $WxModel->getOauthAccessToken($code);

        //如果发生错误
        if (isset($result['errcode'])) {
            return array('msg'=>'授权失败,请联系客服','result'=>$result);
        }

        //到这一步就说明已经取到了access_token
        $this->wxId = $result['openid'];
        $accessToken = $result['access_token'];
        $openId = $result['openid'];

        //将openid和accesstoken存入cookie中
        setcookie('wx_id', $this->wxId, 60*60*24*7);
        setcookie('access_token', $accessToken);

        /*******************这里开始第三步：通过access_token调用接口，取出用户信息***********************/
        $this->userInfo = $WxModel->getUserInfo($openId, $accessToken);

        //自定义微信唯一标识符
        $wxSign =substr(md5($this->wxId.'k2m5i3'), 8, 16);
        //将其存到cookie里
        setcookie('wxSign', $wxSign, 60*60*24*7);
        //将个人信息缓存到cookie里
        setcookie("weixin:sign_{$wxSign}", $userInfo, 60*60*24*7);
        return $userInfo;
    }
}
?>