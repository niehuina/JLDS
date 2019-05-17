<?php
namespace modules\home;
 
/**
 * @desc 首页
 *
 */
class rest extends \cs\rest{

	 
	 
	public function index(){
		 $this->data = ['a'=>'test','b'=>'debug'];
	}


 
}