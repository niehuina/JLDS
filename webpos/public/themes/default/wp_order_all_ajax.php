<?php
$sort['wq'] = request_data('wq');
?>
    <table class="table table-bordered table-hover dataTable" >
        <thead>
        <tr role="row">
            <th class="wap_hide"><?php echo __('序号'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('销售额'); ?></th>
            <th><?php echo __('订单总数'); ?></th>
            <th><?php echo __('退单金额'); ?></th>
            <th><?php echo __('收银员账号'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'user_'.($_GET['sort']['user']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('所属门店'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($model){
            foreach($model as $v){  ?>
                <tr role="row" class="odd">
                    <td class="wap_hide"><?php echo $v->id; ?></td>
                    <td><?php echo $v->sum; ?></td>
                    <td><?php echo count($v->wp_order); ?></td>
                    <td><?php echo $v->good; ?></td>
                    <td><?php echo $v->user; ?></td>
                    <td><?php echo $v->type==1?__('商城导入订单'): $v->yf_shop_base->title; ?></td>
                </tr>
            <?php }} ?>

        </tbody>
        <tfoot>

        </tfoot>
    </table>
<?php
echo $model->appends(page_opt())->render();
?>