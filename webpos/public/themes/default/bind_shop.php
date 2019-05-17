<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('同步店铺');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('同步店铺'); ?>
       
    </h1>
</section>
<!-- Main content -->
<section class="content clearfix">
     <div class="col-md-9" style="width: 9px;">
          <div class="nav-tabs-custom">
            
              <div class="tab-pane" id="settings">

                     <p style="display: block;margin-top: 10px;" >
                      <a href="javascript:;" id="bt-store" class="btn btn-info" rel="<?php echo base_url().'/doc/bind_shop/sync_store'?>" ><?php echo __('同步店铺') ?></a>
                    </p>
                  
            </div>
          </div>
        </div>
</section>

<?php $this->end(); ?>
