<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
  <title>一键拿钱</title>
  <script src="/Public/js/jquery-2.1.4.js"></script>
	<script src="/Public/js/layer.js"></script> 
	
 </head>
 <style type="text/css">
	*{
		 margin: 0;
		 padding: 0;
	 }
	 html,body{
		 width: 100%;
		  height: 100%;
	}
	.head{
		width:100%;
		height: 50px;
		background-color: rgb(42,183,255);
		color: white;
		font-size: 16px;
		position:fixed;
		text-align:center;
		line-height:50px;
	}
	.content{
		display: flex; 
		flex-direction: column;
	}
	.b{
		display: flex;
		flex-direction: row;
		margin-top: 20px;
	}
	.contain{
		display: flex;
		background: white;
		border-radius: 10px;
		width: 90%;
		height: auto;
		margin-top: 20px;
		padding-right:5px;
		overflow:hidden;
		margin-left:5%;
		margin-right:5%;
		flex-direction: column;
	}
	.l{
		display: flex;
		flex-direction: column;
		width: 100%;
		height: 40px;
	}
	.lt{
		display: flex;
		flex-direction: row;
		width: 100%;
		height: 39px;
	}

	select{
		border: none;
		appearance: none;
		-moz-appearance: none;
		-webkit-appearance: none;
		background:white;
	}
	select::-ms-expand { display: none; }
 </style>
 <body style="background: rgb(255,255,255);margin-top:-20px">
  	<div class="content">
		<div class="b">
			<img style="width: 100%;height: 180px" src="/Public/images/contentbg.jpg"/>
			<!--<text style="align-self: center;margin-left: 20px;font-size: 16px">基本信息</text>-->
		</div>
		<form action="#" method="post" id="formf">
			<div class="contain">
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">客户姓名</text>
						<input id="username" name="username" placeholder="请输入客户姓名" style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 10px" />
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">手机号</text>
						<input type="number" id="phone" name="phone" placeholder="请输入手机号" style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 10px" />
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width:60px">性别</text>
						<div style="flex-grow:1"></div>
						<select dir="rtl" style="width:80px;border-width: 0;font-size: 12px;padding-right:10px" id="sex" name="sex">
						  <option value="1">男</option>
						  <option value="0">女</option>
						</select>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width: auto">年龄</text>
						<input type="number" id="age" name="age" placeholder="请输入年龄" style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 10px" />
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>

				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width: 120px">期望借款金额</text>
						<input type="number" id="money" name="money" placeholder="请输入金额" style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 10px" />
						<text style="font-size: 12px;align-self:center;padding-right:10px">万</text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>

				<!-- <div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width: auto">身份证号</text>
						<input type="number" id="cardNum" name="cardNum" placeholder="请输入身份证号" style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 10px" />
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div> -->

				<!-- <div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width: auto">房产状态</text>
						<select dir="rtl" id="houseType" name="houseType" style="text-align: right; margin-left: 40%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px;">
						 <option value="-1"></option>
						  <option value="1">有房贷</option>
						  <option value="2">有房</option>
						  <option value="3">无</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div>
				
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">车辆状态</text>
						<select dir="rtl" id="carType" name="carType" style="margin-left: 50%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
							<option value="-1"></option>
						  <option value="1">有车贷</option>
						  <option value="2">有车</option>
						  <option value="3">无</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div> -->

				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">职业</text>
						<select dir="rtl" id="zy" name="zy" style="margin-left: 50%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
						  <option value="-1"></option>
						  <option value="1">白领</option>
						  <option value="2">公务员</option>
						  <option value="3">私企业主</option>
						  <option value="4">无业</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>

				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px;width:120px">工作年限</text>
						<div style="flex-grow:1"></div>
						<select dir="rtl" id="work" name="work" style="width:120px;border-width: 0;font-size: 12px;padding-right: 10px">
						  <option value="-1"></option>
						  <option value="1">6个月以内</option>
						  <option value="2">12个月以内</option>
						  <option value="3">1年以上</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>

				<!-- <div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">公积金情况</text>
						<div style="flex-grow:1"></div>
						<select dir="rtl" id="gjj" name="gjj" style="width:120px;border-width: 0;font-size: 12px;padding-right: 10px">
						  <option value="-1"></option>
						  <option value="1">1年以内</option>
						  <option value="2">1年以上</option>
						  <option value="3">无公积金</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div> -->

				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">月收入</text>
						<select dir="rtl" id="ysr" name="ysr" style="margin-left: 50%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
						  <option value="-1"></option>
						  <option value="1">4千以下</option>
						  <option value="2">4千-1万</option>
						  <option value="3">1万以上</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
		
				</div>

				<!-- <div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">社保情况</text>
						<select dir="rtl" id="sb" name="sb" style="margin-left: 50%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
						  <option value="-1"></option>
						  <option value="1">有</option>
						  <option value="2">无</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div>
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">信用卡情况</text>
						<select dir="rtl" id="xyk" name="xyk" style="margin-left: 45%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
						  <option value="-1"></option>
						  <option value="1">无</option>
						  <option value="2">1万以下</option>
						  <option value="3">1万以上</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div>
				
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">保单情况</text>
						<select dir="rtl" id="bd" name="bd" style="margin-left: 50%;flex-grow: 1;border-width: 0;font-size: 12px;padding-right: 20px">
						  <option value="-1"></option>
						  <option value="1">有</option>
						  <option value="2">无</option>
						</select>
						<text style="font-size: 12px;align-self:center;">></text>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div>
				<div class="l">
					<div class="lt">
						<text style="align-self: center; margin-left: 20px;font-size: 12px">城市</text>
						<input id="city" name="city" type="text" readonly="" placeholder="请选择城市"  style="flex-grow: 1;text-align: right;border-width: 0;font-size: 12px;padding-right: 20px" value=""/>
						<input id="city1" name="city1" type="hidden"/>
					</div>
					<div style="width: 100%;height: 1px;background: rgb(240,240,240);padding-left: 10px;padding-right:10px"></div>
						
				</div> -->
				
			</div>
		</form>
		<button style="display: flex; background:#1E90FF;color:#fff; font-size: 18px;border:0;margin-top:30px;margin-left:20px;margin-right:20px;height:40px;align-items: center;justify-content: center;margin-bottom:20px;" onclick="submitContent()">立即申请</button>
  	</div>
 </body>
