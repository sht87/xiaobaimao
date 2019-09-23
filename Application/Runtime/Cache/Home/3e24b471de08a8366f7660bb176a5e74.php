<?php if (!defined('THINK_PATH')) exit();?>    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
        <link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>
        <link type="text/css" href="/Public/css/app.css"  rel="stylesheet"/>
        <link rel="stylesheet" href="/Public/css/dropload.css" />
        <title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
        <style>
            .weui-panel__bd a{
                border: 1px solid #fff;
            }
            .weui-media-box:before{

            }
            .weui-panel__bd{
                border-bottom: none;
            }

            .weui-navbar:after{
                border-bottom: none;
            }
            .weui-navbar__item:after{

            }
            .weui-navbar__item{
                margin: 10px 0;
                padding: 0;
            }
            .weui-navbar__item.weui-bar__item--on{
                color: #5461eb;
                background:#fff;
            }
            .weui-agree__checkbox:checked:before{
                color: #5461eb;
            }
            .selected{border-color:#5461eb;color:#5461eb;}
            .noselect{border-color:#eee;color:#000;}
        </style>
    </head>
    <body>
    <header class="header" >
        <a href="javascript:history.go(-1)" class="go_left"></a>
        <span class="header_til"><?php echo ($SEOTitle); ?></span>
    </header>
    <div class="" style="height: 50px;"></div>

    <!---->
    <section class="hd"></section>
    <!---->
    <article class="khfxWarp" style="padding-bottom:60px;">
        <section class="itemlist">
        </section>
    </article>

    <!--引用底部文件 start-->
    <div class="weui-tabbar" style="position: fixed; bottom: 0; left: 0; width: 100%;">
    <a href="<?php echo U('Index/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Index' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
         <?php if(CONTROLLER_NAME=='Index' && ACTION_NAME=='index'):?>
            <img src="/Public/images/ic_home_selected.png" alt=""/>
         <?php else:?>
            <img src="/Public/images/ic_home_normal.png" alt=""/>
         <?php endif;?>
        </div>
        <p class="weui-tabbar__label">首页</p>
    </a>
    <a href="<?php echo U('Item/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && ACTION_NAME=='index'):?>
                <img src="/Public/images/ic_loan_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_loan_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找借贷</p>
    </a>

    <!--<a href="http://www.jinrirong.com/forum.php?mod=forumdisplay&fid=38&mobile=2" class="weui-tabbar__item">-->
        <!--<div class="weui-tabbar__icon">-->
            <!--<img src="/Public/images/forumlt.png" alt=""/>-->
        <!--</div>-->
        <!--<p class="weui-tabbar__label">论坛</p>-->
    <!--</a>-->

    <a href="<?php echo U('Item/creditv');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')) echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')):?>
                <img src="/Public/images/ic_promote_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_promote_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找信用卡</p>
    </a>
    <a href="<?php echo U('Member/index');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Member' && ACTION_NAME=='index') echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
         <?php if(CONTROLLER_NAME=='Member' && ACTION_NAME=='index'):?>
            <img src="/Public/images/ic_mine_selected.png" alt=""/>
         <?php else:?>
            <img src="/Public/images/ic_mine_normal.png" alt=""/>
         <?php endif;?>
        </div>
        <p class="weui-tabbar__label">我的</p>
    </a>
</div>

<!--页面悬浮-->
<div class="">
  <?php if($GLOBALS['BasicInfo']['Ytstatus']=='3'):?>
    <a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
  <?php elseif((CONTROLLER_NAME=='Index' && ACTION_NAME=='index') && $GLOBALS['BasicInfo']['Ytstatus']=='2'):?>
    <a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
  <?php endif;?>
    <a href="<?php echo U('Index/index');?>" class="suspend_icon2"><img src="/Public/images/suspend_icon2.png"></a>
    <a href="javascript:history.go(-1)" class="suspend_icon3"><img src="/Public/images/suspend_icon3.png"></a>
</div>
<!--页面悬浮 end-->
<script src="/Public/js/jquery-2.1.4.js"></script>
<!-- <script src="/Public/js/fastclick.js"></script> -->
<script src="/Public/js/jquery-weui.min.js"></script>
<script src="/Public/js/common.js"></script>
<script type="text/javascript" src="/Public/artDialog/artDialog.min.js"></script>
<link rel="stylesheet" href="/Public/artDialog/skins/aero.css">
<script type="text/javascript" src="/Public/artDialog/iframeTools.js"></script>

<!-- <script type="text/javascript">
    $(function () {
        FastClick.attach(document.body);
    });
</script> -->
</body>
</html>
    <!--引用底部文件 end-->
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/dropload.js"></script>
    <script type="text/javascript">
        //加载更多
        $(function () {
            var current_page=0;

            // dropload
            var dropload = $('.khfxWarp').dropload({
                scrollArea: window,
                loadDownFn: function (me) {

                    $.ajax({
                        type: 'POST',
                        url: "<?php echo U('News/indexdata');?>",
                        data:"pages="+current_page,
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            var result = '';
                            if(!data.result){
                                me.lock();
                                me.noData();
                                me.resetload();
                            }else{
                                for(var i = 0; i < data.message.length; i++) {
                                    result+= ''
                                            +'<section style="padding: 10px 0;border-bottom: 1px solid #eee">'
                                            +'<a href=<?php echo U("News/details");?>?ID='+data.message[i].ID+'><dl style="float: left;color:#2c2c2c;padding-left: 5%">'+data.message[i].Title+'</dl>'
                                            +'<dt style="float:right;color:#999;padding-right:5%;font-size:14px">'+data.message[i].AddTime.substr(0,16)+'</dt>'
                                            +'<dd class="clear"></dd></a>'
                                            +'</section>';
                                }

                                setTimeout(function(){
                                    $('.itemlist').append(result);
                                    current_page++;
                                    me.resetload();
                                },0);
                            }
                        },
                        error: function(xhr, type){
                            XB.Tip('加载数据出错');
                            // 即使加载出错，也得重置
                            me.lock();
                            me.noData();
                        }
                    });

                }
            });
        });
    </script>