<?php

include __DIR__.'/../../vendor/autoload.php';
if(!function_exists('__')){function __($str){return $str;}}
define('ROOT_PATH',realpath(__DIR__.'/../../').'/');





$met = isset($_GET['met'])?$_GET['met']:"index";
 
$config_path = __DIR__.'/configs/';
include __DIR__.'/Medoo.php';
use Medoo\Medoo;
$lock = __DIR__.'/data/lock.txt';

global $db;

global $installDBFiles;
$installDBFiles = [
		'/public'=>'',
		

];


error_reporting(E_ALL^E_NOTICE^E_WARNING);
$msg =  " "; 
function get_db(){
		global $db; 
		if($db){return $db;}
		try 
		{ 

			  $db_row = include ROOT_PATH.'/configs/db.php'; 



				$db = new Medoo([
				    'database_type' => 'mysql',
				    'database_name' => $db_row['default']['database'],
				    'server' => $db_row['default']['write']['host'],
				    'username' => $db_row['default']['username'],
				    'password' => $db_row['default']['password'],
				    'port' => isset($db_row['default']['port'])?:3306,
				    'command' => [
							'SET SQL_MODE=ANSI_QUOTES'
						]
				]);  
				return $db;

		} 
		catch(Exception $e) 
		{  
			 
		} 
}

if($met == 'plugin'){
	$met = 'db';
}
switch ($met) {

	case 'index':
		include __DIR__.'/views/policy.php';
		break;
	case 'checkEnv':
		$check_rs = true; 
		//扩展
		$loaded_ext_row = get_loaded_extensions();
		$check_ext_row = include_once $config_path . 'check_ext.ini.php'; 
		foreach ($check_ext_row as $ext_name)
		{
			if (!in_array($ext_name, $loaded_ext_row))
			{
				$check_rs = false;
				break;
			}
		}

		//目录权限
		$check_dir_row = include_once $config_path . 'check_dir.ini.php';
		$dir_rows = check_dirs_priv($check_dir_row);

		//函数检查
		if (!$dir_rows['result'])
		{
			$check_rs = false;
		}
		include __DIR__.'/views/checkEnv.php';


		break;
	case 'env': 
		 
		include __DIR__.'/views/env.php';
		break;
	case 'plugin':
		
		include __DIR__.'/views/plugin.php';
		break;	

	case 'db':
		


		include __DIR__.'/views/db.php';
		break;	
	case 'initDbConfig':
		
				if($_POST){
				 	$db_row = array(
						'host' => 'localhost',
						'port' => '3306',
						'user' => '',
						'password' => '',
						'database' => '',
						'charset' => 'UTF8'
					); 
					 
					$db_row['host'] = trim($_POST['host']);
					$db_row['port'] = trim($_POST['port']);
					$db_row['username'] = trim($_POST['user']);
					$db_row['password'] = trim($_POST['password']);
					$db_row['database'] = trim($_POST['database']); 

					/*try 
					{ 

							$database = new Medoo([
							    'database_type' => 'mysql',
							    'database_name' => $db_row['default']['database'],
							    'server' => $db_row['default']['write']['host'],
							    'username' => $db_row['default']['username'],
							    'password' => $db_row['default']['password'],
							    'port' => $db_row['port']?:3306,
							]); 

					} 
					catch(Exception $e) 
					{  
							$msg = "数据库配置不正确";
							include  __DIR__.'/views/db.php';
							exit;
					} */

					//检查MYSQL是否是严格模式
					//$database->query(" set global sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"); 
					
					$input = array(
						    'default'=>array(
						                'read' => array(
						                   'host' => $db_row['host'],
						                ),
						                'write' => array(
						                    'host' => $db_row['host'] 
						                ),
						                'driver'    => 'mysql',
						                'database'  => $db_row['database'],
						                'username'  => $db_row['username'],
						                'password'  => $db_row['password'],
						                'charset' => 'utf8mb4',
						                'collation' => 'utf8mb4_unicode_ci',
						                'prefix'    => '',
						    ), 
						    
					);

					file_put_contents(ROOT_PATH.'/configs/db.php', "<?php return ".var_export($input,true)."\n;");

				  
					//Header("Location:index.php?met=initDbConfig"); 
 

		}

		include __DIR__.'/views/initDbConfig.php';
		break;
	case 'install':
	
		$state_row = check_install_db(); 
		if(file_exists($lock)){
			 $msg = '已安装程序，禁止再次安装。';
			 include __DIR__.'/views/msg.php';
			 exit;
		}
	 
	  if (9 == $state_row['state'])
		{
			 $msg = $state_row['msg'];
			 include __DIR__.'/views/msg.php';
			 exit;
		}
		foreach( $installDBFiles as $f=>$pre){
				$arr = explode('/',$f);
				$dr = ROOT_PATH.$arr[1].'/install/data/sql';
				if(is_dir($dr)){
					$installSql[$dr] = $pre;

				} 

		}
		if(count($installSql) != 1)
		{
			  $msg = __('安装SQL文件缺少');
				include  __DIR__.'/views/msg.php';
				exit;
		}

    ob_end_flush(); 
	  include  __DIR__.'/views/install.php';
		foreach ($installSql as $sql_path => $TABEL_PREFIX) { 

					echo str_repeat(" ", 4096);  //以确保到达output_buffering值 
					echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
							__("安装...").$TABEL_PREFIX."</li>";
 					ob_flush();
					flush(); 
					$dir = scandir($sql_path);

					$init_db_row = array();

					foreach ($dir as $item)
					{
						$file = $sql_path . DIRECTORY_SEPARATOR . $item;
						if (is_file($file))
						{  
								$flag = import($file, $TABEL_PREFIX); 
						}
					}   
		} 

		$r = $db->error();
		if($r[2]){ 
				var_dump($r);

		}

		//写webpos_admin管理员
		//
		/*$data = [];
		$data['user']    = 'admin'; //  用户账号
		$data['pwd']     = 111111;  //  用户密码
		$data['level']   = 1;       //  用户权限组*/
		$pwd = password_hash('yuanfeng021', PASSWORD_DEFAULT);
		if(!$get){
				$db->query("
					insert into admin_users set 
							user = 'admin',
							pwd  = '".$pwd."',
							level=  1
							" 
				); 
		}



	  	echo "<li class='line'><span class='yes' style='color:blue'><i class='iconfont'></i></span>".
		 					__("安装完成，页面即将跳转……")."</li>"; 

		file_put_contents($lock, 1);
	 
 	 	echo "<script>location.href='index.php?met=admin';</script>";	 
		exit;
		include __DIR__.'/views/install.php';
		break;
	case 'admin':
		
		include __DIR__.'/views/admin.php';
		break;
	default:
		# code...
		break;
}






