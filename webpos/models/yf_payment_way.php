<?php namespace models;
/*
 * 支付方式表数据处理
 * */

use cs\login as pay;
class yf_payment_way extends base{

    protected $table = 'yf_payment_way';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [

    ];

    /*
     *@desc 支付方式开启关闭样式
     */
    public function getStatusNameAttribute(){
        if($this->status==1){
            $flag = "<span class='' style='color:#00AA00;'>".__('启用')."</span>";
        }else{
            $flag = "<span class='' style='color:red;'>".__('未启用')."</span>";
        }
        return $flag;
    }


    public function getPaymentwayNameAttribute()
    {
        $payment = array_search($this->paycenter_id,config('payment'));
        switch($payment){
            case 'wepay':
                $payment = __('微信');
                break;
            case 'alipay':
                $payment = __('支付宝');
                break;
            case 'cash':
                $payment = __('现金');
                break;
            case 'member':
                $payment = __('账户余额');
                break;
            case 'unionpay':
                $payment = __('银行卡');
                break;
            case 'xpsm':
                $payment = __('小票扫码');
                break;
            default:
                break;

        }
        return $payment;
    }
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        $data = post_data();
        if($data['id']){
            $model = self::find($data['id']);
        }
        $model->data($data)->save();
    }
    /*
     *@desc 支付方式
     */
    static  function pay_list(){
        $model = new self;

        $list = self::get()->toArray();
        if(!$list){
            foreach(config('payment') as $k=>$v){
                $model->insert(['pay_way'=>$k,'paycenter_id'=>$v,'status'=>1,'created'=>time()]);
            }
        }
    }



}