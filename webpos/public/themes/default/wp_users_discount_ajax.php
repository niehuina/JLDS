<?php
$sort['wq'] = request_data('wq');

$qx = new \cs\controller_home();
$arr = $qx->operation_authority(1,1);

?>
<table class="table table-bordered table-hover dataTable" >
    <input type="hidden" id="qxz" value=<?php echo $arr;?>>

    <thead>
    <tr role="row">
        <th class="wap_hide" ><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" >
            </a>
        </th>
        <th class="wap_hide"><?php echo __('用户名'); ?></th>
        <th><?php echo __('真实姓名'); ?></th>
        <th class="wap_hide"><?php echo __('性别'); ?></th>
        <th><?php echo __('手机号码'); ?></th>
        <th class="wap_hide"><?php echo __('邮箱'); ?></th>
        <th class="wap_hide"><?php echo __('注册时间'); ?></th>
        <th ><?php echo __('累计购买金额'); ?></th>
       <!--  <th ><?php //echo __('累计充值金额'); ?></th> -->
        <th class="wap_hide"><?php echo __('会员折扣'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'discount_'.($_GET['sort']['discount']?:'desc') ]); ?>" >
            </a>
        </th>
        <th ><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row" class="odd">
                <td class="sorting_1 wap_hide"><?php echo $v->id; ?></td>
                <td class="wap_hide"><?php echo $v->wp_users->ucenter_name; ?></td>
                <td><?php echo $v->wp_users->realname; ?></td>
                <td class="sorting_1 wap_hide"><?php if($v->wp_users->sex==1){
                        echo "男";
                    }elseif($v->wp_users->sex==2){
                        echo "女";
                    }else{
                        echo "保密";
                    }   ?>
                </td>
                <td><?php echo $v->wp_users->phone; ?></td>
                <td class="wap_hide"><?php echo $v->wp_users->email; ?></td>
                <td class="wap_hide"><?php echo date('Y-m-d',$v->started); ?></td>
                <td><?php echo $v->wp_users->wp_order->sum('good_price')  ; ?></td>

                <td class="wap_hide"><?php echo $v->discount; ?></td>
                <td>

                    <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>"  data-url="doc/wp_users_discount/edit">
                       <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"  ></i>
                    </a>
                </td>
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
    <script src="<?php echo theme_url().'/qx.js';?>"></script>
<?php
echo $model->appends(page_opt())->render();
?>