<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('会员管理');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('会员管理'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
            
        </small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('会员管理'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label><?php echo __('用户名'); ?></label>
                <input type="text" class="form-control" onkeyup= "if(!/^[A-Za-z0-9_-]{0,20}$/.test(this.value)){this.value='';}" id="ucenter_name" <?php if(get_data('id')){ ?> readonly <?php } ?> value="<?php echo $model->wp_users->ucenter_name;?>"  name='ucenter_name'>
            </div>

            <div class="form-group">
                <label><?php echo __('真实姓名'); ?></label>
                <input type="text" class="form-control" id="realname" value="<?php echo $model->wp_users->realname;?>"  name='realname' >
            </div>
<!--            --><?php //if(cookie('admin_level')==1){?>
<!--            <div class="form-group">-->
<!---->
<!--                <label>--><?php //echo __('所属商家'); ?><!--</label>-->
<!--                <select class="form-control select2" name="users_id">-->
<!--                    <option value="">--><?php //echo __('请选择所属商家'); ?><!--</option>-->
<!--                    --><?php //if($user){foreach($user as $k=>$v){?>
<!--                        <option  value="--><?php //echo $v['id'];?><!--" --><?php //if($model->users_id == $v['id']){?><!--selected--><?php //}?><!-- >--><?php //echo $v['ucenter_name'];?><!--</option>-->
<!--                    --><?php //}}?>
<!--                </select>-->
<!--            </div>-->
<!--            --><?php //}?>
            <div class="form-group">
                <label><?php echo __('性别'); ?></label>
                <div class="list-module text-module mt10 mb10">
                   <label class="block">
                        <input type="radio" class="iCheck" name="sex"  <?php if($model->wp_users->sex==1){ ?> checked <?php } ?> value="1"  >
                        <?php echo __('男'); ?>
                    </label>
                    <label class="block">
                        <input type="radio" class="iCheck" name="sex"  <?php if($model->wp_users->sex==2){ ?> checked <?php } ?> value="2">
                        <?php echo __('女'); ?>
                    </label>
                    <label class="block">
                        <input type="radio" class="iCheck" name="sex"   <?php if($model->wp_users->sex==3){ ?> checked <?php } ?> value="3">
                        <?php echo __('保密'); ?>
                    </label> 
                </div>
                
            </div>
            <div class="form-group">
                <label><?php echo __('生日'); ?></label>
                <div class="input-group date list-module">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control  datepicker pull-right"  value="<?php echo date('Y-m-d',$model->wp_users->bron);?>" name='bron'>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo __('手机号码'); ?></label>
                <div class="input-group list-module">
                    <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <input type="text"  onkeyup="value=value.replace(/[^\d]/g,'')" class="form-control" id="phone" <?php if($model->wp_users->phone){ ?> readonly <?php } ?> value="<?php echo $model->wp_users->phone;?>" name='phone' placeholder="" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                    <?php if($model->wp_users->phone){ ?>  
                    <div class="input-group-btn input-btn-absolute">
                        <a id="get_phone"  href="javascript:;" class="btn btn-primary btn-flat"  ><?php echo __('修改手机号');?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group" id="dis" <?php if($model->wp_users->phone){ ?> style="display: none;" <?php } ?> >
                <label><?php echo __('短信验证码'); ?></label>
                <div class="input-group list-module">
                     <input type="text"  class="form-control" id="code"  
                     <?php if($model->wp_users->phone){ ?>
                            name='get_code' 
                     <?php }else{ ?>
                            name="code"
                       <?php } ?>  value=""  >
                    <div class="input-group-btn ">
                        <button id="get_code"  href="javascript:;" rel="<?php echo base_url().'/fun/verify/send_sms';?>" class="btn btn-primary btn-flat btn-code" type="button" ><?php echo __('获取短信验证码');?></button>
                    </div>
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label>--><?php //echo __('邮箱'); ?><!--</label>-->
<!--                <div class="input-group list-module">-->
<!--                    <div class="input-group-addon">-->
<!--                        <i class="fa fa-envelope"></i>-->
<!--                    </div>-->
<!--                    <input type="text"  class="form-control" id="email" value="--><?php //echo $model->wp_users->email;?><!--" name='email' placeholder="Email">-->
<!--                </div>-->
<!--            </div>-->

            <div class="form-group">
                <label><?php echo __('是否享受折扣'); ?></label>
                <div class="list-module text-module mt10 mb10">
                    <label class="block">
                        <input type="radio" name="status"  checked class="iCheck zhikou yes" <?php if($model->status==1){ ?> checked <?php } ?> value=1  >
                        <?php echo __('是'); ?>
                    </label>
                    <label class="block">
                        <input type="radio" name="status" class="iCheck zhikou no"  <?php if($model->status==0){ ?> checked <?php } ?> value=0>
                        <?php echo __('否'); ?>
                    </label>
                </div>
                
            </div>
            <div class="form-group discount">
                <label><?php echo __('享受折扣'); ?></label>
                <input id="read" type="text"  class="form-control zhikou_0"   value="<?php echo $model->discount;?>" name='discount'  readonly placeholder="输入折扣范围为10-100">
            </div>

            <?php if(get_data('id')){?>
                <input type="hidden" name="id" value="<?php echo get_data('id');?>">
            <?php }?>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    <?php echo __('保存');?>
                </button>
            </div>
            </form>
        </div>
</section>
<?php $this->end(); ?>


 
