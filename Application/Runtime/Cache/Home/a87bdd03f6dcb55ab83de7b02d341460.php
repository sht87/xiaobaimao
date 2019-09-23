<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
  <title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
  <style type="text/css">
    .all{
        display:flex; 
        background:url(/Public/images/tg_bg2.png)  no-repeat; 
        flex-direction: column; 
        width: 90%; 
        margin: 220px 5% 0 5%;
        background-size:100% 100%;
    }
    .icon-text{
        justify-content: center;
        display:flex;
         margin-top:10px;
         font-size: 14px;
         color: #4197FF
    }
	input{height: 36px;}

  </style>
  
<script src="/Public/js/jquery-2.1.4.js"></script>
<script src="/Public/js/layer.js"></script>  
 </head>
 <body style="background: url(/Public/images/tui_img.png) no-repeat;background-size:100% 120%;display:flex;background-repeat: no-repeat;">
    <div style="width: 100%;height:100%;display:flex;flex-direction: column;">
        <div class="all">
            <div style="display: flex;flex-direction: column;width: 100%;">
 
                <div style="height:80px;display: flex;justify-content: center;"> 
                    <img src="/Public/images/login_logo.png" width="60px" height="60px"  style="display:flex;    align-self: center;">
                </div>
                <div style="display:flex;flex-direction:row;justify-content: center;font-size:14px; margin: 10px;color:#395FB0;font-weight:bold">
                    拿钱，其实很简单
				</div>

                <div style="display:flex;flex-direction:row;width:100%;margin-top:10px">
                    <div style="flex-direction: column; display:flex;justify-content: center;align-items: center;flex:1">
                        <img src="/Public/images/tg_icon1.png" style="width:30px;height:30px"/>
                        <span class="icon-text">0门槛</span>
                    </div>
                    <div style="flex-direction: column; display:flex;flex:1;justify-content: center;align-items: center">
                        <img src="/Public/images/tg_icon2.png" style="width:30px;height:30px"/>
                        <span class="icon-text">易通过</span>

                    </div>
                    <div style="flex-direction: column; display:flex;flex:1;justify-content: center;align-items: center">
                        <img src="/Public/images/tg_icon3.png" style="width:40px;height:30px" />
                        <span class="icon-text">到账快</span>

                    </div>

                </div>
				<form action="#" method="post" id="formf" style="width:100%">
					<div style="position:relative; margin-top: 20px;width:80%;margin-left:10%;margin-right:10%;height:40px;border-radius:4px; font-size: 14px; border:2px solid #41A4FF;">
						<input type="number" name="Mobile" id="Mobile" style=" border-width: 0;background-color:transparent;position:absolute;width:99%;height:36px;" placeholder="请输入手机号码"></input>
					</div>
					<div style="margin-top: 20px;width:80%;margin-left:10%;margin-right:10%;height:40px;border-radius:4px; font-size: 14px; border:2px solid #41A4FF;display:flex">
					   <input name="ImgCode" id="ImgCode" type="text" value="" style="width: 60%;border-width: 0;background-color:transparent;" placeholder="图形验证码">
					   <img style="width:36%;display: inline-block;vertical-align: middle" src="<?php echo U('Common/selfverify');?>" id="imgValidateCode" onclick="this.src='<?php echo U('Common/selfverify');?>#'+Math.random();" height="38px" width="80px">
					</div>
					<div style="position:relative; margin-top: 20px;width:80%;margin-left:10%;margin-right:10%;height:40px;border-radius:4px; font-size: 14px; border:2px solid #41A4FF;">
						<input type="number" name="MsgCode" id="MsgCode" style=" border-width: 0;background-color:transparent;position:absolute;width:99%;height:36px;" placeholder="请输入四位验证码"></input>
						<div id="getcode" onclick="getCode()" style="position:absolute; width:80px; height:36px;border:2px solid #41A4FF;background:#41A4FF;color:#fff; font-size: 14px;border-radius:4px; line-height:36px;right:-2px;text-align:center;">获取验证码</div>
					</div>

					
					
				
					<div style="margin-left:10%;display: flex; background:#D1BF9C;color:#fff; font-size: 18px;width: 80% ;border-radius:4px; border:0;height:36px;margin-top:30px;height:40px;align-items: center;justify-content: center" onclick="fnRegister()">立即领钱</div>

					<div style="font-size: 10px;margin:10px 30px 20px 30px;display:flex;flex-direction: column">
						
						<?php if($xy1 == 1 ): ?><div style="display:flex;flex-direction: row;height:20px;align-items:center;">
								<input type="checkbox" id="adviceRadio1" value="1" checked/>
								<span>我已阅读同意<a href="<?php echo U('News/pages',array('ID'=>5));?>" style="color:#41A4FF;width:auto;"><?php echo ($xy1title); ?></a></span>
							</div><?php endif; ?>
						<?php if($xy2 == 1 ): ?><div style="display:flex;flex-direction: row;height:20px;align-items:center;">
								<input type="checkbox" id="adviceRadio2" value="1" checked/>
								<span>我已阅读同意<a href="<?php echo U('News/pages',array('ID'=>8));?>" style="color:#41A4FF;width:auto"><?php echo ($xy2title); ?></a></span>
							</div><?php endif; ?>
						<?php if($xy3 == 1 ): ?><div style="display:flex;flex-direction: row;height:20px;align-items:center;">
								<input type="checkbox" id="adviceRadio3" value="1" checked/>
								<span>我已阅读同意<a href="<?php echo U('News/pages',array('ID'=>9));?>" style="color:#41A4FF;width:auto"><?php echo ($xy3title); ?></a></span>
							</div><?php endif; ?>
					</div>
					<input type="hidden" name="puser" id="puser" value="<?php echo ($puser); ?>">
					<input type="hidden" name="linkType" id="linkType" value="<?php echo ($linkType); ?>">
					<input type="hidden" name="client" id="client" value="pc">
				</form>
            </div>
        </div>
		<div style="width: 100%; display: flex;justify-content: center;margin-top:25px">
		</div>
    </div>


 </body>
