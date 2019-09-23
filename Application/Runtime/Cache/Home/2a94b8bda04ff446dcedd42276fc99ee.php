<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
        <link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
		<link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>
        <link type="text/css" href="/Public/css/app.css"  rel="stylesheet"/>
		<title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
		 <style>
	      .swiper-container {
	        width: 100%;
	      } 
	
	      .swiper-container img {
	        display: block;
	        width: 100%;
	      }
	      .swiper-pagination-bullet-active{
	      	background: #000;
	      }
	      /**/
	     .weui-media-box{
	     	box-sizing: border-box;
	     }
	     .weui-media-box:before{
	     	 
	     }
	     #havenoread{
	     	    background: url(/Public/images/icon_smnews1.png) center no-repeat;
			    background-size: 18px auto;
			    display: block;
			    width: 30px;
			    height: 40px;
			    position: absolute;
			    right: 10px;
			    top: 0;
	     }
		  /*6.25*/
		  .btn_close{
			  position: absolute;
			  right: 10px;
			  top:10px;
			  z-index: 999999999999;
			  width:40px;
		  }
		  .btn_close img{
			  width: 40px;
			  height: 40px;
		  }
		  .tan_con{
			  z-index: 9999999;
			  padding: 10px;
		  }
		  .tan_con{ width: 80%;
			  position: fixed;
			  top:25%;
			  left: 10%;
			  z-index: 9999999; }
		.zhi_til1{
			position: relative;
		}
			 .zhi_til1 span{
				 padding: 0 10px;
				 position: relative;
				 z-index: 2;
			 }
			 .zhi_til1 .line{
				 width: 100%;
				 height:2px;
				 background: #eee;
				 position: absolute;
				 top:13px;
			 }
		 </style>
	</head>
	<body>
		<header class="header" style="background:none; position: absolute; left: 0; top:0; z-index: 999;">
			<a href="javascript:void(0);" class="go_left select_adress" style="width:auto;background: none;"><?php echo ($cityinfo["cityname"]); ?></a> 
			<a href="<?php echo U('Member/news');?>" class="newss" id="<?php if($noread){echo 'havenoread';}?>"></a>
		</header>
		
		 <div class="swiper-container"  >
	      <!-- Additional required wrapper -->
	      <div class="swiper-wrapper">
	        <!-- Slides -->
	        <?php foreach($banner as $k=>$v):?>
	           <div class="swiper-slide">
	             <a href="<?php echo ($v["Url"]); ?>">
	              <img src="<?php echo ($v["Pic"]); ?>" style="max-height: 171px;"/>
	             </a>
	           </div>
	        <?php endforeach;?>
	      </div>
	      <!-- If we need pagination -->
	      <div class="swiper-pagination"></div>
	    </div>
	    <!--banner end-->  
	    <section class="txt_laba apple">  
			 <ul> 
			   <?php foreach($aboutUs as $k=>$v):?>
				 <li class="overa icon_laba"><a href="<?php echo U('News/index');?>" style="color:red"><?php echo mb_substr($v['Title'],0,20,'utf-8');?></a></li>
			   <?php endforeach;?>
			 </ul>
		</section>
		<!--end-->
		<section class="hd"></section>
		<a href="<?php echo U('Register/content');?>"> <img src="/Public/images/homebg.jpg" style="width:100%;height:160px"/> </a>
		<!---->
		<ul class="ul_indexlist">
		  <?php foreach($cate as $k=>$v):?>
		 	<li><a href="<?php echo U('Item/index').'?cateid='.$v['ID'];?>"><img src="<?php echo ($v["Imageurl"]); ?>"><?php echo ($v["Name"]); ?></a></li>
		  <?php endforeach;?>
		 	<div class="clear"></div>
		 </ul>
		<!--
		  <section class="hd"></section>             
		  <div class="weui-panel__bd">
		    <a href="<?php echo U('Item/index');?>" class="weui-media-box weui-media-box_appmsg fl" style="width: 50%;">
		      <div class="weui-media-box__hd">
		        <img class="weui-media-box__thumb" src="/Public/images/nn.png">
		      </div>
		      <div class="weui-media-box__bd">
		        <h4 class="weui-media-box__title">贷款大全</h4>
		        <p class="weui-media-box__desc">汇聚各类网贷</p>
		      </div>
		    </a>
		    <a href="<?php echo U('Item/credit');?>" class="weui-media-box weui-media-box_appmsg fl" style="width: 50%;">
		      <div class="weui-media-box__hd">
		        <img class="weui-media-box__thumb" src="/Public/images/icon_dabing2.png">
		      </div>
		      <div class="weui-media-box__bd">
		        <h4 class="weui-media-box__title">办信用卡</h4>
		        <p class="weui-media-box__desc">下卡快额度高</p>
		      </div>
		    </a>
		    <a href="<?php echo U('Member/rongke');?>" class="weui-media-box weui-media-box_appmsg fl" style="width: 50%;">
		      <div class="weui-media-box__hd">
		        <img class="weui-media-box__thumb" src="/Public/images/mm.png">
		      </div>
		      <div class="weui-media-box__bd">
		        <h4 class="weui-media-box__title">我要赚钱</h4>
		        <p class="weui-media-box__desc">邀请朋友赚钱</p>
		      </div>
		    </a>
		    <a href="<?php echo U('Zenxin/index');?>" class="weui-media-box weui-media-box_appmsg fl" style="width: 50%;">
		      <div class="weui-media-box__hd">
		        <img class="weui-media-box__thumb" src="/Public/images/icon_dabing4.png">
		      </div>
		      <div class="weui-media-box__bd">
		        <h4 class="weui-media-box__title">黑名单查询</h4>
		        <p class="weui-media-box__desc">老被拒？看是否黑了</p>
		      </div>
		    </a>
		    <div class="clear"></div>
		  </div>
	  <!--智能推荐-->
	      <section class="hd"></section> 
	      <div class="zhi_til" style="margin-top: 20px;">
	      	<p>秒下款产品</p>
	      	<section class="zhi_til1"><span>同时申请4家以上，下款率达99%</span><div class="line"></div></section>
	      </div>
	      <ul class="ul_zhineng">
	        <?php foreach($tjitems as $k=>$v):?>
		      	<a href="<?php echo U('Item/detail').'?id='.$v['ID'];?>"> 
		      		<img src="<?php echo ($v["Logurl"]); ?>" style="width: 45px; height: 45px;">
		      		<p class="p1"><?php echo ($v["Name"]); ?></p>
		      		<p class="p2">
						<?php if($v["AppNumbs"] >= 100000000): echo (round($v['AppNumbs']/100000000,1)); ?>亿<?php elseif($v["AppNumbs"] >= 10000): echo (round($v['AppNumbs']/10000,1)); ?>万<?php else: echo ($v["AppNumbs"]); endif; ?>人申请
					</p>
		      	</a>
	        <?php endforeach;?>
	      	<div class="clear"></div>
	      </ul>
		<section class="hd"></section>
	  <!--智能推荐 end-->  
	  <!--热门借款-->
	  <section class="comt_til">
	  	<span>热门借款</span>
	  </section>
	    <?php foreach($hotitem as $k=>$v):?>
		<!--<a href="<?php echo U('Item/detail').'?id='.$v['ID'];?>" >-->
	      <section style="border-bottom: 1px solid #f7f7f7">
		 	<dl class="dl_hot">
		 		<span class="k_logo"><img src="<?php echo ($v["Logurl"]); ?>" style="width:58px; height: 58px;"></span>
		 		<dd><?php echo ($v["Name"]); ?><img src="/Public/images/icon_hot.png" style="width: 45px;"></dd>
				<dt class="dt1"><?php echo ($v["Intro"]); ?></dt>
				<dt class="dt2"><span style="color: #FF3D3D"><?php if($v["AppNumbs"] >= 100000000): echo (round($v['AppNumbs']/100000000,1)); ?>亿<?php elseif($v["AppNumbs"] >= 10000): echo (round($v['AppNumbs']/10000,1)); ?>万<?php else: echo ($v["AppNumbs"]); endif; ?></span>人申请</dt>
				<a href="<?php echo U('Item/detail').'?id='.$v['ID'];?>" class="btn_shenqing">申请</a>
			</dl>
			  <ul class="ul_index2">
				  <li>
					  <span class="sp_1">额度(元)</span>
					  <span class="sp_2"><?php echo ($v["eduname"]); ?></span>
				  </li>
				  <li>
					  <span class="sp_1">借款期限</span>
					  <span class="sp_2"><?php echo ($v["Jkdays"]); ?></span>
				  </li>
				  <li style="border-right: none;">
					  <span class="sp_1">日费率</span>
					  <span class="sp_2"  style="color:#fb8828;"><?php echo ($v["DayfeeRate"]); ?></span>
				  </li>
				  <div class="clear"></div>
			  </ul>
			  <section class="borderbt8"></section>
		 </section>
			<!--</a>-->
	   <?php endforeach;?>
	  <!--热门借款 end-->
	  <div style="height: 50px;"></div>


