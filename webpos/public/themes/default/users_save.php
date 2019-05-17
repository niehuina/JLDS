<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('授权账号管理 ');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('授权账号管理'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('授权账号管理'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label ><?php echo __('账号'); ?></label>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\w_]/g,'');" id="ucenter_name" value="<?php echo $info->ucenter_name;?>" name='ucenter_name' <?php if(get_data('id')){?>readonly<?php }?>>
            </div>
            <div class="input-group form-group">
                <label><?php echo __('密码'); ?></label>
                <input type="text" class="form-control" id="shop_user_pwd" value="" name='pwd' placeholder="请输入密码" >
                <span class="input-group-btn input-btn-absolute">
                <a class="btn btn-warning btn-flat shop_pwd" type="button"><?php echo __('显示/隐藏') ?></a>
                </span>
            </div>
            <div class="form-group">
                <label><?php echo __('服务期限'); ?></label>
                <div class="list-module">
                    <div class="input-group date iblock">
                        <div class="input-group-addon iblock">
                            <i class="iconfont icon-riqi"></i>
                        </div>
                        <input type="text" class="form-control  datepicker pull-right"
                        value="<?php if($info->service_start_time){ echo $info->service_start_time;}else{echo date('Y-m-d');}?>"
                           name='service_start_time'>
                    </div>
                    <strong class="colspan"><?php echo __('至'); ?></strong>
                    <div class="input-group date iblock">
                        <div class="input-group-addon iblock">
                            <i class="iconfont icon-riqi"></i>
                        </div>
                        <input type="text" class="form-control  datepicker pull-right"
                        value="<?php if($info->service_end_time){ echo $info->service_end_time;}else{echo date('Y-m-d');}?>" name='service_end_time'>
                    </div>
                </div>
                
            </div>
            <div class="form-group">

                <label><?php echo __('授权模块'); ?></label>
                <select class="form-control" name="authorization_module">
                    <option  value="1" <?php echo $info->authorization_module==1?'selected':'';?> ><?php echo __('门店管理'); ?></option>
                    <option  value="2" <?php echo $info->authorization_module==2?'selected':'';?> ><?php echo __('其他功能'); ?></option>
                </select>
            </div>
            <div class="form-group">

                <label><?php echo __('最大店员数'); ?></label>
                <input type="number" class="form-control" id="max_nums" value="<?php echo $info->max_nums;?>" name='max_nums'>

            </div>
            <div class="form-group">
                <label><?php echo __('门店数量'); ?></label>
                <input type="number" class="form-control" id="max_stores" value="<?php echo $info->max_stores;?>" name='max_stores'>
            </div>
            <?php if(get_data('id')){?>
                <input type="hidden" name="id" value="<?php echo get_data('id');?>">
            <?php }?>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
        </div>
        </form>
    </div>
</section>



<?php $this->end(); ?>

