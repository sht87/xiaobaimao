<?php if (!defined('THINK_PATH')) exit();?>        <!DOCTYPE html>
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

        <header class="header">
            <a href="javascript:window.history.go(-1);" class="go_left"></a>
            <span class="header_til"><?php echo ($title); ?></span>
        </header>
        <!---->
        <div class="weui-panel weui-panel_access" style="margin-top:50px">
            <div class="weui-panel__bd">
                <a href="<?php echo U('Member/newslist',array('Type'=>1));?>" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" src="/Public/images/icon_mynews1.png">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">
                            <span class="fl">通知消息</span>
                            <?php if(empty($notice)): ?><span class="fr" style="color:#959595; font-size: 11px;"><?php echo date('Y-m-d'); ?></span>
                                <?php else: ?>
                                <span class="fr" style="color:#959595; font-size: 11px;"><?php echo (substr($notice['SendTime'],0,10)); ?></span><?php endif; ?>
                            <div class="clear"></div>
                        </h4>
                        <?php if(empty($notice)): ?><p class="weui-media-box__desc">暂无新消息</p>
                            <?php else: ?>
                            <p class="weui-media-box__desc"><?php echo ($notice['Title']); ?></p><?php endif; ?>
                    </div>
                </a>
                <a href="<?php echo U('Member/newslist');?>" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" src="/Public/images/icon_mynews2.png">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">
                            <span class="fl">系统消息</span>
                            <?php if(empty($sysmsg)): ?><span class="fr" style="color:#959595; font-size: 11px;"><?php echo date('Y-m-d'); ?></span>
                                <?php else: ?>
                                <span class="fr" style="color:#959595; font-size: 11px;"><?php echo (substr($sysmsg['SendTime'],0,10)); ?></span><?php endif; ?>
                            <div class="clear"></div>
                        </h4>
                        <?php if(empty($sysmsg)): ?><p class="weui-media-box__desc">暂无新消息</p>
                            <?php else: ?>
                            <p class="weui-media-box__desc"><?php echo ($sysmsg['Title']); ?></p><?php endif; ?>
                    </div>
                </a>

            </div>
        </div>
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
        <!---->