</html>
<script type="text/javascript">
	var can = 1;
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
	function GetQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
		var r = window.location.search.substr(1).match(reg); 
		if (r != null) 
			return unescape(r[2]); 
		return null; 
	} 

	$(document).ready(function(){
		var mobile = GetQueryString('mobile');
		$("#phone").val(mobile);
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
		can = 0;
		if(isiOS){
			window.webkit.messageHandlers.back.postMessage('');
		}
		if(isAndroid){
			window.java_obj.anFinish();
		}
	}

	function submitContent() {
		var username = $.trim($('#username').val());
		var money = $.trim($('#money').val());
		var age = $.trim($('#age').val());
		//var houseType = $.trim($('#houseType').val());
		//var carType = $.trim($('#carType').val());
		var zy = $.trim($('#zy').val());
		var work = $.trim($('#work').val());
		//var gjj = $.trim($('#gjj').val());
		var ysr = $.trim($('#ysr').val());
		//var sb = $.trim($('#sb').val());
		//var xyk = $.trim($('#xyk').val());
		//var bd = $.trim($('#bd').val());
		//var city = $.trim($('#city').val());
		if (username == '') {
			layer.open({
						content:'请输入姓名'
						,btn: '确定'
					  });
			return;
		}
		if (money == '') {
			layer.open({
						content:'请输入借款金额'
						,btn: '确定'
					  });
			return;
		}
		if (age == '') {
			layer.open({
						content:'请输入年龄'
						,btn: '确定'
					  });
			return;
		}
		/*if (houseType == -1) {
			layer.open({
						content:'请选择房产状态'
						,btn: '确定'
					  });
			return;
		}
		if (carType == -1) {
			layer.open({
						content:'请选择车辆状态'
						,btn: '确定'
					  });
			return;
		}*/
		if (zy == -1) {
			layer.open({
						content:'请选择职业'
						,btn: '确定'
					  });
			return;
		}
		if (work == -1) {
			layer.open({
						content:'请选择工作年限'
						,btn: '确定'
					  });
			return;
		}
		/*if (gjj == -1) {
			layer.open({
						content:'请选择公积金情况'
						,btn: '确定'
					  });
			return;
		}*/
		if (ysr == -1) {
			layer.open({
						content:'请选择月收入情况'
						,btn: '确定'
					  });
			return;
		}
		/*if (sb == -1) {
			layer.open({
						content:'请选择社保情况'
						,btn: '确定'
					  });
			return;
		}
		if (xyk == -1) {
			layer.open({
						content:'请选择信用卡情况'
						,btn: '确定'
					  });
			return;
		}
		if (bd == -1) {
			layer.open({
						content:'请选择保单情况'
						,btn: '确定'
					  });
			return;
		}
		if (city == '') {
			layer.open({
						content:'请输入城市'
						,btn: '确定'
					  });
			return;
		}*/
		$.ajax({
			type: "POST",
			url: "<?php echo U('Register/submitContent');?>",
			data: $('#formf').serialize(),
			dataType: "json",
			success: function (data) {
				if (data.result == 1) {
					layer.open({
						style: 'border:none; background-color:#FDF9EE; width:300px;height:240px;',
						content: '<div style="width:100%;height:100%"><div style="width:100%;height:30px;color:#CB967D;font-size:18px">填写成功</div><div style="width:100%;height:30px;margin-top:20px;color:#8B7C6B;font-size:20px">将为您匹配最符合您的产品额度！</div><div id="make" style="width:80%;height:40px;margin-top:20px;color:#8B7C6B;font-size:17px;background:#CFB88C;margin-left:10%;line-height:40px;border-radius:20px;" onclick="linkM()">确定(3)</div></div>'
					});
					timeOut();
				} else {
					layer.open({
						content: data.message
						,btn: '确定'
					  });
				}
			}
		});
		var wait =3;
		function timeOut() {
			if(wait != 0) {
				setTimeout(function() {
					--wait;
					$('#make').text("确定("+wait+")");
					timeOut();

				}, 1000);

			}else{
				if(can==1){
					if(isiOS){
						window.webkit.messageHandlers.back.postMessage('');
					}
					if(isAndroid){
						window.java_obj.anFinish();
					}

				}
			}

		}
	}
</script>