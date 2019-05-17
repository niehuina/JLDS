<?php
$sort['wq'] = request_data('wq');
$sort['status'] = request_data('status');
$sort['start_time'] = request_data('start_time');
$sort['end_time'] = request_data('end_time');
$qx = new \cs\controller_home();
$arr = $qx->operation_authority(1,1);
?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
        <tr role="row">
            <th class="wap_hide"><?php echo __('序号'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('编号'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'order_id_'.($_GET['sort']['num']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('日期'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'created_'.($_GET['sort']['created']?:'desc') ]); ?>" >
                </a>
            </th>
            <th class="wap_hide"><?php echo __('状态'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'order_status_'.($_GET['sort']['order_status']?:'desc') ]); ?>" >
                </a>
            </th>
            <th ><?php echo __('会员名称'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'user_id_'.($_GET['sort']['user_id']?:'desc') ]); ?>" >
                </a>
            </th>
            <th class="wap_hide"><?php echo __('手机号码'); ?></th>
            <th ><?php echo __('总价'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'good_price_ori_'.($_GET['sort']['good_price_ori']?:'desc') ]); ?>" >
                </a>
            </th>
            <th class="wap_hide"><?php echo __('数量'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'good_num_'.($_GET['sort']['good_num']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('所属门店'); ?></th>
            <th><?php echo __('操作'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($model){
            foreach($model as $v){ ?>
            <tr role="row" class="odd">
                <td class="wap_hide"><?php echo $v->id; ?></td>
                <td ><?php echo $v->order_id; ?></td>
                <td ><?php echo date('Y-m-d H:i:s' ,$v->created); ?></td>
                <td class="wap_hide"><?php if($v->order_status==1){echo __('待支付');
                }else{echo __('已完成'); } ?></td>
                <td ><?php echo $v->wp_users->ucenter_name?:__('非会员'); ?></td>
                <td class="wap_hide"><?php echo $v->wp_users->phone?:__('非会员'); ?></td>
                <td >￥<?php echo $v->good_price_ori; ?></td>
                <td class="wap_hide"><?php echo $v->good_num; ?></td>
                <td ><?php echo $v->yf_shop_base->title; ?></td>
                <td>
                    <a href="<?php echo url('doc/wp_order_value/index',['order_id'=>$v->order_id])?>" data-url="doc/wp_order_value/index"><?php echo __('查看详情'); ?></a>
                </td>
            </tr>
            <?php }} ?>

        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <?php
    echo $model->appends(page_opt())->render();
    ?>
    