/**
 * 检查目录的读写权限
 *
 * @access  public
 * @param   array     $check_dir_row     目录列表
 * @return  array     检查后的消息数组，
 */
function check_dirs_priv($check_dir_row)
{
	$state = array('result' => true, 'detail' => array());

	foreach ($check_dir_row as $dir)
	{
		$file = ROOT_PATH . $dir;

		if (!file_exists($file))
		{
			//$flag = mkdir($file, 0777, true);
		}

		if (is_writable($file))
		{
			$state['detail'][] = array($dir, __('yes'), __('可写'));
		}
		else
		{
			$state['detail'][] = array($dir, __('no'), __('不可写'));
			$state['result'] = false;
		}
	}

	return $state;
}






function import($sqlfile, $db_prefix='yf_')
{
	get_db();
	global $db;
	static $loop;
	if(!$loop) $loop = 0;
		// sql文件包含的sql语句数组
		$sqls = array();
		$f    = fopen($sqlfile, "rb");

		// 创建表缓冲变量
		$create_table = '';
		if($loop == 0){
			$scr = "
<script>
			function install_bottom()
{
var now = new Date();
var div = document.getElementById('installed'); 
div.scrollTop = div.scrollHeight;
}
</script>
";
			echo $scr."<ol id='installed' name='installed' style='height: 600px;overflow-y: auto;'>";
		}
		while (!feof($f))
		{
			// 读取每一行sql
			$line = fgets($f);

			if (substr($line, 0, 2) == '/*' || substr($line, 0, 2) == '--' || $line == '')
			{
				continue;
			}

			$create_table .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				// 默认一键安装，不支持修改表前缀
				//$create_table = str_replace($db_prefix_base, $db_prefix, $create_table);  
				//执行sql语句创建表
				 
				$flag = $db->query($create_table);  
				echo str_repeat(" ", 4096);  //以确保到达output_buffering值
				
				$pattern = '/CREATE TABLE.*`(.*)`/i';
			  preg_match($pattern, $create_table, $matches);  
			  $show_table_created = $matches[1];  

				if($show_table_created){
					echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
							__("创建数据库")." ".$show_table_created." ".__("成功")."</li>";
				} 
				echo "<script>install_bottom();</script>";
				ob_flush();
				flush(); 

				// 清空当前，准备下一个表的创建
				unset($create_table);
				$create_table = '';
			}

			unset($line);
		}
	 
 	  $loop++;
		fclose($f);
		
		return true;
}



function check_install_db()
{

	  get_db();
	  global $db;
	  global $installDBFiles;
		try
		{ 
 				$db_row = include ROOT_PATH.'/configs/db.php' ;
				$state = 2;
 				$table_sql = "SELECT table_name FROM information_schema.tables WHERE table_schema='" . $db_row['default']['database'] ."' AND table_type='BASE TABLE'";
  			$table_rows = $db->query($table_sql)->fetchAll();
   			foreach($installDBFiles as $fs=>$TABEL_PREFIX){
  						foreach ($table_rows as $table_row)
							{
								//表存在,则停止安装
								if ($TABEL_PREFIX == substr($table_row['table_name'], 0, strlen($TABEL_PREFIX)))
								{
									$state = 9;
									$msg = '数据库信息已经存在,不可以继续安装,请先手动删除存在的表后,执行安装程序!';
									break;
								}
							}
  			} 
			 
		}
		catch(Exception $e)
		{

		}
	return array('state'=>$state, 'msg'=>$msg);
}

