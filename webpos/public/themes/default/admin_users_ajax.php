<?php
$sort['wq'] = request_data('wq');
?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('账号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'user_'.($_GET['sort']['user']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>



    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row" class="odd">
                <td class="sorting_1"><?php echo $v->id; ?></td>
                <td><?php echo $v->user; ?></td>
                <td>
                    <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>">
                        <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"></i>
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