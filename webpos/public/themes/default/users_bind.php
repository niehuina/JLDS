<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('账号绑定');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('账号绑定'); ?>
    </h1>

</section>

<section class="content">
    <h2 class="module-title"><?php echo __('账号绑定'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('edit')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label ><?php echo __('绑定系统'); ?></label>
                <select class="">
                    <option><?php echo __('BBC商城系统');?></option>
                </select>
            </div>
            <div class="form-group">
                <label ><?php echo __('账号'); ?></label>
                <input type="text" class="form-control"  value="<?php if($ucenter_name){echo $ucenter_name;} ?>" name='user' <?php if($ucenter_id){?>readonly<?php }?>>

            </div>
            <div class="form-group">
                <label><?php echo __('密码'); ?></label>
                <input type="password" class="form-control" id="pwd"  name='pwd' placeholder="请输入密码">
            </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <?php if(!$ucenter_id){?>
                <button type="submit" class="btn btn-primary"><?php echo __('账号绑定');?></button>
            <?php }else{?>
                <span  class="btn btn-bad"><?php echo __('已绑定');?></span>
            <?php }?>
        </div>
        </form>
    </div>
</section>





<?php $this->end(); ?>