<!--引用底部文件 start-->
<div class="weui-tabbar" style="position: fixed; bottom: 0; left: 0; width: 100%;">
    <a href="<?php echo U('Index/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Index' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
         <?php if(CONTROLLER_NAME=='Index' && ACTION_NAME=='index'):?>
            <img src="/Public/images/ic_home_selected.png" alt=""/>
         <?php else:?>
            <img src="/Public/images/ic_home_normal.png" alt=""/>
         <?php endif;?>
        </div>
        <p class="weui-tabbar__label">首页</p>
    </a>
    <a href="<?php echo U('Item/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && ACTION_NAME=='index'):?>
                <img src="/Public/images/ic_loan_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_loan_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找借贷</p>
    </a>

    <!--<a href="http://www.jinrirong.com/forum.php?mod=forumdisplay&fid=38&mobile=2" class="weui-tabbar__item">-->
        <!--<div class="weui-tabbar__icon">-->
            <!--<img src="/Public/images/forumlt.png" alt=""/>-->
        <!--</div>-->
        <!--<p class="weui-tabbar__label">论坛</p>-->
    <!--</a>-->

    <!-- <a href="<?php echo U('Item/creditv');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')) echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')):?>
                <img src="/Public/images/ic_promote_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_promote_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找信用卡</p>
    </a> -->
    <a href="<?php echo U('Member/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Member' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
         <?php if(CONTROLLER_NAME=='Member' && ACTION_NAME=='index'):?>
            <img src="/Public/images/ic_mine_selected.png" alt=""/>
         <?php else:?>
            <img src="/Public/images/ic_mine_normal.png" alt=""/>
         <?php endif;?>
        </div>
        <p class="weui-tabbar__label">我的</p>
    </a>
