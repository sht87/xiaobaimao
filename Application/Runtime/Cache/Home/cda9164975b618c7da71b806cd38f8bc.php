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
            <a href="javascript:history.go(-1)" class="go_left"></a>
            <span class="header_til">我的消息</span>
        </header>
        <!---->
        <div style="margin-top:50px">
            <?php if(is_array($msgList)): $i = 0; $__LIST__ = $msgList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><section class="section_mynews" onclick="readmsg(this,<?php echo ($vo['ID']); ?>,<?php echo ($vo['UserID']); ?>,<?php echo ($vo['IsRead']); ?>)">
                    <p class="center">
                        <span class="time_til "><?php echo (substr($vo['SendTime'],0,10)); ?></span>
                    </p>
                    <section class="section_mynews1">
                        <dl>
                            <dd><?php echo ($vo['Title']); ?></dd>
                            <dt><?php echo ($vo['Contents']); ?></dt>
                        </dl>
                    </section>
                </section><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>

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
            function readmsg(obj,MID,UID,IsRead) {
                if(IsRead==1){
                    return;
                }
                $.get("<?php echo U('Member/readmsg');?>",{MID:MID,UID:UID},function (data) {
                    if(data.result==1){
                        location.reload();
                    }
                },'json');
            }
        </script>