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
			.weui-toast{
				margin-left: 0;
			}
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
			<a href="<?php echo U('Login/index');?>" class="go_left"></a>
			<span class="header_til"><?php echo ($title); ?></span>
		</header>
        <section style=" color: #999;  margin-top: 50px;">
		 	<section class="weui_reg"> 
		 		<input class="weui-input icon_regUser" id="Mobile" name="Mobile" value="" type="text" placeholder="请输入您的手机号">
		 		<a href="javascript:void(0);" class="huo_btnqu" id="show-custom">获取验证码</a>
		 	</section>
			<section class="weui_reg">
				<input class="weui-input icon_regem" name="ImgCode" id="ImgCode" type="text" value="" style="width: 60%;" placeholder="图形验证码">
				<img style="width:36%;display: inline-block;vertical-align: middle" src="<?php echo U('Common/selfverify');?>" id="imgValidateCode" onclick="this.src='<?php echo U('Common/selfverify');?>#'+Math.random();" height="38" width="100px">
				<div class="clear"></div>
			</section>
		 	<section class="weui_reg"> 
		 		<input class="weui-input icon_regPwd" id="MsgCode" name="MsgCode" type="text" placeholder="请输入短信验证码">
		 		<!--<a href="#" class="liu_icon"><img src="images/icon_liu.png" style="width: 20px; height:auto;"></a>-->
		 	</section>
		 	   
	        <section style="padding: 30px 10px;">
				<a href="javascript:void(0);" onclick="fn_next()" class="btn_want" style="width: 100%;">下一步</a>
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
	$(document).on("click", "#show-custom", function() {
		var mobile = $.trim($("#Mobile").val());
		var code = $.trim($("#ImgCode").val());

		if (mobile == '') {
			//XB.Tip('请输入手机号码！');
			$.alert("请输入手机号码！");
			return false;
		}
		if (!mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)) {
			//XB.Tip("手机号码格式不正确！");
			$.alert("手机号码格式不正确！");
			return;
		}
		if (code == '') {
			//XB.Tip("请输入图形验证码！");
			$.alert("请输入图形验证码！");
			return;
		}
		//校验此号码是不是平台的会员
		$.ajax({
			type: 'post',
			url: '<?php echo U("Forgetpwd/ajaxcheckmodel");?>',
			data: {mobile: mobile},
			dataType: 'json',
			success: function (res) {
				if (res.result==1) {
					$.modal({
						title: "确认手机号码",
						text: "我们将发送短信验证码到：<br>"+mobile,
						buttons: [
							{ text: "取消", className: "default"},
							{ text: "好", onClick: function(){
								//获取验证码
								$.ajax({
									type: 'post',
									url: '<?php echo U("Common/getcode");?>',
									data: {mobile: mobile,code:code,check:2},
									dataType: 'json',
									success: function (res) {
										if (res.result) {
											times();
											$.toast(res.message);
											//XB.Success(res.message);
										} else {
											$.alert(res.message);
											$('#imgValidateCode').attr('src', "<?php echo U('Common/selfverify');?>?" + Math.random());
										}
									}
								});
								}
							}
						]
					});
				} else {
					XB.Tip(res.message);
				}
			}
		});
	});

	//倒计时
	function times() {
		var setTime;
		var time = 60;
		setTime = setInterval(function () {
			if (time <= 0) {
				clearInterval(setTime);
				//添加事件
				$("#show-custom").text('获取验证码');
				return;
			}
			time--;
			msgs = time + "s后重发";
			$("#show-custom").text(msgs);
		}, 1000);
	}

	//下一步
	function fn_next() {
		var mobile = $.trim($("#Mobile").val());
		var msgcode = $.trim($("#MsgCode").val());

		if (mobile == '') {
			//XB.Tip('请填写手机号码！');
			$.alert("请填写手机号码！");
			return false;
		}
		if (!mobile.match(/^((1[3-8][0-9]{1})+\d{8})$/)) {
			//XB.Tip("手机号码格式不正确！");
			$.alert("手机号码格式不正确！");
			return;
		}
		if (msgcode == '') {
			//XB.Tip('请填写获取到的短信验证码！');
			$.alert("请填写获取到的短信验证码！");
			return false;
		}

		$.ajax({
			url: "<?php echo U('Forgetpwd/checkOne');?>",
			type: 'post',
			dataType: 'json',
			data: {"mobile": mobile, "code": msgcode},
			success: function (data) {
				if (data.result == 1) {
					window.location.href = "<?php echo U('Forgetpwd/stepTwo');?>";
				} else {
					$.alert(data.message);
				}
			}
		});
	}
</script>