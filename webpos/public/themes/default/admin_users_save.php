<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('超级管理员账号管理');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('超级管理员账号管理'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('超级管理员账号管理'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label ><?php echo __('账号'); ?></label>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\w_]/g,'');" id="user" value="<?php echo $info->user;?>" name='user' placeholder="请输入账号">
            </div>
            <div class="form-group">
                <label><?php echo __('密码'); ?></label>
                <input type="password" class="form-control" id="pwd"  name='pwd' placeholder="请输入密码">
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