</div>

<!--页面悬浮-->
<div class="">
  <?php if($GLOBALS['BasicInfo']['Ytstatus']=='3'):?>
    <a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
  <?php elseif((CONTROLLER_NAME=='Index' && ACTION_NAME=='index') && $GLOBALS['BasicInfo']['Ytstatus']=='2'):?>
    <a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
  <?php endif;?>
    <a href="<?php echo U('Index/index');?>" class="suspend_icon2"><img src="/Public/images/suspend_icon2.png"></a>
    <a href="javascript:history.go(-1)" class="suspend_icon3"><img src="/Public/images/suspend_icon3.png"></a>
</div>
<!--页面悬浮 end-->
<script src="/Public/js/jquery-2.1.4.js"></script>
<!-- <script src="/Public/js/fastclick.js"></script> -->
<script src="/Public/js/jquery-weui.min.js"></script>
<script src="/Public/js/common.js"></script>
<script type="text/javascript" src="/Public/artDialog/artDialog.min.js"></script>
<link rel="stylesheet" href="/Public/artDialog/skins/aero.css">
<script type="text/javascript" src="/Public/artDialog/iframeTools.js"></script>

<!-- <script type="text/javascript">
    $(function () {
        FastClick.attach(document.body);
    });
</script> -->
</body>
</html>
		<div style="display:<?php if($GLOBALS['BasicInfo']['Tcstatus']=='4' || $GLOBALS['BasicInfo']['Tcstatus']=='2'){echo '';}else{echo 'none';}?>;">
		<div style="display:<?php if(session('close')['isclose']===1 ){echo 'none';}?>;">
		<!--点击查看-->
		<div class="tan_alap" style="display: block;"></div>
			<div class="tan_con">
				<p style="text-align: right;"><img src="/Public/images/btn_close.png" class="btn_close"></p>
				<a href="<?php echo $GLOBALS['BasicInfo']['TanUrl'];?>">
				<img src="<?php echo $GLOBALS['BasicInfo']['TanImg'];?>" style="width: 100%;">
				</a>
			</div>
		</div>
		</div>
		<!--点击查看 end-->
<!--引用底部文件 end-->
<script src="/Public/js/swiper.min.js"></script> 
<script>
  $(".swiper-container").swiper({
    loop: true,
    autoplay: 3000
  });

  $('.tan_alap').click(function(){
	  $('.tan_alap').hide();
	  $('.tan_con').hide();
	  close();
  })
  $('.btn_close').click(function(){
	  $('.tan_alap').hide();
	  $('.tan_con').hide();
	  close();
  })
	function close() {
		$.ajax({
			url:"<?php echo U('Index/close');?>",
			type:"post",
			success:function () {
			}
		});
	}
</script>