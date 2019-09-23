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

		<header class="header"  >
			<a href="javascript:window.history.go(-1);" class="go_left"></a>
			 <span class="header_til"><?php echo ($title); ?></span>
		</header> 
	 
      <!---->
        <section> 
         	<div class="weui-cells" style="margin-top:50px;">
	          <a class="weui-cell weui-cell_access" href="<?php echo U('Member/newpwd');?>">
	            <div class="weui-cell__bd">
	              <p>修改登录密码</p>
	            </div>
	            <div class="weui-cell__ft">
	            </div>
	          </a>
	          <a class="weui-cell weui-cell_access" href="<?php echo U('News/pages',array('ID'=>1));?>">
	            <div class="weui-cell__bd">
	              <p>关于我们</p>
	            </div>
	            <div class="weui-cell__ft">
	            </div>
	          </a>  
	          
	          <a class="weui-cell weui-cell_access" href="<?php echo U('News/pages',array('ID'=>4));?>">
	            <div class="weui-cell__bd">
	              <p>版本介绍</p>
	            </div>
	            <div class="weui-cell__ft">
	            </div>
	          </a>
	        </div>
	        
     
	         
          <section class="hd"></section>
          <section class="center" style="padding: 10px 0;">
          	 <a href="javascript:void(0);" onclick="backoff()" style="color:#444444; font-size: 17px;">退出登录</a>
          </section>  
         </section>
     
	    <!---->
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
<script src="/Public/js/jquery-weui.min.js"></script>
<script src="/Public/js/common.js"></script>
<script src="/Public/js/fastclick.js"></script>
<script type="text/javascript" src="/Public/artDialog/artDialog.min.js"></script>
<link rel="stylesheet" href="/Public/artDialog/skins/aero.css">
<script type="text/javascript" src="/Public/artDialog/iframeTools.js"></script>
<script src="/Public/js/mui.min.js"></script>

<script type="text/javascript">
    $(function () {
        FastClick.attach(document.body);
    });
</script>
</body>
</html>
		<div id="ucloginout"></div>
		<script>
			//退出
			// function backoff() {
			// 	$.modal({
			// 		title: "",
			// 		text: "确定要退出吗？",
			// 		buttons: [
			// 			{
			// 				text: "确定", onClick: function () {
			// 					window.location.href="<?php echo U('Login/logout');?>";
			// 			}
			// 			},
			// 			{text: "取消", className: "default"},
			// 		]
			// 	});
			// }
			function backoff() {
				$.modal({
					title: "",
					text: "确定要退出吗？",
					buttons: [
						{
							text: "确定", onClick: function () {
								  $.ajax({
									  type: "POST",
									  url: "<?php echo U('Login/logout');?>",
									  dataType: "json",
									  success: function (data) {
										  if (data.result == 1) {
//											  $('#ucloginout').html(data.des);//输出同步登录
											  window.location.href="<?php echo U('Login/index');?>";
										  } else {
											  XB.Tip(data.message);
										  }
									  }
								  });
						}
						},
						{text: "取消", className: "default"},
					]
				});
			}
		</script>