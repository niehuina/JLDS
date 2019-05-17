<?php
$sort['wq'] = request_data('wq');
?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('授权账号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'user_id_'.($_GET['sort']['user_id']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('店员数'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'shop_num_'.($_GET['sort']['shop_num']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('门店名称'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'title_'.($_GET['sort']['title']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('门店地址'); ?></th>
        <th class="wap_hide"><?php echo __('联系方式'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'phone_'.($_GET['sort']['phone']?:'desc') ]); ?>" ></a>
        </th>
        <th class="wap_hide"><?php echo __('服务期限'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'user_id_'.($_GET['sort']['user_id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($model){
        foreach($model as $k=>$v){ ?>
            <tr role="row" class="odd">
                <td class="sorting_1"><?php echo $k+1; ?></td>
                <td><?php echo $v->users->ucenter_name; ?></td>
                <td class="wap_hide"><?php echo $v->shop_num; ?></td>
                <td><?php echo $v->title; ?></td>
                <td class="wap_hide"><?php echo $v->address; ?></td>
                <td class="wap_hide"><?php echo $v->phone; ?></td>
                <td class="wap_hide"><?php echo date('Y-m-d',strtotime($v->users->service_start_time)).'至'.date('Y-m-d',strtotime($v->users->service_end_time)); ?></td>
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