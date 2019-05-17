<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('员工管理');?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo __('员工管理'); ?>
    <small>
       <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i><?php echo __('列表'); ?>
      </a>
    </small>
  </h1>
</section>
<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('员工管理'); ?></h2>
    <div class='box box-primary box-absolute'>
    <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
    <div class="box-body">
    <div class="form-group">
      <label><?php echo __('员工账号'); ?></label>
      <input type="text" class="form-control" id="user" onkeyup="this.value=this.value.replace(/[^\w_]/g,'');" value="<?php echo $model->user;?>" name='user' <?php if(get_data('id')){?>readonly<?php }?>>
    </div>
    <div class="input-group form-group">
        <label><?php echo __('密码'); ?></label>
        <input type="text" class="form-control" id="shop_user_pwd" value="" name='pwd' placeholder="请输入密码" >
        <span class="input-group-btn input-btn-absolute">
        <a class="btn btn-warning btn-flat shop_pwd" type="button"><?php echo __('显示/隐藏') ?></a>
        </span>
    </div>

    <div class="form-group">
        <label><?php echo __('岗位角色'); ?></label>
        <select type="" class="form-control" id="role_id" name='role_id'>
            <option value=""><?php echo __('请选择'); ?></option>
            <?php if($tree){
                foreach($tree as $v){?>
                    <option value=<?php echo $v['id']; ?> <?php if($model->role_users->role_id==$v['id']){?>selected<?php }?>> <?php echo $v['title']; ?> </option>
            <?php }} ?>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo __('所属门店'); ?></label>
        <select type="" class="form-control" id="yf_shop_base_id" name='yf_shop_base_id' >
          <option value=""><?php echo __('请选择'); ?></option>
          <?php if($stores){
            foreach($stores as $v){ ?>
              <option value="<?php echo $v['id'] ; ?>" <?php if($model->yf_shop_base_id==$v['id']){?>selected<?php }?> > <?php echo $v['title']; ?> </option>
              <?php }} ?>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo __('编号'); ?></label>
        <input type="text" class="form-control" id="num" <?php if($model->num){ ?> readonly <?php } ?> value="<?php echo $model->num;?>" name='num' >
    </div>
    <div class="form-group">
        <label><?php echo __('姓名'); ?></label>
        <input type="text" class="form-control" id="nickname" value="<?php echo $model->nickname;?>" name='nickname' >
    </div>
    <div class="form-group">
        <label><?php echo __('性别'); ?></label>
        <div class="list-module text-module mt10 mb10">
            <label class="block">
            <input type="radio" class="iCheck" name="sex"  <?php if($model->sex==1){ ?> checked <?php } ?> value="1"  >
            <?php echo __('男'); ?>
            </label>
            <label class="block">
              <input type="radio" class="iCheck" name="sex"  <?php if($model->sex==2){ ?> checked <?php } ?> value="2">
              <?php echo __('女'); ?>
            </label>
            <label class="block">
              <input type="radio" class="iCheck" name="sex"  <?php if($model->sex==3){ ?> checked <?php } ?> value="3">
              <?php echo __('保密'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label><?php echo __('手机号码'); ?></label>
        <div class="input-group list-module">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          <input type="text"  onkeyup="value=value.replace(/[^\d]/g,'')" class="form-control" id="phone" value="<?php echo $model->phone;?>" name='phone'  data-inputmask='"mask": "(999) 999-9999"' data-mask>
        </div>
        <!-- /.input group -->
    </div>
    <div class="form-group">
        <label><?php echo __('身份证'); ?></label>
        <input type="text" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" class="form-control" id="id_card" value="<?php echo $model->id_card;?>" name='id_card' >
        <?php if(get_data('id')){?>
        <input type="hidden" name="id" value="<?php echo get_data('id');?>">
        <?php }?>
    </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
    </div>
    </form>
    </div>
</section>
<?php $this->end(); ?>

