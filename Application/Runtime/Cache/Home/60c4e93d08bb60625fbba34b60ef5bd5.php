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

		<style type="text/css">
			.header {
				background: #fff;
			}
			.header_til {
				color: #000;
			}
			.go_left {
				position: absolute;
				left: 0;
				top: 0;
				width: 40px;
				height: 45px;
				background: url(/Public/images/icon_goback1.png) center no-repeat;
				background-size: 10px auto;
			}
		</style>
      <!---->
		<header class="header"  >
			<a href="<?php echo U('stepOne');?>" class="go_left"></a>
			<span class="header_til"><?php echo ($title); ?></span>
		</header>
        <section style=" color: #999;  margin-top: 50px;">   
		 	<section class="weui_reg"> 
		 		<input class="weui-input icon_regPwd" id="NewPass" name="NewPass"  type="password" placeholder="重置您的密码">
		 	</section>
		 	<section class="weui_reg"> 
		 		<input class="weui-input icon_regPwd" id="SurePass" name="SurePass"  type="password" placeholder="再次重置您的密码">
		 	</section>
		 	   
	        <section style="padding: 30px 10px;">
				<a href="javascript:;" class="btn_want" style="width: 100%;" id="show-custom">完成</a> 
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
		<script type="text/javascript">
			$(document).on("click", "#show-custom", function () {
				var newpass=$.trim($('#NewPass').val());
				var surepass=$.trim($('#SurePass').val());
				if(newpass==''){
					$.alert("请输入新密码");
					return false;
				}
				if(!newpass.match(/^[a-zA-Z]\w{5,15}$/)){
					$.alert("密码必须是以英文字母开头，6-16位与数字的组合");
					return false;
				}
				if(surepass==''){
					$.alert("请输入确认密码");
					return false;
				}
				if(newpass!=surepass){
					$.alert("您输入的新密码与确认密码不相同");
					return false;
				}
				$.post("<?php echo U('Forgetpwd/checkTwo');?>",{newpass:newpass,surepass:surepass},function (data) {
					if(data.result==1){
						$.modal({
							title: " ",
							text: "重置密码成功，请重新登录",
							buttons: [
								{
									text: "返回登录", onClick: function () {
									window.location.href = "<?php echo U('Login/index');?>";
								}
								}
							]

						});
					}else{
						XB.Tip(data.message);
					}
				},'json');

			});
		</script>
			
			
	</body>
</html>