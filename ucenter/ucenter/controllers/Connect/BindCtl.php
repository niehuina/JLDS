<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Connect_BindCtl extends Yf_AppController implements Connect_Interface
{
	public $appid     = null;
	public $appsecret = null;
	public $redirect_url = null;
	public $callback = null;

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

		$this->type = request_string('type');
		$connect_config = Yf_Registry::get('connect_rows');

		$this->appid     = $connect_config[$this->type]['app_id'];
		$this->appsecret = $connect_config[$this->type]['app_key'];
		$this->callback = request_string('callback');

		if($this->type == 'qq')
		{
			$this->redirect_url = Yf_Registry::get('base_url') . '/login.php';
			$this->bindtype = User_BindConnectModel::QQ;
		}
		if($this->type == 'weixin')
		{
			$this->redirect_url = sprintf('%s?ctl=Connect_Bind&met=callback&from=%s&callback=%s&type=%s',Yf_Registry::get('url') , request_string('from'), urlencode(request_string('callback')),'weixin');
			$this->bindtype = User_BindConnectModel::WEIXIN;
		}
		if($this->type == 'weibo')
		{
			$this->redirect_url = sprintf('%s?ctl=Connect_Bind&met=login&type=%s', Yf_Registry::get('url'),'weibo');

			$this->bindtype = User_BindConnectModel::SINA_WEIBO;
		}


	}

	public function select()
	{
		include $this->view->getView();
	}

	public function login()
	{
		$url = '';
		
		//1.QQ授权
		if($this->type == 'qq')
		{
			$redirect_url = $this->redirect_url;

			$url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=".$this->appid."&redirect_uri=".urlencode($redirect_url)."&client_secret=".$this->appsecret."&state=".urlencode($this->callback)."&cope=get_user_info,get_info&callback=".urlencode($this->callback);

		}

		//2.微信授权
		if($this->type == 'weixin')
		{
			$redirect_url = urlencode($this->redirect_url);

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
			{
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=123&connect_redirect=1#wechat_redirect";
			}
			else
			{
				$url = "https://open.weixin.qq.com/connect/qrconnect?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
			}
		}

		//3.微博授权
		if($this->type == 'weibo')
		{
			$redirect_url = $this->redirect_url;

			if($_GET['code'])
			{
				$_REQUEST['callback'] = $_GET['state'];
				$this->callback();
				exit;
			}

			$url = "https://api.weibo.com/oauth2/authorize?client_id=".$this->appid."&redirect_uri=".urlencode($redirect_url)."&state=".urlencode($this->callback);

		}

		location_to($url);
	}

	/**
	 * callback 回调函数
	 *
	 * @access public
	 */
	public function callback()
	{
		$type = $this->bindtype;

		$code = request_string('code', null);

		$redirect_url = $this->redirect_url;

		$openid = '';

		$login_flag = false;

		//判断当前登录账户
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;
		}
		else
		{
			$user_id = 0;
		}

		if ($code)
		{
			if($this->type == 'qq')
			{
				$data = $this->getQQuser($code);
			}
			if($this->type == 'weixin')
			{
				$data = $this->getWXuser($code);
			}
			if($this->type == 'weibo')
			{
				$data = $this->getWBuser($code);
			}

			if($data['status'] == 200)
			{

				$connect_rows = array();
				$User_BindConnectModel = new User_BindConnectModel();
				$bind_id     = $data['bind_id'];
				$connect_rows = $User_BindConnectModel->getBindConnect($bind_id);
					
				if ($connect_rows)
				{
					$connect_row = array_pop($connect_rows);
				}
				//已经绑定,并且用户正确
				if (isset($connect_row['user_id']) && $connect_row['user_id'])
				{
					//验证通过, 登录成功.
					if ($user_id && $user_id == $connect_row['user_id'])
					{
						echo '非法请求,已经登录用户不应该访问到此页面';
						die();
					}

					$login_flag = true;
				}
				else
				{
					// 下面可以需要封装
					$bind_rows     = $User_BindConnectModel->getBindConnect($bind_id);

					if ($bind_rows  && $bind_row = array_pop($bind_rows))
					{
						if ($bind_row['user_id'])
						{
							//该账号已经绑定
							echo '非法请求,该账号已经绑定';
							die();
						}
						if($user_id != 0)
						{
							$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id,$type);
							if($bind_id_row)
							{
								echo '非法请求,该账号已经绑定';
								die();
							}
						}

						$data_row                      = array();
						$data_row['user_id']           = $user_id;
						$data_row['bind_token'] = $data['access_token'];

						$connect_flag = true;
						$User_BindConnectModel->editBindConnect($bind_id, $data_row);
					}
					else
					{
						if($user_id != 0)
						{
							$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id,$type);
							if($bind_id_row)
							{
								echo '非法请求,该账号已经绑定';
								die();
							}
						}

						$user_info = $data['user_info'];
						//插入绑定表
						$bind_array = array();

						$bind_array = array(
											'bind_id'=>$bind_id,
											'bind_type'=>$this->bindtype,
											'user_id'=>$user_id,
											'bind_nickname'=>$user_info['bind_nickname'],
											'bind_avator'=>$user_info['bind_avator'],
											'bind_gender'=>$user_info['bind_gender'],
											'bind_openid'=>$data['openid'],
											'bind_token'=>$data['access_token'],
											);
						
						$connect_flag = $User_BindConnectModel->addBindConnect($bind_array);
					}
					
					//取得open id, 需要封装
					if ($connect_flag)
					{
						//选择,登录绑定还是新创建账号 $user_id == 0
						if (!Perm::checkUserPerm())
						{
							$url = sprintf('%s?ctl=Login&met=select&t=%s&type=%s&from=%s&callback=%s', Yf_Registry::get('url'), $data['access_token'],$type, request_string('from'), urlencode(request_string('callback')?:$_GET['callbak']));
							location_to($url);
						}
						else
						{
							$login_flag = true;
						}
					}
					else
					{
						//
					}
				}
			}

			if ($login_flag)
			{
				//验证通过, 登录成功.
				if ($user_id && $user_id == $connect_row['user_id'])
				{
					echo '非法请求,已经登录用户不应该访问到此页面';
					die();
				}
				else
				{
					$User_InfoModel  = new User_InfoModel();
					$result = $User_InfoModel->userlogin($connect_row['user_id']);
				
					if($result)
					{
						$msg    = 'success';
						$status = 200;

						$this->data->addBody(-140, $result, $msg, $status);
					}
					else
					{
						$this->data->addBody('登录失败');
					}

				}

				$login_flag = true;

				if(request_string('callback'))
				{
					$us = $result['user_id'];
					$ks = $result['k'];
				    $url = sprintf('%s&us=%s&ks=%s', request_string('callback'), $us, $ks);
				    location_to($url);

				}
				else
				{
					$url = sprintf('%s?ctl=Login', Yf_Registry::get('url'));
					location_to($url);
				}
				echo '登录系统';
				die();

			}
			else
			{
				//失败
			}

		}
		else
		{
			$this->data->setError('code 获取失败');

		}
	}

	/**
	 * getWXuser 微信互联登录 - 获取用户信息
	 *
	 * @access public
	 */
	public function getQQuser($code)
	{
		$data = array();

		$token_url        = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=' . $this->appid . '&client_secret=' . $this->appsecret . '&code=' . $code . '&redirect_uri='.urlencode($this->redirect_url);

		$curl = curl_init($token_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);

		$error = strpos($response, 'error');

		if($error)
		{
			$error_info = preg_match_all("|{(.*)}|U", $response, $out,PREG_PATTERN_ORDER);

			$error_info = json_decode($out[0][0]);

			$error_id = $error_info->client_id;

			$error_des = $error_info->error_description;

			$data['status'] = 250;

			$this->data->addBody($error_des);
			die();
		}
		else
		{
			$access_token_row = explode('&', $response);
			//取出token
			$access_token = substr($access_token_row[0], strpos($access_token_row[0], "=") + 1);

			//获取用户openid
			$user_openid_url = 'https://graph.qq.com/oauth2.0/me?' . $access_token_row[0];
			$curl            = curl_init($user_openid_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FAILONERROR, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$user_openid = curl_exec($curl);

			curl_close($curl);
			$user_openid_info_row = array();
			$client_id            = "";

			if ($user_openid)
			{
				$user_openid_info = preg_match_all("|{(.*)}|U", $user_openid, $out, PREG_PATTERN_ORDER);

				$user_openid_info_row = json_decode($out[0][0]);

				$client_id = $user_openid_info_row->client_id;

				$openid = $user_openid_info_row->openid;
			}

			if ($openid)
			{
				//获取用户信息
				$user_info_url = 'https://graph.qq.com/user/get_user_info?' . $access_token_row[0] . '&oauth_consumer_key=' . $client_id . '&openid=' . $openid;
				$curl          = curl_init($user_info_url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FAILONERROR, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$user_info = curl_exec($curl);

				curl_close($curl);

				$user_info = json_decode($user_info);
				if($user_info->gender == '女')
				{
					$user_gender = 2;
				}else
				{
					$user_gender = 1;
				}

				$data['status'] = 200;
				$data['bind_id'] = sprintf('%s_%s', 'qq', $openid);
				$data['access_token'] = $access_token;
				$data['openid'] = $openid;
				$data['user_info']['bind_avator'] = $user_info->figureurl_qq_2;
				$data['user_info']['bind_nickname'] = $user_info->nickname;
				$data['user_info']['bind_gender'] = $user_gender;

			}
		}

		return $data;
	}

	/**
	 * getWXuser 微信互联登录 - 获取用户信息
	 *
	 * @access public
	 */
	public function getWXuser($code)
	{
		$data = array();

		$token_url        = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $code . '&grant_type=authorization_code';
		$access_token_row = json_decode(file_get_contents($token_url), true);

		if (!$access_token_row || !empty($access_token_row['errcode']))
		{
			throw new Yf_ProtocalException($access_token_row['errmsg']);
			$data['status'] = 250;
		}
		else
		{
			$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token_row['access_token'] . '&openid=' . $access_token_row['openid'] . '&lang=zh_CN';
			$user_info_row = json_decode(@file_get_contents($user_info_url), true);

			$data['status'] = 200;
			$data['bind_id'] = sprintf('%s_%s', 'weixin', $user_info_row['openid']);
			$data['access_token'] = $access_token_row['access_token'];
			$data['openid'] = $user_info_row['openid'];;
			$data['user_info']['bind_avator'] = $user_info_row['headimgurl'];
			$data['user_info']['bind_nickname'] = $user_info_row['nickname'];
			$data['user_info']['bind_gender'] = $user_info_row['sex'];

		}

		return $data;
	}

	/**
	 * getWBuser 微博互联登录 - 获取用户信息
	 *
	 * @access public
	 */
	public function getWBuser($code)
	{
		$data = array();

		$token_url = 'https://api.weibo.com/oauth2/access_token?grant_type=authorization_code&client_id=' . $this->appid . '&client_secret=' . $this->appsecret . '&code=' . $code . '&redirect_uri='.urlencode($this->redirect_url);

		$curl = curl_init($token_url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);

		$error = strpos($response, 'error');


		if($error)
		{
			$error_info = json_decode($response);

			$error_code = $error_info->error_code;

			$error_description = $error_info->error_description;

			$data['status'] = 250;

			$this->data->addBody($error_description);
			die;

		}
		else
		{
			$token = json_decode($response);
			$access_token = $token->access_token;
			$expires_in = $token->expires_in;
			$remind_in = $token->remind_in;
			$uid = $token->uid;


			//获取用户信息
			$user_openid_url = 'https://api.weibo.com/2/users/show.json?access_token='.$access_token.'&uid='.$uid;
			$curl = curl_init($user_openid_url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FAILONERROR, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$user_info = curl_exec($curl);

			curl_close($curl);

			$user_info = json_decode($user_info);
			if($user_info->gender == 'f')
			{
				$user_gender = 2;
			}else
			{
				$user_gender = 1;
			}

			$data['status'] = 200;
			$data['bind_id'] = printf('%s_%s', 'wb', $access_token);
			$data['access_token'] = $access_token;
			$data['openid'] = $access_token;
			$data['user_info']['bind_avator'] = $user_info->avatar_large;
			$data['user_info']['bind_nickname'] = $user_info->figureurl_qq_2;
			$data['user_info']['bind_gender'] = $user_gender;

		}

		return $data;
	}

}

?>
