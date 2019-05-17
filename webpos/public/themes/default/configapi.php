<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('ucenter配置');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('接口配置'); ?>
       
    </h1>
</section>
<!-- Main content -->
<section class="content clearfix bge pd0">
     <div class="">
          <div class="nav-tabs-custom bge">
            <ul class="nav nav-tabs">


            <?php
             $i = 0;
            foreach ($config as $key => $value) {
            ?>
            
              <li class="<?php if($i==0){?>active<?php }?>" ><a href="#activity_<?php echo $key;?>" data-toggle="tab"><?php echo $value['label'] ?></a></li>
            <?php $i++;} ?>   



              <!-- <li><a href="#settings" data-toggle="tab"><?php //echo __("同步数据");?></a></li> -->
            </ul>
            <div class="tab-content">
              



            <?php 
            $i = 0;
            foreach ($config as $key => $value) {
                $form = $value['form'];
            ?>
              <div class="<?php if($i==0){?>active<?php }?> tab-pane box-absolute pt20" id="activity_<?php echo $key;?>"> 
                 
                <form class="form-horizontal ajax"  method="post" action="<?php echo  $this->action('save'); ?>">
                    <input type="hidden" name="type" value="<?php echo $key ?>">
                　<?php 
                   
                    foreach ($form as $key => $v) {
                  ?>
                  <div class="form-group">
                    <label for="inputName" class=""><?php echo $key;?></label>

                    <input type="text" class="form-control" name="form[<?php echo $key; ?>]" value="<?php echo  $v; ?>" placeholder="<?php echo $key;?>">
                  </div>
                　<?php  }?>
               
                 <div class="box-footer">
                      <button type="submit" class="btn btn-primary"><?php echo  __('确认提交') ?></button>
                  </div>
                </form>
            
              </div>
              <!-- /.tab-pane -->
              <?php  $i++;} ?> 
              <div class="tab-pane" id="settings">
              
                    <!-- <p>
                      <a href="javascript:;" id="bt-user" class="btn btn-danger" rel="<?php //echo base_url().'/doc/users/sync_user'?>"> <?php //echo __('同步用户') ?></a>
                    </p> -->
                  
                    <!-- <p style="display: block;margin-top: 10px;" >
                      <a href="javascript:;" id="bt-store" class="btn btn-info" rel="<?php //echo base_url().'/doc/yf_shop_base/sync_store'?>" ><?php //echo __('同步店铺') ?></a>
                    </p> -->
                  
              </div>
            </div>
          </div>
        </div>
</section>

<?php $this->end(); ?>
