<?php namespace cs;
/**
 * select 中生成TREE结构数组
 * @weichat  sunkangchina
 * @datetime 2017-04-07T14:11:06+0800
 */
class tree{

	static $set = [
		'id' => 'id',
		'pid' => 'pid',
		'title' => 'title',
	];

	static $_treeList_baseModel;
 	static $string = "----";
	/**
	 * 执行
	 * @param    [type]                   $arr
	 * @param    integer                  $pid
	 * @param    string                   $span
	 * @return   [type]
 
	 */
	static function render($arr,$pid = 0 ){ 
		if(is_object($arr)){
			foreach($arr as $v){
					$obj[] = $v;
			}
			$arr = $obj;
		}
		if(!$arr){
			return;
		}
		if(is_array($arr)){
			foreach($arr as $v){
				if($v[static::$set['pid']] == $pid){ 
					static::$_treeList_baseModel[] = $v;
					$flag = md5(json_encode($v));
					$level = 0;
 					static::_tableTreeHelper($arr,$v ,$level );
				}
			}
		}

		return static::$_treeList_baseModel;
	}
	
	static function _tableTreeHelper($arr ,$v ,$level  ){ 
		for($i=0;$i<= $level;$i++){
			$str .= self::$string;
		}
		if(self::$string){
				$str .= "|";	
		} 
		if(is_array($arr)){
			foreach($arr as $vo){ 
				if($vo[static::$set['pid']] == (string)$v[static::$set['id']]){ 
						$vo[static::$set['title']] = $str.$vo[static::$set['title']];
            if(!$vo)continue;
					  static::$_treeList_baseModel[] = $vo;  
					  static::_tableTreeHelper($arr, $vo ,$level+1 );
				}
			}
		}
	
	}

}