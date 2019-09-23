<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
	.weui-panel__bd a{
		border: 1px solid #fff;
	}
	.weui-media-box:before{
		
	}
	.weui-panel__bd{
		border-bottom: none;
	}
</style>
<header class="header" >
	<span class="header_til">信用卡中心</span>
</header> 
<div class="" style="height: 50px;"></div> 
<!----> 
<div class="" style="height:175px;">
  <?php if(is_array($referInfo)): $i = 0; $__LIST__ = $referInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($i == 1): ?><a href="<?php echo U('Item/cdetail').'?id='.$vo['ID'];?>">
		  <div class="fl" style="width: 40%; padding:20px 10px; box-sizing: border-box;">
			  <div  style=" height: 63px;  margin:0 10px;">
				  <h4 class="weui-media-box__title" style="color: #1d84ff;font-weight: bold"><?php echo ($vo['Name']); ?></h4>
				  <p class="weui-media-box__desc"><?php echo ($vo['Intro']); ?></p>
			  </div>
			  <div  style="width: 101px; height: 62px; margin-right:0">
				  <img class="weui-media-box__thumb" src="<?php echo ($vo["Logurl"]); ?>" style="width: 101px; height: 62px;">
			  </div>
		  </div>
		</a>
	  <?php else: ?>
		  <div class="weui-panel__bd fr" style="width:60%;<?php if($i == 2): ?>border-left:2px solid #F9F9FB;border-bottom:2px solid #F9F9FB<?php elseif($i == 3): ?>border-left:2px solid #F9F9FB;<?php endif; ?>">
			  <a href="<?php echo U('Item/cdetail').'?id='.$vo['ID'];?>" class="weui-media-box weui-media-box_appmsg"  >
				  <div class="weui-media-box__bd">
					  <h4 class="weui-media-box__title" style="color: <?php if($i == 2): ?>#FFC550<?php elseif($i == 3): ?>#FF5050<?php endif; ?>;font-weight: bold"><?php echo ($vo["Name"]); ?></h4>
					  <p class="weui-media-box__desc"><?php echo ($vo["Intro"]); ?></p>
				  </div>
				  <div class="weui-media-box__hd" style="width: 60px; height: 36px; margin-right:0">
					  <img class="weui-media-box__thumb" src="<?php echo ($vo["Logurl"]); ?>" style="width: 60px; height: 36px;">
				  </div>
			  </a>
		  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
 <div class="clear"></div>
</div>


<!---->
<section class="hd"></section>
 <section class="comt_til comt_til1" style="padding: 10px;">
	<span class="fl" style="border-left: 3px solid #5461eb;"> 快速办卡</span>
	<a href="<?php echo U('Item/clist');?>" class="fr" style="color:#999;">更多</a>
	<div class="clear"></div>
 </section>
<ul class="ul_zhineng ul_zhineng4 ul_zhan">
	<?php if(is_array($bankInfo)): $i = 0; $__LIST__ = $bankInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Item/clist').'?BankID='.$vo['ID'];?>" style="width:33.3%;">
			<div class="benk_logo">
				<!-- <?php if(in_array(($i), explode(',',"1,2,3"))): ?><img src="/Public/images/icon_miao.png" class="icon_miao"><?php endif; ?>
				<img src="<?php echo ($vo["Logurl"]); ?>" style="width: 33px; height: 33px;" class="bank_img"> -->
				<img src="<?php echo ($vo["Logurl"]); ?>" style="width: 44px; height: 44px;" class="bank_img">
				<div class="icon_miao">
					<?php echo ($vo["Desc"]); ?>
				</div>
			</div>
			<p class="p1" style="font-size: 16px; padding: 5px 0;"><?php echo ($vo["BankName"]); ?></p>
			<p class="p2"><?php echo ($vo["Intro"]); ?></p>
		</a><?php endforeach; endif; else: echo "" ;endif; ?>
	<div class="clear"></div>
	<div class="more"></div>
  </ul>
<!--end-->
  <section class="hd"></section>
  <section class="comt_til comt_til1" style="padding: 10px;">
	<span class="fl" style="border-left: 3px solid #5461eb;"> 快速办卡</span>
	<a href="<?php echo U('Item/clist');?>" class="fr" style="color:#999;">更多</a>
	<div class="clear"></div>
 </section>
 
 <section>
	 <?php if(is_array($Info)): $i = 0; $__LIST__ = $Info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl class="dl_hot dl_hot1"  >
			 <a href="<?php echo U('Item/cdetail').'?id='.$vo['ID'];?>" class="k_logo" style="top:18px"><img src="<?php echo ($vo["Logurl"]); ?>" style="width:86px; height: 52px; "></a>
			 <a href="<?php echo U('Item/cdetail').'?id='.$vo['ID'];?>">
				 <dt class="dt1"><?php if(mb_strlen($vo['Intro'])<15){echo $vo['Intro'];}else{echo mb_substr($vo['Intro'],0,13)."...";} ?></dt>
				 <dt class="dt2" style="padding-top: 5px;">
					 <label class="fl lab_1" ><?php echo ($vo["BankName"]); ?></label>
					 <label class="fl lab_2">专享</label>
					 <span class="fr"><label  class="span_label">
						 <?php if($vo["AppNumbs"] >= 100000000): echo (round($vo['AppNumbs']/100000000,1)); ?>亿<?php elseif($vo["AppNumbs"] >= 10000): echo (round($vo['AppNumbs']/10000,1)); ?>万<?php else: echo ($vo["AppNumbs"]); endif; ?></label>人申请</span>
				 </dt>
			 </a>
		 </dl><?php endforeach; endif; else: echo "" ;endif; ?>
 </section>
 <div style="width:100%;height:60px"></div>
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