<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('账号权限设置');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('账号权限设置'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"> <i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('账号权限设置'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">

            <div class='row'>
                <input type="hidden" name="users_id" value="<?php echo $uid;?>">
                <input type="hidden" name="roles_id" value="<?php echo get_data('id');?>">
                <div class="form-group">
                    <label class=""><?php echo __('所选角色'); ?></label>
                    <div class="list-module">
                        <select  class="form-control wp100" id="roles" name='roles_id' disabled>
                            <option value=0><?php echo __('请选择'); ?></option>
                            <?php if($roles){
                                foreach($roles as $v){?>
                                    <option value="<?php echo $v['id'] ; ?>" <?php if(get_data('id')==$v['id']){ ?>selected<?php }?> > <?php echo $v['title']; ?> </option>
                                <?php }} ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class=""><?php echo __('是否启用'); ?></label>
                    <div class="list-module">
                        <input type="checkbox" class="open_status" id="a" value=1 name='status' <?php if($infomation->status == 1){?>checked<?php }?> >
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="form-group">
                    <label><?php echo __('角色权限设置'); ?></label>
                    <div class="list-module">

                        <button type="button" class="btn btn-sm checkbox-toggle bg-power"><i class="iconfont icon-all-power"></i><?php echo __('全部权限'); ?>
                    </div>
                </div>


                    <div class="">

                        <?php
                        if($qx){
                            foreach($qx as $key=>$value)
                            {
                                ?><div class="form-group form-group-size"><div class="list-module">
                             
                                <input type="checkbox"  class='iCheck' data-id="<?php echo $value->id; ?>" <?php if($info){ if(in_array($value->slug,$info)){?>checked <?php }}?> name='slug[]' value="<?php echo $value->id ?>">
                                <label for='a1'>
                                    <?php echo $value->title; ?>
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php if ($value->list)
                            {
                                foreach ($value->list as $k => $v)
                                {
                                    ?>
                                    <input type="checkbox" data-pid="<?php echo $v->pid; ?>"  class='iCheck dj' <?php if($info){ if(in_array($v->slug,$info) || $v->slug == 'autocomplete' || $v->slug == 'ajax_sync' || $v->slug == 'sync'){?>checked <?php }}?> name='slug[]' value="<?php echo $v->id ?>">
                                    <label for='a1'>
                                        <?php echo $v->title; ?>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php }
                            }?>
                        </div></div>
                            <?php   } 
                         }?>
                    </div>
                </div>

            </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <a href="<?php echo $this->action('index');?>" class="btn btn-default"><?php echo __('取消');?></a>
            <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
        </div>
        </form>
    </div>
</section>


<?php $this->end(); ?>

