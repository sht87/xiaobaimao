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
		<style type="text/css">
			.dt1{
				overflow: hidden;
				text-overflow:ellipsis;
				white-space: nowrap;
			}
		</style>
	</head>
	<body> 
		<header class="header" >
			<a href="javascript:history.go(-1)" class="go_left"></a> 
			<span class="header_til">找借贷</span>  
		</header> 
		<div class="" style="height: 50px;"></div> 
	  <!---->   
	  <!---->
	    <section style="padding: 10px 0;">
		 	<dl class="dl_hot">
		 		<a href="javascript:void(0);" class="k_logo"><img src="<?php echo ($infos["Logurl"]); ?>" style="width:58px; height: 58px;"></a>
		 		<dd><?php echo ($infos["Name"]); ?><img src="/Public/images/icon_hot.png" style="width: 45px;"></dd>
		 		<dt class="dt1"><?php echo ($infos["Intro"]); ?></dt>
		 		<dt class="dt2">
					通过率: <span style="color: #5663EB"><?php echo ($infos["PassRate"]); ?></span>
				</dt>
		 	</dl>
		 	<ul class="ul_index2">
           	 <li>
           	 	<span class="sp_1">额度(元)</span>
           	 	<span class="sp_2"><?php echo ($infos["eduname"]); ?></span>
           	 </li>
           	 <li>
           	 	<span class="sp_1">借款期限</span>
           	 	<span class="sp_2"><?php echo ($infos["Jkdays"]); ?></span>
           	 </li>
           	 <li style="border-right: none;">
           	 	<span class="sp_1">日费率</span>
           	 	<span class="sp_2" style="color:#fb8828;"><?php echo ($infos["DayfeeRate"]); ?></span>
           	 </li>
           	 <div class="clear"></div>
          </ul>
          <section class="borderbt8"></section>
		 </section>
		 
		 <!---->
	  <!-- <?php if($infos['Downconts']):?>
	  	 		  <section class="hd"></section> 
	  	 		 <section class="comt_til comt_til1" style="padding: 10px 0;">
	  	 			<span> 下款攻略</span>
	  	 		 </section>
	  	 		 <ul class="jie_ul">
	  	 		  <?php foreach($infos['Downconts'] as $k=>$v):?>
	  	 		 	<li><a href="javascript:void(0);"><?php echo ($v); ?></a></li>     
	  	 		  <?php endforeach;?>
	  	 		 </ul>
	  	 <?php endif;?> 
	  		  -->
		 <!---->
		  <section class="comt_til comt_til1" style="padding: 10px 0;">
			<span> 申请条件</span>
		 </section>
		 <section class="shen_box">
		  <?php foreach($conditArr as $k=>$v):?>
		 	<a href="javascript:void(0);" style="height:20px; border-radius:20px"><?php echo ($v); ?></a>
		  <?php endforeach;?>
		 	<div class="clear"></div>
		 </section>
		 <!--end-->
		 
		 <!---->
		  <section class="comt_til comt_til1" style="padding: 10px 0;">
			<span> 所需材料</span>
		 </section>
		 <section class="shen_box">
		   <?php foreach($needArr as $k=>$v):?>
		 	 <a href="javascript:void(0);" style="height:20px; border-radius:20px"><?php echo ($v); ?></a>
		   <?php endforeach;?>
		 	 <div class="clear"></div>
		 </section>
		 <!--end-->
		 
		 <section class="center">
		 	 <p><img src="/Public/images/shen_1.png" style="width: 91px;"> </p>
			 <p style="color:#666666;"> 申请人数<label style="color:#5461eb; font-size: 18px;"><?php if($infos['AppNumbs'] >= 100000000): echo (round($infos['AppNumbs']/100000000,1)); ?>亿<?php elseif($infos['AppNumbs'] >= 10000): echo (round($infos['AppNumbs']/10000,1)); ?>万<?php else: echo ($infos['AppNumbs']); endif; ?>人</p>
		 	 <!--<p style="color:#666666;"> 申请人数<label style="color:#5461eb; font-size: 18px;"><?php echo ($infos["AppNumbs"]); ?>人</p>-->
		 </section>
		 <!---->
		 <div style="width:100%;height:80px;"></div>
		 
		 <section style="position:fixed;bottom:10px;width:100%">
				<a href="<?php  if(session('loginfo')['UserID']){ echo $infos['Openurl']; }else{ echo U('Login/index').'?back='.$_SERVER['REQUEST_URI']; } ?>" class="btn_want" style="width: 90%;">立即申请</a>
		 </section>
		 
		<!--页面悬浮-->
		<div class="">
		  <?php if($GLOBALS['BasicInfo']['Ytstatus']=='3'):?>
			<a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
		  <?php endif;?>
			<a href="<?php echo U('Index/index');?>" class="suspend_icon2"><img src="/Public/images/suspend_icon2.png"></a>
			<a href="javascript:history.go(-1)" class="suspend_icon3"><img src="/Public/images/suspend_icon3.png"></a>
		</div>
		<!--页面悬浮 end-->
		 <script src="/Public/js/jquery-2.1.4.js"></script>
	     <script src="/Public/js/fastclick.js"></script>
		 <script>
			  $(function() {
			    FastClick.attach(document.body);
			  });
		 </script>
		 <script src="/Public/js/jquery-weui.min.js"></script> 
		 <script src="/Public/js/common.js"></script> 
		 
		 
		    
	</body>
</html>