<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('门店详情');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('门店详情 '); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('门店详情'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">

                <label><?php echo __('授权账号'); ?></label>
                <select class="form-control select2 list-module" name="user_id" id="shop_user_id" rel="<?php echo $this->action('ajax_users');?>" >
                    <option value=""><?php echo __('请选择授权账号'); ?></option>
                    <?php foreach($user as $k=>$v){?>
                        <option  value="<?php echo $v['id'];?>" <?php if($info->user_id == $v['id']){?>selected<?php }?> ><?php echo $v['ucenter_name'];?></option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo __('门店数量'); ?></label>
                <input type="text" class="form-control" id="max_stores" value="<?php echo $info->max_stores;?>" name='max_stores' readonly>
            </div>
            <div class="form-group">
                <label><?php echo __('门店名称'); ?></label>
                <input type="text"  class="form-control" id="title" value="<?php echo $info->title;?>" name='title' maxlength="75">
            </div>
            <div class="form-group">
                <label><?php echo __('门店地址'); ?></label>
                <input type="text" class="form-control" id="address" value="<?php echo $info->address;?>" name='address' >
            </div>

            <div class="form-group">
                <label><?php echo __('联系方式'); ?></label>
                <div class="input-group list-module">
                    <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <input type="text"  onkeyup="value=value.replace(/[^\d]/g,'')" class="form-control" id="phone" value="<?php echo $info->phone;?>" name='phone' placeholder="" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
            </div>

            <div class="form-group">
                <label><?php echo __('门店店员数'); ?></label>
                <input type="number" class="form-control" id="shop_num" value="<?php echo $info->shop_num;?>" name='shop_num'>
            </div>
            <?php if(get_data('id')){?>
                <input type="hidden" name="id" value="<?php echo get_data('id');?>" id="ucenter_id">
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


