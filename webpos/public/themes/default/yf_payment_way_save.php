<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('支付方式配置');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('支付方式配置'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <div class='box box-primary'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">

            <div class="form-group">
                <label><?php echo __('是否启用'); ?></label>
                &nbsp; &nbsp; &nbsp;
                <label>
                    <input type="radio" name="status"  class="iCheck" <?php if($info->status==1){ ?>checked<?php } ?> value=1  >
                    <?php echo __('启用'); ?>
                </label>
                <label>
                    <input type="radio" name="status" class="iCheck"  <?php if($info->status==0){ ?>checked<?php } ?> value=0>
                    <?php echo __('不启用'); ?>
                </label>
            </div>
            <input type="hidden" name="id" value="<?php echo get_data('id');?>">
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
        </div>

        </form>
    </div>
</section>

<?php $this->end(); ?>


