<?php
$sort['wq'] = request_data('wq');
$sort['status'] = request_data('status');
$sort['service_start_time'] = request_data('service_start_time');
$sort['service_end_time'] = request_data('service_end_time');

?>
<table class="table table-bordered table-hover dataTable"  >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?>

            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>

        </th>
        <th><?php echo __('授权账号'); ?></th>
        <th class="wap_hide"><?php echo __('服务期限'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'service_end_time_'.($_GET['sort']['service_end_time']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('门店数量'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'max_stores_'.($_GET['sort']['max_stores']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('最大店员数'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'max_nums_'.($_GET['sort']['max_nums']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('账号状态'); ?></th>
        <th class="wap_hide"><?php echo __('授权模块'); ?></th>
        <th class="wap_hide"><?php echo __('门店详情'); ?></th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>



    <?php
    if($model){
        foreach($model as $k=>$v){ ?>
            <tr role="row" class="odd">
                <td class="sorting_1"><?php echo $k+1; ?></td>
                <td><?php echo $v->ucenter_name; ?></td>
                <td class="wap_hide"><?php echo date('Y-m-d',strtotime($v->service_start_time)).'至'.date('Y-m-d',strtotime($v->service_end_time)); ?></td>
                <td><?php echo $v->max_stores; ?></td>
                <td class="wap_hide"><?php echo $v->max_nums; ?></td>
                <td class="wap_hide"><?php echo $v->statusname; ?></td>
                <td class="wap_hide"><?php echo $v->authorization_module==1?__('门店管理'):__('所有功能'); ?></td>
                <td class="wap_hide"><a href="<?php echo url('doc/yf_shop_base/index',['wq'=>$v->ucenter_name])?>"><?php echo __('查看详情'); ?></a></td>
                <td>
                    <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>" >
                        <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"></i>
                    </a>
                    <a class="del" data-id="<?php echo $v->id;?>" rel="<?php echo $this->action('delete',['id'=>$v->id]); ?>" >
                        <i class="iconfont icon-shanchu" title="<?php echo __('删除'); ?>"></i>
                    </a>
                </td>
            </tr>
        <?php }
    } ?>

    </tbody>
    <tfoot>

    </tfoot>
</table>
<script src="<?php echo theme_url().'/qx.js';?>"></script>

<?php
echo $model->appends(page_opt())->render();
?>
 