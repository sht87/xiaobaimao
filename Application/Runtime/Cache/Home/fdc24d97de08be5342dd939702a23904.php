<?php if (!defined('THINK_PATH')) exit();?>		<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
    <link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>
    <link rel="stylesheet" href="/Public/css/app.css">
    <title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
</head>
<body>

		<style>
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
		</style>
		<header class="header" style="background:none; position: fixed; left: 0; top:0; z-index: 999;">
			<a href="javascript:window.history.go(-1);" class="go_left"></a>
			<a href="<?php echo U('Member/news');?>" class="newss" id="<?php if($noread){echo 'havenoread';}?>"></a>
		</header>
		<div class="per_indextop">
          	    <div class="" style="padding:40px 10px 0 10px;">
          	    	<a href="<?php echo U('Member/info');?>" class="per_bg fl">
						<?php if($memInfo["HeadImg"] == null): ?><img src="/Public/images/photo.png">
						<?php else: ?>
							<img src="<?php echo ($memInfo["HeadImg"]); ?>"><?php endif; ?>

          	    	</a>
          	    	<p style="padding: 20px 0 0 80px; color:#ffffff;"><?php echo ($memInfo['TrueName']); ?>&nbsp;(<?php echo ($levelname); ?>)<br><?php echo (substr_replace($memInfo['Mobile'],'****',3,4)); ?>&nbsp;(&nbsp;工号&nbsp;:&nbsp;<?php echo session('loginfo')['UserID'];?>&nbsp;)</p>
          	    </div> 
          </div>
          <!---->
         <!--  <ul class="ul_pertop center">
          		<li style="border-right: 1px solid #eee;">
          		  <a href="<?php echo U('Member/income');?>">
          			<span>¥
          						<?php if($earnMoney == 0): ?>0.00
          						<?php else: ?>
          							<?php echo ($earnMoney); endif; ?>元
          					</span>
          			<p> 总收入  </p>
          		   </a>
          		</li>
          		<li style="border-right: 1px solid #eee;">
          		  <a href="<?php echo U('Member/wallet');?>">
          			<span>¥
          						<?php if($Money == 0): ?>0.00
          						<?php else: ?>
          							<?php echo ($Money); endif; ?>元
          					</span>
          			<p>已结算</p>
          		  </a>
          		</li>
          		<li>
          		  <a href="<?php echo U('Member/wallet');?>">
          			<span>¥
          						<?php if($memInfo['Balance'] == 0): ?>0.00
          						<?php else: ?>
          							<?php echo ($memInfo['Balance']); endif; ?>元
          					</span>
          			<p>可结算</p>
          		  </a>
          		</li>
          		<div class="clear"></div>
          </ul>                   -->            
		<!----> 
		 <!-- <section class="hd"></section> 
		 中间四个板块 start
		 <section>
		 	<ul class="ul_perlist">
		 		<li> 
		 			 <a href="<?php echo U('Member/rongke');?>" style="background: url(<?php echo ($bankinfos[1][0]['Backurl']); ?>) center no-repeat;background-size: 100% 100%;"> 
		 	 					<dl class="dl_perlist">
		 	 						<dd><?php echo ($bankinfos[1][0]['Name']); ?></dd>
		 	 						<dt><?php echo ($bankinfos[1][0]['Intro']); ?></dt>
		 	 					</dl>
		 			 </a> 
		 		</li>
		 		<li> 
		 			 <a href="<?php echo U('Member/buyagent');?>" class="a2" style="background: url(<?php echo ($bankinfos[2][0]['Backurl']); ?>) center no-repeat;background-size: 100% 100%;"> 
		 	 					<dl class="dl_perlist dl_perlist2">
		 	 						<dd><?php echo ($bankinfos[2][0]['Name']); ?></dd>
		 	 						<dt><?php echo ($bankinfos[2][0]['Intro']); ?> </dt>
		 	 					</dl>
		 			 </a> 
		 		</li>
		 		<li> 
		 			 <a href="javascript:void(0);" onclick="fnshares();" style="background: url(<?php echo ($bankinfos[3][0]['Backurl']); ?>) center no-repeat;background-size: 100% 100%;"> 
		 	 					<dl class="dl_perlist dl_perlist3">
		 	 						<dd><?php echo ($bankinfos[3][0]['Name']); ?></dd>
		 	 						<dt><?php echo ($bankinfos[3][0]['Intro']); ?></dt>
		 	 					</dl>
		 			 </a> 
		 		</li>
		 		<li> 
		 			 <a href="<?php echo U('Zenxin/index');?>" class="a2" style="background: url(<?php echo ($bankinfos[4][0]['Backurl']); ?>) center no-repeat;background-size: 100% 100%;">
		 	 					<dl class="dl_perlist dl_perlist4">
		 	 						<dd><?php echo ($bankinfos[4][0]['Name']); ?></dd>
		 	 						<dt><?php echo ($bankinfos[4][0]['Intro']); ?></dt>
		 	 					</dl>
		 			 </a>
		 				</li>
		 		<div class="clear"></div>
		 	</ul> 
		 	
		 </section> -->
		 <!--中间四个板块 end-->
		 <!--<div class="list_nav" style="padding-bottom: 0;">
           <a href="<?php echo U('info');?>" class="per_menu1"><span></span>基本信息</a>
           <a href="<?php echo U('News/pricelist');?>" class="per_menu2"><span></span>价格表</a>   
        </div>-->
        <section class="hd"></section> 

        <div class="list_nav" style="padding-bottom: 0;">
           <a href="<?php echo U('News/helps');?>" class="per_menu3"><span></span>新手帮助</a>  
            <!--<a href="<?php echo U('Member/share');?>" class="per_menu5"><span></span>分享推广</a>-->
            <a href="javascript:void(0);" onclick="fnshares();" class="per_menu4"><span></span>分享推广</a>
           <a href="javascript:void(0);" class="per_menu6" id="show-alert"><span></span>联系我们</a>
           <a href="<?php echo U('Member/setting');?>" class="per_menu7"><span></span>系统设置</a>
        </div>
		<div style="height: 60px"></div>
		<input type="hidden" id="Tel" name="Tel"  value="<?php echo $GLOBALS['BasicInfo']['QQa']; ?>"/>
        <!---->
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

    <a href="<?php echo U('Item/creditv');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')) echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')):?>
                <img src="/Public/images/ic_promote_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_promote_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找信用卡</p>
    </a>
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
		<script type="text/javascript">
			$(document).on("click", "#show-alert", function () {
				$.modal({
					title: "     ",
					text: "<p style=color:#5461eb>"+$('#Tel').val()+"</p>",
					buttons: [
						{text: "取消", className: "default"},
						{text: "确定", className: "default"},

					]
				});
			});
			//分享推广,先判断是否有权限
			function fnshares(){
				//校验
				$.ajax({
			        type:"POST",
			        url:"<?php echo U('Member/checkshares');?>",
			        dataType: "json",
			        success:function(data){
			          if(data.result==0){
			             $.alert(data.message);return false;
			          }
			          if(data.result==1){
			             //校验成功
			             window.location.href = data.des;
			          }
			        }
		      });
			}
		</script>