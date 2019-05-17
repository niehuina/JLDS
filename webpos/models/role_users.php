<?php namespace models;

class role_users extends base{

	protected $table = 'role_users';
	//规则查看 https://github.com/vlucas/valitron 
	public $rules = [
			 	'role_id'=>['required'],
			 	'user_id'=>['required'],
			 	];
    /**
     * 获取所属店铺信息
     */
    public function roles()
    {
        return $this->hasOne('models\roles','id','role_id');
    } 

}