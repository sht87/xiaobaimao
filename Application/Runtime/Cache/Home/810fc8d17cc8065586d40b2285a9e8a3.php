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
			<a href="javascript:window.history.go(-1)" class="go_left"></a>
			 <span class="header_til"><?php echo ($title); ?></span>
		</header>  
      <!---->
      <div class="weui-cells weui-cells_form" style="margin-top: 50px;">
		  <form action="#" method="post" id="formf">
			  <div class="weui-cell">
				  <div class="weui-cell__hd"><label class="weui-label">原密码</label></div>
				  <div class="weui-cell__bd">
					  <input class="weui-input" type="password" id="oldpwd" name="oldpwd" placeholder="请输入原密码">
				  </div>
			  </div>
			  <div class="weui-cell">
				  <div class="weui-cell__hd"><label class="weui-label">新密码</label></div>
				  <div class="weui-cell__bd">
					  <input class="weui-input" type="password" id="newpwd" name="newpwd" placeholder="请输入新密码">
				  </div>
			  </div>
			  <div class="weui-cell">
				  <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
				  <div class="weui-cell__bd">
					  <input class="weui-input" type="password" id="surepwd" name="surepwd" placeholder="请再次输入新密码">
				  </div>
			  </div>
		  </form>
	    </div>

		<section style="padding: 30px 0;">
			<a href="javascript:void(0);" class="btn_want" onclick="savepass()" style="width: 90%;">提交</a>
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
			function savepass() {
				var oldpwd=$.trim($('#oldpwd').val());
				var newpwd=$.trim($('#newpwd').val());
				var surepwd=$.trim($('#surepwd').val());

				if(!oldpwd){
					$.alert("请输入原密码");
					return false;
				}
				if(!oldpwd.match(/^[a-zA-Z]\w{5,15}$/)){
					$.alert("原密码格式不正确");
					return false;
				}
				if(!newpwd){
					$.alert("请输入新密码");
					return false;
				}
				if(!newpwd.match(/^[a-zA-Z]\w{5,15}$/)){
					$.alert("密码必须是以英文字母开头，6-16位与数字的组合");
					return false;
				}
				if(!surepwd){
					$.alert("请再次输入新密码");
					return false;
				}
				if(newpwd!=surepwd){
					$.alert("确认密码与新密码不一致");
					return false;
				}
				$.post("<?php echo U('Member/savePwd');?>",$('#formf').serialize(),function (data) {
					if(data.result==1){
						$.modal({
							title: "",
							text: "保存成功，请重新登录",
							buttons: [
								{
									text: "确定", onClick: function () {
										window.location.href="<?php echo U('Login/index');?>";
								}
								},
							]

						});
					}else{
						$.alert(data.message);
					}
				},'json');
			}
		</script>