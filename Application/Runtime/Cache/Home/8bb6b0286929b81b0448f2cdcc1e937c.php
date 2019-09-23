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

       	<div class="login_top">
       		<a href="<?php echo U('Index/index');?>" class="center" style="display: block; padding-top: 50px;">
       			<img src="/Public/images/login_logo.png" style="width: 86px;">
       		</a>
       	</div> 
       	<!---->
           <section style="margin:40px;">
			   <form action="#" method="post" id="formf">
					<section class="weui_reg">
						<input class="weui-input  icon_regUser" name="Mobile" id="Mobile" value="" type="text" placeholder="手机号" style="padding-left:40px;">
					</section>
				   <section class="weui_reg">
					   <input class="weui-input icon_regem" name="ImgCode" id="ImgCode" type="text" value="" style="width: 60%;" placeholder="图形验证码">
					   <img style="width:36%;display: inline-block;vertical-align: middle" src="<?php echo U('Common/selfverify');?>" id="imgValidateCode" onclick="this.src='<?php echo U('Common/selfverify');?>#'+Math.random();" height="38" width="100px">
					   <div class="clear"></div>
				   </section>
					<section class="weui_reg">
						<input class="weui-input icon_regem" id="MsgCode" name="MsgCode" type="text" placeholder="短信验证码" style="padding-left: 40px;  ">
						<a href="javascript:void(0);" id="getcode" onclick="getcode()" class="huo_code" style="color: #FF9300">发送验证码</a>
					</section>

					<!-- <section class="weui_reg">
						<input class="weui-input  icon_regem" name="Email" id="Email" value="" type="text" placeholder="邮箱" style="padding-left:40px;">
					</section> -->

					<section class="weui_reg">
						<input class="weui-input icon_regPwd" id="Password" name="Password" type="password" placeholder="密码" style="padding-left: 40px;  ">
						<!--<a href="javascript:void(0);" class="liu_icon"><img src="/Public/images/icon_liu.png" style="width: 20px; height:auto;"></a>-->
					</section>
				   <?php if($enable1 == 1 ): ?><section class="weui-agree">
					   <label for="weuiAgree" class="select_agree" id="agreen1" style="padding-left: 35px">
						   <span id="weuiAgree" class="weui-agree__text">
								我已阅读并同意
					  		</span>
					   </label>
					   <a href="<?php echo U('News/pages',array('ID'=>5));?>" style="color: #5461eb;"><?php echo ($title1); ?></a>
				   </section><?php endif; ?>

				<?php if($enable2 == 1 ): ?><section class="weui-agree">
					   <label for="weuiAgree" class="select_agree" id="agreen2" style="padding-left: 35px">
						   <span id="weuiAgree" class="weui-agree__text">
								我已阅读并同意
					  		</span>
					   </label>
					   <a href="<?php echo U('News/pages',array('ID'=>8));?>" style="color: #5461eb;"><?php echo ($title2); ?></a>
				   </section><?php endif; ?>

				<?php if($enable1 == 3 ): ?><section class="weui-agree">
					   <label for="weuiAgree" class="select_agree" id="agreen3" style="padding-left: 35px">
						   <span id="weuiAgree" class="weui-agree__text">
								我已阅读并同意
					  		</span>
					   </label>
					   <a href="<?php echo U('News/pages',array('ID'=>9));?>" style="color: #5461eb;"><?php echo ($title3); ?></a>
				   </section><?php endif; ?>
				
				   <input type="hidden" name="Referee" id="Referee" value="<?php echo ($referee); ?>">
			   </form>
	        <section style="padding: 30px 0;">
				<a href="javascript:void(0);" class="btn_want" onclick="fnRegister()" style="width: 90%;">注册</a>
			</section>  
			<p class="center"><a href="<?php echo U('Login/index');?>"  style="color: #5461eb;">立即登录</a></p>
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
			var u = navigator.userAgent;
			var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
			var url = '';
			$(document).ready(function(){
				$.ajax({
					type: 'get',
					url: '<?php echo U("Register/getdownloadurl");?>',
					dataType: 'json',
					success: function (res) {
						if(isiOS){
							url = res.des.ios;
						}
						else{
							url = res.des.android;
						}
					}
				});

				$("#agreen").on("click",function () {
					if($(this).hasClass("select_refuse")){
						$(this).attr("class","select_agree");
					}else{
						$(this).attr("class","select_refuse");
					}
				});
			});
			//启用倒计时
			function times() {
				var setTime;
				var time = 60;
				setTime = setInterval(function () {
					if (time <= 0) {
						clearInterval(setTime);
						//添加事件
						$("#getcode").attr("onclick", "getcode()");
						$("#getcode").text('发送验证码');
						return;
					}
					time--;
					msgs = time + "s后重发";
					$("#getcode").text(msgs);
				}, 1000);
			}

			//获取验证码
			function getcode() {
				var mobile = $.trim($("#Mobile").val());
				var code = $.trim($("#ImgCode").val());

				if (mobile == '') {
					$.alert("手机号码不能为空！");
					return false;
				}
				if (!mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)) {
					$.alert("手机号码格式不正确！");
					return;
				}
				if (code == '') {
					$.alert("请输入图形验证码！");
					return;
				}
				$.ajax({
					type: 'post',
					url: '<?php echo U("Common/getcode");?>',
					data: {mobile: mobile,code:code,check: 1},
					dataType: 'json',
					success: function (res) {
						if (res.result==1) {
							times();
							$("#getcode").removeAttr("onclick");
                            $.alert(res.message);
						}
						else if (res.result==2) {
							$.modal({
							  text: res.message,
							  buttons: [
								{ text: "取消", className: "default", onClick: function(){ console.log(3)} },
								{ text: "下载App", onClick: function(){
									window.location.href = url;
								} },
							  ]
							});

						} else {
							$.alert(res.message);
							$('#imgValidateCode').attr('src', "<?php echo U('Common/selfverify');?>?" + Math.random());
						}
					}
				});
			}

			//注册
			function fnRegister() {
				var Mobile = $.trim($('#Mobile').val());
				//var Email = $.trim($('#Email').val());
				var Password = $.trim($("#Password").val());
				var MsgCode = $.trim($('#MsgCode').val());

//				if( $('#weuiAgree').prop('checked')!=true ){
//					$.alert('请认真阅读《注册协议》并勾选');
//					return false;
//				}
				if($("#agreen1").length>0&&!$("#agreen1").hasClass("select_agree")){
					$.alert('请认真阅读《注册协议》并勾选');
					return false;
				}
				if($("#agreen2").length>0&&!$("#agreen2").hasClass("select_agree")){
					$.alert('请认真阅读《注册协议》并勾选');
					return false;
				}
				if($("#agreen3").length>0&&!$("#agreen3").hasClass("select_agree")){
					$.alert('请认真阅读《注册协议》并勾选');
					return false;
				}
				if (Mobile == '') {
					$.alert("请输入手机号码");
					return false;
				}
				if (!Mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)) {
					$.alert("手机号码格式不正确！");
					return;
				}
				// if (Email == '') {
				// 	$.alert("请输入邮箱");
				// 	return false;
				// }
				if (MsgCode == '') {
					$.alert("短信验证码不能为空！");
					return false;
				}
				if (Password == '') {
					$.alert("请输入您的密码，以英文字母开头，6-16位与数字的组合");
					return false;
				}
				if (!Password.match(/^[a-zA-Z]\w{5,15}$/)) {
					$.alert("密码必须是以英文字母开头，6-16位与数字的组合");
					return false;
				}


				$.ajax({
					type: "POST",
					url: "<?php echo U('Register/ajaxRegister');?>",
					data: $('#formf').serialize(),
					dataType: "json",
					success: function (data) {
						if (data.result == 1) {
							//成功
							// XB.Success(data.message, function () {
							// 	window.location.href = "<?php echo U('Login/index');?>";
							// });
							$.alert(data.message, "", function() {
							  //点击确认后的回调函数
							  window.location.href = "<?php echo U('Login/index');?>";
							});
						} else {
							$.alert(data.message);
						}
					}
				});
			}
		</script>