<?php
$sort['wq'] = request_data('wq');
?>
<style>
    .table td{text-align: left !important;vertical-align: middle !important;}
</style>
<table class="table table-bordered table-hover dataTable"  >
    <thead>
    <tr role="row">
        <th><?php echo __('商品分类'); ?></th>
        <th><?php echo __('操作'); ?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    if($model){
        foreach($model as $v){   ?>
            <tr role="row" class="odd" data-cat_parent_id="<?php echo $v['cat_parent_id'];?>" data-id="<?php echo $v['id'];?>">
                <td data-id="<?php echo $v['id'];?>">
                    <!--<i class="fa fa-fw fa-plus-square-o"></i>-->
                    <?php switch($v['level']){
                        case 2:
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                            break;
                        case 3:
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            break;
                        case 4:
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            break;
                    }?>
                    <i class="fa fa-fw fa-minus-square-o"></i>
                    <?php echo $v['cat_name']; ?>
                </td>
                <td>
                    <?php if($v['level'] != 4){?>
                        <a href="<?php echo $this->action('add',['cat_parent_id'=>$v['id']]); ?>" data-url="doc/yf_goods_cat/add">
                            <i class="fa fa-fw fa-plus-square" title="<?php echo __('添加'); ?>"></i>
                        </a>
                    <?php }?>
                    <?php if($v['type'] != 1){?>
                        <a href="<?php echo $this->action('edit',['id'=>$v['id']]); ?>" data-url="doc/yf_goods_cat/edit" >
                            <i class="iconfont icon-bianji" title="<?php echo __('编辑'); ?>"></i>
                        </a>
                    <?php }?>
                    <a class="del" data-id="<?php echo $v['id'];?>" rel="<?php echo $this->action('delete',['id'=>$v['id']]); ?>" data-url="doc/yf_goods_cat/delete">
                        <i class="iconfont icon-shanchu" title="<?php echo __('删除'); ?>"></i>
                    </a>
                </td>
            </tr>
        <?php }
    } ?>

    </tbody>
    <tfoot>

    </tfoot>
</table>

<script>
    $(function(){
        $('.show').click(function(){
            var d = $(this);
            var v = d.data('id');
            var url = "<?php echo $this->action('ajax_list'); ?>";
            if(d.children().attr('class') == 'fa fa-fw fa-minus-square-o'){
                $.get(url,{"id":v},function(obj){
                    if(obj){
                        d.children().removeClass("fa-minus-square-o").addClass('fa-plus-square-o');
                    }
                    $.each(obj,function(i,val){
                        $('.odd').each(function(){
                            var s = $(this).data('id');
                            if(val == s){
                                $(this).css('display','none');
                            }
                        });

                    });
                },'json');
            }else{
                d.children().removeClass("fa-plus-square-o").addClass('fa-minus-square-o');
                $.get(url,{"id":v},function(obj){
                    $.each(obj,function(i,val){
                        $('.odd').each(function(){
                            var s = $(this).data('id');
                            if(val == s){
                                $(this).children().eq(0).children().removeClass("fa-plus-square-o").addClass('fa-minus-square-o');
                                $(this).css('display','');
                            }
                        });

                    });
                },'json');
            }


        });

    });

</script>