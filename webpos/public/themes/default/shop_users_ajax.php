<?php
$sort['wq'] = request_data('wq');

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
            <th><?php echo __('账号'); ?></th>
            <th><?php echo __('角色');  ?></th>
            <th><?php echo __('门店');  ?></th>
            <th class="wap_hide"><?php echo __('编号'); ?></th>
            <th ><?php echo __('姓名'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'nickname_'.($_GET['sort']['nickname']?:'desc') ]); ?>" >
                </a>
            </th>
            <th class="wap_hide"><?php echo __('性别'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'sex_'.($_GET['sort']['sex']?:'desc') ]); ?>" >
                </a>
            </th>
            <th class="wap_hide"><?php echo __('手机号'); ?></th>
            <th class="wap_hide"><?php echo __('身份证号'); ?></th>
            <th class="wap_hide"><?php echo __('建档时间'); ?>
                <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'created_'.($_GET['sort']['created']?:'desc') ]); ?>" >
                </a>
            </th>
            <th><?php echo __('操作');      ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($model){
          foreach($model as $v){ ?>
          <tr role="row" class="odd">
              <td class="wap_hide"><?php echo $v->id; ?></td>
              <td ><?php echo $v->user; ?></td>
              <td ><?php echo $v->role_users->roles->title; ?></td>
              <td class="product-buyer-name"><?php echo $v->yf_shop_base->title; ?></td>
              <td class="wap_hide"><?php echo $v->num; ?></td>
              <td ><?php echo $v->nickname; ?></td>
              <td class="wap_hide">
                <?php if($v->sex==1){
                    echo "男"; 
                }elseif($v->sex==2){
                    echo "女"; 
                }else{
                    echo "保密";    
                }   ?>
            </td>
            <td class="wap_hide"><?php echo $v->phone; ?></td>
            <td class="wap_hide"><?php echo $v->id_card; ?></td>
            <td class="wap_hide"><?php echo date('Y-m-d ',$v->created); ?></td>
            <td>
                <a href="<?php echo $this->action('edit',['id'=>$v->id]); ?>" data-url="doc/shop_user/edit">
                 <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"></i>
                </a>
                <a class="del" data-id="<?php echo $v->id;?>" rel="<?php echo $this->action('delete',['id'=>$v->id]); ?>" data-url="doc/shop_user/delete">
                 <i class="iconfont icon-shanchu" title="<?php echo __('删除'); ?>"></i>
             </a>
         </td>
     </tr>
     <?php } } ?>
 </tbody>
 <tfoot>
 </tfoot>
</table>
    <script src="<?php echo theme_url().'/qx.js';?>"></script>
<?php
echo $model->appends(page_opt())->render();
?>