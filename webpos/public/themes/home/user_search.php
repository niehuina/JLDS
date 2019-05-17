<style>
    .ellipsis{width: 130px}
</style>
<div class="bgf bore5 radius10 hp100 relative" id='searchUser'  :class="{hide:show!=2}" >
	<div class="auto"   >
		<div class="ser-tit"><a href="javascript:;" @click="retu" ><p><?php echo __('搜索会员');?></p></a></div>

		<div class="t_search">
			<div class="input-group" id="wrap">
		      <input type="text"  name="code"  id='code'  class="form-control warp" placeholder="<?php echo __('输入手机号、会员账号名称');?>">
		      <span class="input-group-btn nonekey">
		        <button class="btn btn-default" type="button" id="btnserarch" @click="click"><span class="iconfont icon-search white "></span></button>
		      </span>

		    </div><!-- /input-group -->

		</div>

		<div  class="softkeys"  data-target="input[name='code']" style="display: none;"></div>
    </div>
	<div class="swiper-container swiper-container4 ser-list ">
        <div class="swiper-wrapper">
            <div class="member-infor swiper-slide hauto" @click="confirm(v.id,v.ucenter_name,v.user_minimum_living_status)" v-for = "v in users">
                <a href="javascript:;" >
                    <dl class="member-infor-dl1">
                        <dt><i class="iconfont icon-member-user"></i> </dt>
                        <dd class="ucname ellipsis wp70">{{v.ucenter_name}}</dd>
                    </dl>
                    <dl>
                        <dt><i class="iconfont icon-member-phone"></i></dt>
                        <dd>{{v.phone}}</dd>
                    </dl>
                 </a>
            </div>
        </div>
        <div class="swiper-scrollbar swiper-scrollbar4"></div>
        
    </div>
	<div class="auto zhekou" style="display: none;">
        <div class="pd20"  >
            <a v-for= "info in infos"  href="javascript:;"  @click="zhekou(info.discount,info.status,$event)" class="member-card fff"    >

              <div class="clearfix">
              <span class="card-user-n ellipsis">{{zkname}}</span>
              <strong v-if="info.discount == 0 " class="fr"><?php echo __('无折扣');?></strong>
              <strong v-else class="fr">{{info.discount}}<?php echo __('折');?></strong>
              </div>

                <p><?php echo __("NO:") ?>{{info.numbers}}</p>
                <time><?php echo __('有效日期：');?>{{ info.started}}<?php echo __('至');?>{{info.ended}}</time>
            </a>
        </div>  
    </div>
</div>	
