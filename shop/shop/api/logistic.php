<?php
require_once '../configs/config.ini.php';

//Web_ConfigModel::value('kuaidi100_status');
$channel = Web_ConfigModel::value('logistics_channel');


$order_id   = request_string('order_id');  //订单编号
$express_id = request_string('express_id'); //物流公司编码
$nu         = request_string('shipping_code'); //物流单号

$ExpressModel = new ExpressModel();

$express_row = $ExpressModel->getOne($express_id);

if ('kuaidi100' == $channel)
{
	$cus_id = Web_ConfigModel::value('kuaidi100_app_id');
	$key = Web_ConfigModel::value('kuaidi100_app_key');

	if($express_id && $nu)
	{
		$express_row = $ExpressModel->getOne($express_id);

		if ($express_row)
		{
			$express_pinyin = $express_row['express_pinyin'];
            Yf_Log::log($express_pinyin, Yf_Log::LOG, 'kuaidi');
            Yf_Log::log($nu, Yf_Log::LOG, 'kuaidi');

			$str = lookorder($express_pinyin, $nu, $key, $cus_id);

			if(isset($str['status']) && $str['status'] == 200)
			{
				$content_div = '';
				$content_div = sprintf('<div class="pc"><div class="p-tit">运单号：%s (%s)</div><div class="logistics-cont"><ul>',$nu ,$express_row['express_name']);
				foreach ($str['data'] as $key => $val)
				{
					$time    = $val['time'];
					$context = $val['context'];

					$deliver_info[] = [
							'time'=>$time,
							'context'=>$context,
					];

					$class_name = '';

					if ($key == 0)
					{
						$class_name = 'first';

						$content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
					}
					else
					{
						$content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
					}
				}

				$content_div = sprintf('%s</ul></div></div><div class="p-arrow p-arrow-left" style="top: 242px;"></div>',$content_div);
			}
			elseif(isset($str['returnCode']) && $str['returnCode'] == 500)
			{
				$content_div = '<div class="error_msg">'.$str['message'].'</div>';
			}
			else
			{
				$content_div = '<div class="error_msg">物流单暂无结果</div>';
			} 
		}
		else
		{
			$content_div = "";
		//	echo "暂时没有物流信息！";
		}
	}
}
elseif ('kuaidiniao' == $channel)
{
	Web_ConfigModel::value('kuaidiniao_status');
	//$kuaidiniao_express = decode_json(Web_ConfigModel::value('kuaidiniao_express'));

	$e_business_id = Web_ConfigModel::value('kuaidiniao_e_business_id');
	$app_key = Web_ConfigModel::value('kuaidiniao_app_key');


	$express_code = $express_row['express_pinyin'];


	$api = new Api_KdNiao($e_business_id, $app_key);

	$request_rows =
		array (
			'OrderCode' =>   $order_id,  //订单编号
			'ShipperCode' => $express_code, //物流公司编码
			'LogisticCode' => $nu            //物流单号
		);

	$rs_str =  $api->getOrderTracesByJson($request_rows);

	if($rs_str && isset($rs_str['Success']) && $rs_str['Success'] && $rs_str['Traces'])
	{
		$content_div = '';
		$content_div = sprintf('<div class="pc"><div class="p-tit">运单号：%s (%s)</div><div class="logistics-cont"><ul>',$nu,$express_row['express_name'] );
		foreach ($rs_str['Traces'] as $key => $val)
		{
			$time    = $val['AcceptTime'];
			$context = $val['AcceptStation'];
			$deliver_info[] = [
					'time'=>$time,
					'context'=>$context,
			];
			$class_name = '';

			if ($key == 0)
			{
				$class_name = 'first';

				$content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
			}
			else
			{
				$content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
			}
		}

		$content_div = sprintf('%s</ul></div></div><div class="p-arrow p-arrow-left" style="top: 242px;"></div>',$content_div);
	}
	else
	{
		if($rs_str['Reason'])
		{
			$content_div = $rs_str['Reason'];
		}
		else
		{
			$content_div = '<div class="error_msg">物流单暂无结果</div>';
		}
	}

	

	 
}


if($_GET['typ'] == 'json'){ 
			exit(json_encode([
						'status'=>1,
						'data'=>$deliver_info
				]));
}else{
	 echo $content_div;
}

 

 


//http://poll.kuaidi100.com/poll/query.do/?customer=[]&sign=[]&param=[]
/*com	必须	快递公司代码（英文），所支持快递公司见如下列表
nu	必须	快递单号，长度必须大于5位
key	必须	授权KEY，申请请点击快递查询API申请方式,客户授权key
在新版中ID为一个纯数字型，此时必须添加参数secret（secret为一个小写的字符串）
customer	必填	客户编号
type	可选	返回结果类型，值分别为 html | json（默认） | text | xml
encode	可选	gbk（默认）| utf8
ord	可选	asc（默认）|desc，返回结果排序
lang	可选	en返回英文结果，目前仅支持部分快递（EMS、顺丰、DHL）*/
function lookorder($com, $nu, $key, $customer)
{
//    $com = 'shentong';
//    $nu = '773005190382886';

//    $com = 'yunda';
//    $nu = '4301122069022';
//    $key = 'gHdFStiB5931';
//    $customer = '333180D6BCAFF0EFB9BFC64A4424A211';

    //参数设置
    $param = array (
        'com' => $com,			    //快递公司编码
        'num' => $nu,	            //快递单号
        'phone' => '',				//手机号
        'from' => '',				//出发地城市
        'to' => '',					//目的地城市
        'resultv2' => '1'			//开启行政区域解析
    );

    //请求参数
    $post_data = array();
    $post_data["customer"] = $customer;
    $post_data["param"] = json_encode($param);
    $sign = md5($post_data["param"].$key.$post_data["customer"]);
    $post_data["sign"] = strtoupper($sign);

    $url = 'http://poll.kuaidi100.com/poll/query.do';	//实时查询请求地址

    $params = "";
    foreach ($post_data as $k=>$v) {
        $params .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
    }
    $post_data = substr($params, 0, -1);
    Yf_Log::log($post_data, Yf_Log::LOG, 'kuaidi');

    //发送post请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    $data = str_replace("\"", '"', $result );
    $data = json_decode($data,true);

	return $data;
}
?>