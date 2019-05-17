<?php

$qx = new \cs\controller_home();
$arr = $qx->operation_authority(null,1,1);

?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?></th>
        <th><?php echo __('门店角色'); ?></th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>



    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row">
                <td><?php echo $v->id; ?></td>
                <td><?php echo $v->title; ?></td>
                <td>
                    <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>" data-url="doc/acl_roles/edit">
                        <?php echo __('设置权限'); ?>
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