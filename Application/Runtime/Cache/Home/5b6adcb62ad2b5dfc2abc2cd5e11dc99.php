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

       	<section style="margin:40px;">
			<form action="#" method="post" id="formf">
				<section class="weui_reg">
					<input class="weui-input " id="Mobile" name="Mobile" value="" type="text" placeholder="请输入手机号" style="padding-left:0; background: none;"  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
				</section>
				<section class="weui_reg">
					   <input class="weui-input icon_regem" name="ImgCode" id="ImgCode" type="text" value="" style="width: 60%;" placeholder="图形验证码">
					   <img style="width:36%;display: inline-block;vertical-align: middle" src="<?php echo U('Common/selfverify');?>" id="imgValidateCode" onclick="this.src='<?php echo U('Common/selfverify');?>#'+Math.random();" height="38" width="100px">
					   <div class="clear"></div>
				   </section>
				<section class="weui_reg">
					<input class="weui-input " id="Password" name="Password" value="" type="number" placeholder="请输入验证码" style="padding-left:0; background: none;">
					<a href="javascript:void(0);" id="getcode" onclick="getcode()" class="huo_code" style="color: #FF9300">发送验证码</a>
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
				

				<input type="hidden" name="ip" id="IP" value="">
				<input type="hidden" name="city" id="City" value="">

				<input type="hidden" name="ServiceOpenid" id="ServiceOpenid" value="<?php echo ($wxinfos["openid"]); ?>">
				<input type="hidden" name="Unionid" id="Unionid" value="<?php echo ($wxinfos["unionid"]); ?>">
				<input type="hidden" name="HeadImg" id="HeadImg" value="<?php echo ($wxinfos["headimgurl"]); ?>">
				<input type="hidden" name="PhoneType" id="PhoneType">
				<p class="center" style="padding: 10px;">
					<a href="<?php echo U('Forgetpwd/stepone');?>" class="fl" style="color: #5461EB">忘记密码？</a>
					<a href="<?php echo U('Register/index');?>" class="fr" style="color:#ffa200;">注册登录</a>
				<div class="clear"></div>
				</p>

				<section style="padding: 30px 0;">
					<a href="javascript:void(0);" onclick="fn_login()" class="btn_want" style="width: 80%;">登录</a>
				</section>
			</form>
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
		<div id="uclogins"></div>
		<script type="text/javascript">
			$(function () {
				$("#agreen1").on("click",function () {
					if($(this).hasClass("select_refuse")){
						$(this).attr("class","select_agree");
					}else{
						$(this).attr("class","select_refuse");
					}
				});
				$("#agreen2").on("click",function () {
					if($(this).hasClass("select_refuse")){
						$(this).attr("class","select_agree");
					}else{
						$(this).attr("class","select_refuse");
					}
				});
				$("#agreen3").on("click",function () {
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
					url: '<?php echo U("common/getcode");?>',
					data: {mobile: mobile,code:code,check: 0},
					dataType: 'json',
					success: function (res) {
						if (res.result==1) {
							times();
							$("#getcode").removeAttr("onclick");
                            $.alert(res.message);
						}else{
							$.alert(res.message);
						}
					}
				});
			}
			  //判断android还是ios
			  function checkPlatform(){
				  if(/android/i.test(navigator.userAgent)){
					  return 'android';
				  }else if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
					  return 'ios';
				  }else{
				  	  return 'other';
				  }
			  }
			  //登录功能
			  function fn_login() {
				  var PhoneType = checkPlatform();
				  $('#PhoneType').val(PhoneType);
				  var Mobile = $.trim($('#Mobile').val());
				  var Password = $.trim($("#Password").val());
				  if (Mobile=='') {
					 // XB.Tip('请输入请输入手机号');
					 $.alert("请输入手机号");
					  return false;
				  }
				  if(!Mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)){
					  //XB.Tip('您输入的手机号格式不正确!');
					  $.alert("您输入的手机号格式不正确!");
					  return false;
				  }
				  if (Password=='') {
					  //XB.Tip('请输入您的密码!');
					  $.alert("请输入您的验证码!");
					  return false;
				  }
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

				  //提交
				  $.ajax({
					  type: "POST",
					  url: "<?php echo U('Login/ajaxLogin');?>",
					  data: $('#formf').serialize(),
					  dataType: "json",
					  success: function (data) {
						  if (data.result == 1) {
							  var backsrc = "<?php echo ($back); ?>";
//							  $('#uclogins').html(data.des);//输出同步登录
							  if (backsrc) {
								  window.location.href = backsrc;
							  } else {
								  window.location.href = "<?php echo U('Member/index');?>";
							  }

						  } else {
							  //XB.Tip(data.message);
							  $.alert(data.message);
							  $('#CodeImg').attr('src', "<?php echo U('Common/selfverify');?>?" + Math.random())
						  }
					  }
				  });
			  }
		</script>