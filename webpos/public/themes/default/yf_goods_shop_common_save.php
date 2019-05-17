<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('商品管理');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('商品管理'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<script>

</script>

<!-- Main content -->
<section class="content">
     <h2 class="module-title"><?php echo __('商品管理'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','enctype'=>'multipart/form-data','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label><?php echo __('商品主图'); ?></label>
                <div class="list-module">
                    <div id="uploads">
                    <?php if($info->yf_goods_common->file){?><img src="<?php echo $info->yf_goods_common->file;?>" style="width: 200px;"><br /><a id="del-img"><?php echo __('删除'); ?></a><?php }?>
                    </div>
                    <input  class="f14" type="file" name="file" id="common_image" rel="<?php echo url('doc/upload/doupload')?>"/>
                    <input type="hidden" name="file" id="img" value="<?php echo $info->yf_goods_common->file;?>"/>
                    <input type="hidden" id="img_id" value="<?php echo $info->yf_goods_common->id;?>">
                    <input type="hidden" id="base_url" value="<?php echo base_url().'/doc/upload/delete'; ?>">
                </div>
                

            </div>
            <div class="form-group">
                <label ><?php echo __('商品条码'); ?></label>
                <input type="text" class="form-control" id="common_code" value="<?php echo $info->yf_goods_common->common_code;?>" name='common_code' placeholder="直接扫码、手动输入">

            </div>
            <div class="form-group">
                <label><?php echo __('商品名称'); ?></label>
                <input type="text" class="form-control" id="common_name" value="<?php echo $info->yf_goods_common->common_name;?>" name='common_name' placeholder="输入商品名称">
            </div>

            <div class="form-group">
                <label><?php echo __('商品分类'); ?></label>
                <select class="form-control select2" name="cat_id">
                    <option  value="">请选择商品分类</option>
                    <?php foreach($yf_goods_cat as $k=>$v){?>
                        <option  value="<?php echo $v['id'];?>" <?php if($info->yf_goods_common->cat_id==$v['id']){?>selected<?php } ?>><?php echo $v['cat_name']?></option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group ">
                <label><?php echo __('所属门店'); ?></label>
                <div class="list-module text-module mt10 mb10 label-wp">
                     <?php foreach($yf_shop_base as $k=>$v){?>
                       <label class="">
                            <input type="checkbox" class="iCheck" name="shops[]"  value="<?php echo $v['id'];?>"  <?php if($info->shop_id==$v['id']){?>checked<?php } ?> > <?php echo $v['title']?>
                       </label>
                       
                    <?php }?>
                </div>
               
            </div>  
            <div class="input-group form-group ">
                <label><?php echo __('商品重量'); ?></label>
                <input type="text" class="form-control" id="common_cubage" value="<?php echo $info->yf_goods_common->common_cubage;?>" name='common_cubage' >
                <span class="input-group-btn input-btn-absolute">
                <button class="btn btn-warning btn-flat" type="button">kg</button>
                </span>
            </div>

            <div class="form-group  " >
                <label><?php echo __('商品售价'); ?></label>

                <input type="text"  class="form-control" id="common_price" value="<?php echo $info->yf_goods_common->common_price;?>" name='common_price' placeholder="￥" >

            </div>
            <div class="form-group ">
                <label><?php echo __('市场价格'); ?></label>
                <input type="text"  class="form-control" id="common_market_price" value="<?php echo $info->yf_goods_common->common_market_price;?>" name='common_market_price' placeholder="￥">
            </div>

            <div class="form-group ">
                <label><?php echo __('商品库存'); ?></label>
                <input type="text" onkeyup="value=value.replace(/[^\d]/g,'')" class="form-control" id="common_stock" value="<?php echo $info->yf_goods_common->common_stock;?>" name='common_stock'>
            </div>
            <div class="form-group  ">
                <label><?php echo __('商品规格'); ?></label>
                <input type="text" onkeyup="value=value.replace(/[^\d]/g,'')" class="form-control" id="common_spec_name" value="<?php if($info->yf_goods_common->common_spec_name){echo $info->yf_goods_common->common_spec_name;}else{echo __('无');}?>" name='common_spec_name' placeholder="输入对应规格值名称" >
            </div>
            <div class="form-group">
                <label class=""><?php echo __('是否上架'); ?></label>
                <div class="list-module">
                    <input type="checkbox" class="open_status"  value=1  name='common_state' <?php if($info->yf_goods_common->common_state==1){?> checked <?php }?>/>
                </div>
            </div>


            <div class="form-group">
                <label class=""><?php echo __('是否参与折扣'); ?></label>
                <div class="list-module">
                    <input type="checkbox" class="open_status" value=1  name='common_discounts' <?php if($info->yf_goods_common->common_discounts==1){?> checked <?php }?>/>
                </div>
            </div>
            <?php if($info->yf_goods_common->id){?>
                <input type="hidden" name="common_id" value="<?php echo $info->yf_goods_common->id;?>">
            <?php }?>
            <?php if(get_data('id')){?>
                <input type="hidden" name="id" value="<?php echo get_data('id');?>">
            <?php }?>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <a href="<?php echo $this->action('index');?>" class="btn btn-default"><?php echo __('取消');?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
        </div>

        </form>
    </div>
</section>



<?php $this->end();?>