</html>
<script type="text/javascript">
	var u = navigator.userAgent;
	var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
	if(isiOS){
		$('#client').val('ios');
	}
	if(isAndroid){
		$('#client').val('android');
	}
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
	});
	
	function linkM(){
		layer.closeAll();
		window.location.href = url;
	}

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
	function getCode() {
		var mobile = $.trim($("#Mobile").val());
		var code = $.trim($("#ImgCode").val());
		if (mobile == '') {
					  layer.open({
						content: '手机号码不能为空！'
						,btn: '确定'
					  });

			return;
		}
		if (!mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)) {
			layer.open({content:'手机号码格式不正确！'
						,btn: '确定'
					  });
			return;
		}
		if (code == '') {
			layer.open({content:'请输入图形验证码！'
						,btn: '确定'
					  });
			return;
		}
		$.ajax({
			type: 'post',
			url: '<?php echo U("Common/getcodenew");?>',
			data: {mobile: mobile,code:code,check: 1},
			dataType: 'json',
			success: function (res) {
				var h = $(window).height();
				$(document).scrollTop(h); 
				if (res.result==1) {
					times();
					$("#getcode").removeAttr("onclick");
					//信息框
					  layer.open({
						content: res.message
						,btn: '确定'
					  });

				} else if(res.result==2){
					layer.open({
					   content: res.message
					  ,btn: ['下载APP', '取消']
					  ,yes: function(index){
						window.location.href = url;
						layer.close(index);
					  }
					 });
				}else{
					layer.open({
						content: res.message
						,btn: '确定'
					  });
					  $('#imgValidateCode').attr('src', "<?php echo U('Common/selfverify');?>?" + Math.random());
				}
			}
		});
	}

	//注册
	function fnRegister() {
		var Mobile = $.trim($('#Mobile').val());
		var MsgCode = $.trim($('#MsgCode').val());
		var linkType = $.trim($('#linkType').val());
		
		if (Mobile == '') {
			layer.open({
						content:'请输入手机号码'
						,btn: '确定'
					  });
			return;
		}
		if (!Mobile.match(/^((1[3-9][0-9]{1})+\d{8})$/)) {
			layer.open({
						content:'手机号码格式不正确！'
						,btn: '确定'
					  });
			return;
		}
		if (MsgCode == '') {
			layer.open({
						content:'短信验证码不能为空！'
						,btn: '确定'
					  });
			return;
		}
		if ($('#adviceRadio1').length>0&&!$('#adviceRadio1').is(':checked')) {
			layer.open({
						content:'请阅读协议内容'
						,btn: '确定'
					  });
			return;
		}
		if ($('#adviceRadio2').length>0&&!$('#adviceRadio2').is(':checked')) {
			layer.open({
						content:'请阅读协议内容'
						,btn: '确定'
					  });
			return;
		}
		if ($('#adviceRadio3').length>0&&!$('#adviceRadio3').is(':checked')) {
			layer.open({
						content:'请阅读协议内容'
						,btn: '确定'
					  });
			return;
		}
		
		$.ajax({
			type: "POST",
			url: "<?php echo U('Register/ajaxRegister');?>",
			data: $('#formf').serialize(),
			dataType: "json",
			success: function (data) {
				if (data.result == 1) {
					var h = $(window).height();
					$(document).scrollTop(h); 
					layer.open({
						style: 'border:none; background-color:#FDF9EE; width:300px;height:240px;',
						content: '<div style="width:100%;height:100%"><div style="width:100%;height:30px;color:#CB967D;font-size:18px">——您已注册完成!——</div><div style="width:100%;height:30px;margin-top:20px;color:#8B7C6B;font-size:20px">赶紧下载APP体验吧！</div><div style="width:80%;height:40px;margin-top:20px;color:#8B7C6B;font-size:17px;background:#CFB88C;margin-left:10%;line-height:40px;border-radius:20px;" onclick="linkM()">立即体验</div></div>'
					});
				} else {
					layer.open({
						content: data.message
						,btn: '确定'
					  });
				}
			}
		});
	}
</script>