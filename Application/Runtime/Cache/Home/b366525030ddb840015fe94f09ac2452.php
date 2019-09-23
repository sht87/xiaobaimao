<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
        <link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
		<link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>
        <link type="text/css" href="/Public/css/app.css"  rel="stylesheet"/>
		<title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
		<style>
			.weui-grids:before{ border-top:none;}
			.weui-grid:after{ border-bottom: none;}
			.weui-grid:before{border-right: none;}
			.weui-grid__label{ color:#4b4a4a;}
			.dropload-down{text-align: center;}
		</style>
	</head>
	<body>  
		<header class="header">
			<a href="<?php echo U('Index/index');?>" class="go_left"></a> 
			<span class="header_til">新手帮助</span>  
		</header>
		
		<div style="height: 50px;"></div>  
		<!----> 
		
		<div class="help_search">
			<input type="text" name="searchword" id="searchword" value="<?php echo ($words); ?>" placeholder="请输入关键字搜索" />
		</div> 
		 
	   <div class="weui-grids">
		  <a href="<?php echo U('News/helps').'?cateid=4';?>" class="weui-grid js_grid">
		    <div class="weui-grid__icon">
		      <img src="/Public/images/help_icon1.png" alt="">
		    </div>
		    <p class="weui-grid__label" style="<?php if($cateid==4) echo 'color:#4564f2;';?>">
		      融客店
		    </p>
		  </a>
		  <a href="<?php echo U('News/helps').'?cateid=5';?>" class="weui-grid js_grid">
		    <div class="weui-grid__icon">
		      <img src="/Public/images/help_icon2.png" alt="">
		    </div>
		    <p class="weui-grid__label" style="<?php if($cateid==5) echo 'color:#4564f2;';?>">
		      注册登录
		    </p>
		  </a>
		  
		  <a href="<?php echo U('News/helps').'?cateid=6';?>" class="weui-grid js_grid">
		    <div class="weui-grid__icon">
		      <img src="/Public/images/help_icon3.png" alt="">
		    </div>
		    <p class="weui-grid__label" style="<?php if($cateid==6) echo 'color:#4564f2;';?>">
		      工资结算
		    </p>
		  </a>
		  
		  <a href="<?php echo U('News/helps').'?cateid=7';?>" class="weui-grid js_grid">
		    <div class="weui-grid__icon">
		      <img src="/Public/images/help_icon4.png" alt="">
		    </div>
		    <p class="weui-grid__label" style="<?php if($cateid==7) echo 'color:#4564f2;';?>">
		      推荐有钱
		    </p>
		  </a>  
		</div>
		
		<section class="help_select khfxWarp">
			<section class="itemlist">
			</section>
		</section>
		<!--查询数据-->	 
		<input type="hidden" name="cateid" class="cateid" value="<?php echo ($cateid); ?>"/>
		<input type="hidden" name="words" class="words" value="<?php echo ($words); ?>"/>

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
	</body>
</html>
<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/dropload.js"></script>
<script type="text/javascript">
	//加载更多
    $(function () {
    var current_page=0;
    var cateid=$('.cateid').val();
    var words=$('.words').val();
    // dropload
    var dropload = $('.khfxWarp').dropload({
        scrollArea: window,
        loadDownFn: function (me) {
            $.ajax({
                type: 'POST',
                url: "<?php echo U('News/gethelpsdata');?>",
                data:"pages="+current_page+"&cateid="+cateid+"&words="+words,
                dataType: 'json',
                success: function(data){
                    var result = '';
                    if(!data.result){
                        me.lock();
                        me.noData();
                        me.resetload();
                    }else{
                        for(var i = 0; i < data.message.length; i++) {
                            result+= ''  
                            +'<dl class="dl_select" onclick="fnopens(this);">'
								+'<dd style="overflow:hidden;">'+data.message[i].Title+'</dd>'
								+'<dt>'+data.message[i].Contents+'</dt>'
							+'</dl>';
                        }
                        setTimeout(function(){
                            $('.itemlist').append(result);
                            current_page++;
                            me.resetload();
                        },0);
                    }
                },
                error: function(xhr, type){
                    alert('加载数据出错');
                    // 即使加载出错，也得重置
                    me.lock();
                    me.noData();
                }
            });

        }
    });

    //搜索功能
    $("#searchword").keydown(function() {
        if (event.keyCode == "13") {  //keyCode=13是回车键
            if($("#searchword").val()==''){
            	alert('请输入关键字...'); return false;
            }
            window.location.href="<?php echo U('News/helps');?>"+"?words="+$("#searchword").val();
        }
    });

});
    //展开文章
    function fnopens(obj){
    	$(obj).toggleClass('cur_show').siblings().removeClass('cur_show');
    }
</script>