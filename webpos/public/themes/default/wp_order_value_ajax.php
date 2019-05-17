<?php
$sort['wq'] = request_data('wq');
$qx = new \cs\controller_home();
$arr = $qx->operation_authority(1,1);
?>
    <table class="table table-bordered table-hover dataTable" >
        <thead>
        <tr role="row">
            <th class="wap_hide"><?php echo __('序号'); ?></th>
            <th><?php echo __('编号'); ?></th>
            <th><?php echo __('会员名称'); ?></th>
            <th class="wap_hide"><?php echo __('手机号码'); ?></th>
            <th><?php echo __('支付方式'); ?></th>
            <th><?php echo __('名称'); ?></th>
            <th class="wap_hide"><?php echo __('数量'); ?></th>
            <th class="wap_hide"><?php echo __('价格'); ?></th>
            <th><?php echo __('已退货数量'); ?></th>
            <th><?php echo __('操作'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($model){
            foreach($model as $v){ ?>
                <tr role="row" class="odd">
                    <td class="wap_hide"><?php echo $v->id; ?></td>
                    <td><?php echo $v->wp_order_info->order_id; ?></td>
                    <td><?php echo $v->wp_order_info->wp_users->ucenter_name?:__('非会员') ;?></td>
                    <td class="wap_hide"><?php echo $v->wp_order_info->wp_users->phone?:__('非会员'); ?></td>
                    <td><?php echo array_search($v->wp_order_info->payid,config('payment')); ?></td>
                    <td class="product-buyer-name" title="<?php echo $v->goods_name; ?>"><?php echo $v->goods_name; ?></td>
                    <td class="wap_hide"><?php echo $v->num; ?> </td>
                    <td><?php echo $v->goods_price; ?></td>

                     
                    
                        <td><?php echo $v->renum; ?></td>
                    <td>
                        <?php
                        if($v->type != 1 && cookie('admin_level') != 1){
                            if($v->wp_order_info->order_status==1){
                                echo __('待支付');
                            }elseif($v->num != $v->renum){
                                ?>
                                <a href="<?php echo  url('doc/wp_order_return/index',['id'=>$v->id] ); ?>" data-url="doc/wp_order_return/index">
                                    <?php echo __('退货退款'); ?>
                                </a>
                            <?php }else{ echo __('已退货'); }  }else{
                            echo __('不可操作');
                        }?>


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