<?php
$sort['shop_id'] = request_data('shop_id');
?>
<table class="table table-bordered table-hover dataTable" >
  <thead>
    <tr role="row">
      <th><?php echo __('营收'); ?></th>
      <th><?php echo __('概况'); ?></th>
      <th><?php echo __('现金'); ?></th>
      <th><?php echo __('银联'); ?></th>
      <th><?php echo __('微信'); ?></th>
      <th><?php echo __('支付宝'); ?></th>
      <!-- <th><?php// echo __('会员余额'); ?></th> -->
      <!--<th><?php /*echo __('小票扫码'); */?></th>-->
      <th><?php echo __('所属门店'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php 
    if($model){
      foreach($model as $k=>$list){  
        foreach($list as $k1=>$v){

          foreach( config('payment')  as $_k1=>$v1){

            if($_k1 == 'cash' && $k1 == '现金收支'){
              }else{
                $sum[$_k1] += $v->$_k1;
              }
         }
         if($k1=='总计'){
          foreach( config('payment')  as $_k1=>$v1){

            $v->$_k1 = $sum[$_k1]?:"-";     
          } 
          unset($sum);
        }
        ?>
        <tr role="row" class="">
          <td class="sorting_1"><?php echo __($k1); ?></td>
          <td><?php echo $v->first; ?></td>
          <td><?php echo $v->cash; ?></td>
          <td><?php echo $v->unionpay; ?></td>
          <td><?php echo $v->wepay; ?></td>
          <td><?php echo $v->alipay; ?></td>
          <!-- <td><?php //echo $v->member; ?></td> -->
          <!--<td><?php /*echo $v->xpsm; */?></td>-->
          <td><?php echo $k; ?></td> 
        </tr>
        <?php 
      }
    } 
  }
  ?>
</tbody>
</table>
