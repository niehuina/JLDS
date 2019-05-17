<?php
$sort['wq'] = request_data('wq');
?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('角色标识'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'slug_'.($_GET['sort']['slug']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('门店角色名称'); ?></th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>

    

    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row">
                <td class="sorting_1"><?php echo $v->id; ?></td>
                <td><?php echo $v->slug; ?></td>
                <td><?php echo $v->title; ?></td>
                <td>
                    <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>">
                        <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"></i>
                    </a>
                    <a class="del" data-id="<?php echo $v->id;?>" rel="<?php echo $this->action('delete',['id'=>$v->id]); ?>">
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
<?php
echo $model->appends(page_opt())->render();
?>