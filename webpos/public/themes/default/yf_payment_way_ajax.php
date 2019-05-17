<?php
$sort['wq'] = request_data('wq');

$qx = new \cs\controller_home();
$arr = $qx->operation_authority(1,1);

?>
<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <th><?php echo __('序号'); ?></th>
        <th><?php echo __('支付方式'); ?></th>
        <th><?php echo __('状态'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row">
                <td class="sorting_1"><?php echo $v->id; ?></td>
                <td><?php echo $v->PaymentwayName; ?></td>
                <td><?php echo $v->statusname; ?></td>
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