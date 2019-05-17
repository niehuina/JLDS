<?php
$sort['wq'] = request_data('wq');
$sort['yf_shop_base_id'] = request_data('yf_shop_base_id');
$sort['start_time'] = request_data('start_time');
$sort['end_time'] = request_data('end_time');
?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th class="wap_hide"><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('收银员账号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'shop_users_id_'.($_GET['sort']['shop_users_id']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('开始时间'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'start_time_'.($_GET['sort']['start_time']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('结束时间'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'end_time_'.($_GET['sort']['end_time']?:'desc') ]); ?>" ></a>
        </th>
        <th ><?php echo __('总额'); ?></th>
        <th class="wap_hide"><?php echo __('现金'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'cash_payments_'.($_GET['sort']['cash_payments']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('银联'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'unionpay_pay_'.($_GET['sort']['unionpay_pay']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('微信'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'weixin_pay_'.($_GET['sort']['weixin_pay']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('支付宝'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'alipay_pay_'.($_GET['sort']['alipay_pay']?:'desc') ]); ?>" ></a>
        </th>

        </th>
        <th class="wap_hide"><?php echo __('备用金'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'standby_money_'.($_GET['sort']['standby_money']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('应缴金额'); ?></th>
        <th><?php echo __('门店'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'yf_shop_base_id_'.($_GET['sort']['yf_shop_base_id']?:'desc') ]); ?>" ></a>
        </th>

    </tr>
    </thead>
    <tbody>
    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row" class="odd">
                <td class="wap_hide"><?php echo $v->id; ?></td>
                <td><?php echo $v->shop_users->user; ?></td>
                <td class="wap_hide"><?php echo date('Y-m-d h:i:s',$v->start_time); ?></td>
                <td class="wap_hide"><?php echo date('Y-m-d h:i:s',$v->end_time); ?></td>
                <td>￥<?php echo $v->cash_payments+$v->unionpay_pay+$v->weixin_pay+$v->alipay_pay; ?></td>
                <td class="wap_hide"><?php echo $v->cash_payments?'￥'.$v->cash_payments:'--'; ?></td>
                <td class="wap_hide"><?php echo $v->unionpay_pay?'￥'.$v->unionpay_pay:'--'; ?></td>
                <td class="wap_hide"><?php echo $v->weixin_pay?'￥'.$v->weixin_pay:'--'; ?></td>
                <td class="wap_hide"><?php echo $v->alipay_pay?'￥'.$v->alipay_pay:'--'; ?></td>
                <td class="wap_hide"><?php echo $v->standby_money?'￥'.$v->standby_money:'--'; ?></td>
                <td>￥<?php echo $v->cash_payments+$v->standby_money; ?></td>
                <td><?php echo $v->yf_shop_base->title; ?></td>
            </tr>
        <?php }
    } ?>

    </tbody>
    <tfoot>

    </tfoot>
</table>
<?php
echo $model->appends(page_opt())->render();
?>