<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
		<link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>

		<link href="/Public/css/style.css" rel="stylesheet" />
		<script type="text/javascript" src="/Public/js/script.js"></script>
		<link type="text/css" href="/Public/css/app.css"  rel="stylesheet"/>
		<title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
		<style>
			.weui-popup__container, .weui-popup__overlay{
				height: 0;
			}
			.share img{
				 width: 100%;
			 }
			 .tui_wei{
				position: absolute;bottom:10px;left:0;width:100%; text-align: center
			}
			.tui_wei img{
				width: 110px;
				height:110px;
			}

			@media screen and(width: 320px){
				.tui_wei img{
					width: 100px;
					height: 100px;
				}
			}
			.weui-mask.weui-mask--visible{opacity:1;visibility:visible}
			.weui-mask{background:rgba(0,0,0,.6)}
			.weui-mask,.weui-mask_transparent{position:fixed;z-index:1000;top:0;right:0;left:0;bottom:0}
			.weui-dialog.weui-dialog--visible,.weui-dialog.weui-toast--visible,.weui-toast.weui-dialog--visible,.weui-toast.weui-toast--visible{opacity:1;visibility:visible;-webkit-transform:scale(1) translate(-50%,-50%);transform:scale(1) translate(-50%,-50%)}
			.weui-dialog{position:fixed;z-index:5000;width:80%;max-width:300px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:#fff;text-align:center;border-radius:3px;overflow:hidden}
			.weui-dialog__hd{padding:1.3em 1.6em .5em}
			.weui-dialog__title{font-weight:400;font-size:18px}
			.weui-dialog__bd{padding:0 1.6em .8em;min-height:40px;font-size:15px;line-height:1.3;word-wrap:break-word;word-break:break-all;color:#999}
			.weui-dialog__ft{position:relative;line-height:48px;font-size:18px;display:-webkit-box;display:-webkit-flex;display:flex}
			.weui-dialog__btn{display:block;-webkit-box-flex:1;-webkit-flex:1;flex:1;color:#4564f2;text-decoration:none;-webkit-tap-highlight-color:rgba(0,0,0,0);position:relative}
			.weui-dialog__ft:after{content:" ";position:absolute;left:0;top:0;right:0;height:1px;border-top:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(.5);transform:scaleY(.5)}
		</style>
	</head>
	<body>
		<header class="header">
			<a href="javascript:window.history.go(-1);" class="go_left"></a>
			<span class="header_til">分享推广</span>
		</header>
		<section style="height: 45PX;"></section>
		<div>
			<img src="<?php echo ($url); ?>" style="width: 100%;height:auto;margin-bottom: 35px;">
		</div>

		<div class="weui-tabbar" style="position: fixed; bottom: 0; left: 0; width: 100%;">
			<!--  <a href="javascript:void(0);" class="weui-tabbar__item btn2" data-clipboard-action="copy" data-clipboard-target="#bar2">
               <div class="weui-tabbar__icon">
                 <img src="/Public/images/icon_yongjin1.png" alt="">
               </div>
               <p class="weui-tabbar__label">分享海报</p>
             </a> -->
			<a onclick="fn_shre()" href="javascript:void(0);" class="weui-tabbar__item open-popup" >
				<div class="weui-tabbar__icon">
					<img src="/Public/images/icon_yongjin1.png" alt="">
				</div>
				<p class="weui-tabbar__label">分享海报</p>
			</a>
			<!--<a href="javascript:void(0);" class="weui-tabbar__item open-popup" data-target="#half">-->
				<!--<div class="weui-tabbar__icon">-->
					<!--<img src="/Public/images/icon_yongjin2.png" alt="">-->
				<!--</div>-->
				<!--<p class="weui-tabbar__label">分享链接</p>-->
			<!--</a>-->
			<a href="<?php echo ($url); ?>" class="weui-tabbar__item"  >
				<div class="weui-tabbar__icon">
					<img src="/Public/images/icon_yongjin3.png" alt="">
				</div>
				<p class="weui-tabbar__label">保存海报</p>
			</a>
			<a onclick="fn_cls()" href="javascript:void(0);" class="weui-tabbar__item btn" data-clipboard-action="copy" data-clipboard-target="#bar">
				<div class="weui-tabbar__icon">
					<img src="/Public/images/icon_yongjin4.png" alt="">
				</div>
				<p class="weui-tabbar__label">复制链接</p>
			</a>
		</div>
		<div id="bar" style="position: absolute; opacity: 0; filter:Alpha(opacity=0)"><?php echo ($shareurl); ?></div>

		<!--分享链接 start-->
		<div id="half" class='weui-popup__container popup-bottom'>
			<div class="weui-popup__overlay"></div>
			<div class="weui-popup__modal">
				<div class="toolbar">
					<div class="toolbar-inner">
						<a href="javascript:;" class="picker-button close-popup">关闭</a>
						<h1 class="title">选择要分享的平台</h1>
					</div>
				</div>
				<div class="modal-content" style="background:#fff;">
					<ul class="ul_zhineng">
						<div class="bdsharebuttonbox">

							<a href="#" style="width: 23%;height:50px;background: url(/Public/images/share_1.png);background-repeat: no-repeat;background-position: center;background-size: 46px 43px"  class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
							<a href="#" style="width: 23%;height:50px;background: url(/Public/images/share_2.png);background-repeat: no-repeat;background-position: center; background-size: 46px 43px"  class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a>
							<a href="#" style="width: 23%;height:50px;background: url(/Public/images/share_3.png);background-repeat: no-repeat;background-position: center; background-size: 46px 43px"  class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
							<a href="#" style="width: 23%;height:50px;background: url(/Public/images/share_4.png);background-repeat: no-repeat;background-position: center; background-size: 46px 43px"  class="bds_tqf" data-cmd="tqf" title="分享到腾讯朋友"></a>
							<a href="#" style="width: 23%;height:50px;background: url(/Public/images/share_5.png);background-repeat: no-repeat;background-position: center; background-size: 46px 43px" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
						</div>
						<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"<?php echo ($shareurl); ?>","bdMini":"2","bdMiniList":false,"bdPic":"<?php echo ($shareqrcode); ?>","bdStyle":"0","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>

						<div class="clear"></div>
					</ul>
				</div>
			</div>
		</div>
		<!--分享链接 end-->
		<script src="/Public/js/jquery-2.1.4.js"></script>
		<script src="/Public/js/jquery-weui.min.js"></script>
		<script src="/Public/js/clipboard.min.js"></script>
	</body>
</html>
<!--页面悬浮-->
<div class="">
	<?php if($GLOBALS['BasicInfo']['Ytstatus']=='3'):?>
	<a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
	<?php endif;?>
	<a href="<?php echo U('Index/index');?>" class="suspend_icon2"><img src="/Public/images/suspend_icon2.png"></a>
	<a href="javascript:history.go(-1)" class="suspend_icon3"><img src="/Public/images/suspend_icon3.png"></a>
</div>
<!--页面悬浮 end-->

<!--提示框-->
<div class="weui-mask weui-mask--visible" style="display:none"></div>
<div class="weui-dialog weui-dialog--visible" style="display:none">
	<div class="weui-dialog__hd">
		<strong class="weui-dialog__title">提示</strong>
	</div>
	<div class="weui-dialog__bd">链接复制成功!</div>
	<div class="weui-dialog__ft">
		<a href="javascript:;" class="weui-dialog__btn primary">确定</a>
	</div>
</div>
<!--提示框 END-->

<script type="text/javascript" src="/Public/artDialog/artDialog.min.js"></script>
<link rel="stylesheet" href="/Public/artDialog/skins/aero.css">
<script type="text/javascript" src="/Public/artDialog/iframeTools.js"></script>
<!-- 微信分享时的JS -->
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
	//复制功能 链接
	var clipboard = new Clipboard('.btn');
	clipboard.on('success', function(e) {
		//console.log(e);
		$('.weui-mask').css('display','');
		$('.weui-dialog').css('display','');
//		$.alert("链接复制成功!");
	});
	$('.weui-dialog__ft').click(function () {
		$('.weui-mask').css('display','none');
		$('.weui-dialog').css('display','none');
	});
	clipboard.on('error', function(e) {
		//console.log(e);
	});

	//分享海报 复制海报链接
	var clipboard2 = new Clipboard('.btn2');
	clipboard2.on('success', function(e) {
		//console.log(e);
		//XB.msg("海报链接复制成功");
		$.alert("海报链接复制成功!");
	});
	clipboard2.on('error', function(e) {
		//console.log(e);
	});

	function fn_cls() {
		$('.weui-mask').css('display','');
		$('.weui-dialog').css('display','');
	}


	function fn_shre() {
		$('.weui-popup__modal').css('display','none');
		$.alert("点击浏览器右上角分享本链接!");
	}
	/*
		微信分享逻辑
	*/
	$(function(){
	    wx.config({
            debug: false,////生产环境需要关闭debug模式
            appId: "<?php echo ($signPackage["appId"]); ?>",//appId通过微信服务号后台查看
            timestamp: "<?php echo ($signPackage["timestamp"]); ?>",//生成签名的时间戳
            nonceStr: "<?php echo ($signPackage["nonceStr"]); ?>",//生成签名的随机字符串
            signature: "<?php echo ($signPackage["signature"]); ?>",//签名
            jsApiList: [//需要调用的JS接口列表
                "onMenuShareTimeline",
		        "onMenuShareAppMessage",
		        "onMenuShareQQ",
		        "onMenuShareWeibo",
		        "onMenuShareQZone",
            ]
        });
		var title = "<?php echo ($shuju["ExtensionTitle"]); ?>";
        var ExtensionDesc = "<?php echo ($shuju["ExtensionDesc"]); ?>";
		var link = "<?php echo ($shareurl); ?>";
		var imgUrl = "<?php echo ($shuju["ExtensionImage"]); ?>";
		wx.ready(function () {
		    // 在这里调用 API
		    wx.checkJsApi({
		    jsApiList: ["onMenuShareTimeline",
		        "onMenuShareAppMessage",
		        "onMenuShareQQ",
		        "onMenuShareWeibo",
		        "onMenuShareQZone",
		        ], // 需要检测的JS接口列表，所有JS接口列表见附录2,
		        success: function(res) {
		            if(res.errMsg !='checkJsApi:ok'){
		                $.alert('请升级您的微信版本');
		                return;
		            }
		        }
		    });
		  	//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
		  	wx.onMenuShareTimeline({
			    title: title, // 分享标题
			    link: link, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
		  	});
		  	//获取“分享给朋友”按钮点击状态及自定义分享内容接口
		  	wx.onMenuShareAppMessage({
			    title: title, // 分享标题
			    desc: ExtensionDesc, // 分享描述
			    link: link, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    type: '', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			        $.alert('分享成功');
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			        $.alert('分享失败');
			    }
		  	});
			//获取“分享到QQ”按钮点击状态及自定义分享内容接口
			wx.onMenuShareQQ({
			    title: title, // 分享标题
			    desc: ExtensionDesc, // 分享描述
			    link: link, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			       // 用户取消分享后执行的回调函数
			    }
			});
		  	//获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口
			wx.onMenuShareWeibo({
			    title: title, // 分享标题
			    desc: ExtensionDesc, // 分享描述
			    link: link, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
		  	});
		  	//获取“分享到QQ空间”按钮点击状态及自定义分享内容接口
			wx.onMenuShareQZone({
			    title: title, // 分享标题
			    desc: ExtensionDesc, // 分享描述
			    link: link, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
				}
		  	});
		});
		wx.error(function (res) { 
			$.alert(res.errMsg);
		});       
	        
	});
</script>