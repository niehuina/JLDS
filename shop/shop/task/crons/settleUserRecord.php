<?php
if (!defined('ROOT_PATH')) {
    if (is_file('../../../shop/configs/config.ini.php')) {
        require_once '../../../shop/configs/config.ini.php';
    } else {
        die('请先运行index.php,生成应用程序框架结构！');
    }

    //不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
    $Base_CronModel = new Base_CronModel();
    $rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

    //终止执行下面内容, 否则会执行两次
    return;
}
$cur_dir = dirname(__FILE__);
chdir($cur_dir);

Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

fb($crontab_file);

/**
 * 将可以结算的入账记录，从账户冻结余额中转移到账户余额中，并更新记录状态及时间
 */
//执行任务
$key = Yf_Registry::get('paycenter_api_key');
$url = Yf_Registry::get('paycenter_api_url');
$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
$formvars = array();
$formvars['app_id'] = $paycenter_app_id;

$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editUserMoneyFrozen&typ=json', $url), $formvars);
