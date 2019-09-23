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


			.ul_lookselect span a.selected{border-color:#5461eb;color:#5461eb;}
			 .noselect{border-color:#eee;color:#000;}

			 .loan_icon1 label{ padding-left: 20px; background: url(/Public/images/loan_icon1.png) left center no-repeat; background-size: 19px auto;}
			 .loan_icon2 label{ padding-left: 20px; background: url(/Public/images/loan_icon5.png) left center no-repeat; background-size: 19px auto;}
			 .loan_icon3 label{ padding-left: 20px; background: url(/Public/images/loan_icon3.png) left center no-repeat; background-size: 19px auto;}

			 .mui-icon-arrowdown span.loan_icon1hover{ background: url(/Public/images/icon_btselect1.png) right center no-repeat;    background-size: 7px auto;}
			 .loan_icon1hover label{ background: url(/Public/images/loan_icon4.png) left center no-repeat; background-size: 19px auto; color:#5461eb;}

			 .mui-icon-arrowdown span.loan_icon2hover{ background: url(/Public/images/icon_btselect1.png) right center no-repeat;    background-size: 7px auto;}
			 .loan_icon2hover label{ background: url(/Public/images/loan_icon2.png) left center no-repeat; background-size: 19px auto; color:#5461eb;}

			 .mui-icon-arrowdown span.loan_icon3hover{ background: url(/Public/images/icon_btselect1.png) right center no-repeat;    background-size: 7px auto;}
			 .loan_icon3hover label{ background: url(/Public/images/loan_icon6.png) left center no-repeat; background-size: 19px auto; color:#5461eb;}

			 .mainmenu{ background: url(/Public/images/line_tab.png) left center no-repeat; background-size:auto 20px;}
			.ul_lookselect{
				padding-bottom: 50px;
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
			}
			.ul_lookselect .catesel{
				display: block;
				margin: 0 2%;
				width: 20%;
				border:1px solid #eee;
				text-align: center;
				padding: 5px 0;
				margin-bottom: 4%;
				border-radius: 4px;
			}
			.selected{
				color: #5461eb;
				border: 1px solid #5461eb!important;
			}
		</style>
	</head>
	<body>
		<header class="header" >
			<a href="javascript:history.go(-1)" class="go_left"></a>
			<span class="header_til">找借贷</span>
		</header>
		<div class="" style="height: 50px;"></div>
	  <!---->
	  <section class="hd"></section>
	  <!---->
	  <div class="box_con"  style="margin-top: 55px;" >
			         <div style="width:100%; height:100%; background-color:#000000; position: fixed; top:45px; left:0; z-index:998;filter:alpha(opacity=70);-moz-opacity:0.7;opacity:0.7; display:none;" id="menubg" onClick="btnbg()"></div>
						<div style="position: fixed; width:100%;top:45px;height:45px;overflow:visible; z-index:999">
							<div style="height:40px;background-color:#fff;border-bottom: 1px solid #f7f7f7;">
								<div id="icon-menu1" onClick="btn1()" class="mainmenu mui-icon mui-icon-arrowdown" style="width: 33.3%;"><span class="loan_icon1"><label>期限</label></span></div>
								<div id="icon-menu2" onClick="btn2()" class="mainmenu mui-icon mui-icon-arrowdown" style="width: 33.3%;"><span class="loan_icon2"><label>金额</label></span></div>
								<div id="icon-menu3" onClick="btn3()" class="mainmenu mui-icon mui-icon-arrowdown" style="width: 33.3%;"><span class="loan_icon3"><label>类型</label></span></div>

							</div>
							<div id="menu-wrapper" >
								<!--菜单1-->
								<div id="menu1" class="menu_box1"   style=" display:none;">
									<ul class="ul_jine">
									  <?php foreach($jktimelist as $k=>$v):?>
										<li class="mui-table-view-cell" f="pr" v="clear" jktimesid="<?php echo ($v["ID"]); ?>" onclick="fnjktimes(this)" style="<?php if($jkday==$v['ID']) echo 'color:#fff; background-color: #5461eb;';?>"><?php echo ($v["Name"]); ?></li>
									  <?php endforeach;?>
									</ul>
								</div>
								<!--菜单1结束-->
								<div id="menu2" class="menu_box1" style="display:none;">
									<ul class="ul_jine">
									  <?php foreach($moneylist as $k=>$v):?>
										<li class="mui-table-view-cell" f="pr" v="clear" moneyid="<?php echo ($v["ID"]); ?>" onclick="fnmoney(this)" style="<?php if($mtype==$v['ID']) echo 'color:#fff; background-color: #5461eb;';?>"><?php echo ($v["Name"]); ?></li>
									  <?php endforeach;?>
									</ul>
								</div>
								<div id="menu3" class="menu_box1" style="display:none;height:auto;">
									 <p style="padding: 10px;">我有</p>
									 <div class="ul_lookselect" >
									   <a href="javascript:void(0);" class="catesel <?php if($cateid=='0') echo 'selected';?>" onclick="fncate(this)" cateid="0">全部</a>
									   <?php foreach($catelist as $k=>$v):?>
									 	<a href="javascript:void(0);" class="catesel <?php if($cateid==$v['ID']) echo 'selected';?>" onclick="fncate(this)" cateid="<?php echo ($v["ID"]); ?>"><?php echo ($v["Name"]); ?></a>
									   <?php endforeach;?>
									 	<div class="clear"></div>
									 </div>
								<!--
									 <p style="padding: 10px;">我需要</p>
									 <div class="ul_lookselect">
									  <?php foreach($needlist as $k=>$v):?>
									 	<span style="width:auto;"><a href="javascript:void(0);" class="needsel <?php if(strpos($needid,$v['ID'])!==false) echo 'selected';?>" needid="<?php echo ($v["ID"]); ?>" onclick="fnneed(this)"><?php echo ($v["Name"]); ?></a></span>
									  <?php endforeach;?>
									 	<div class="clear"></div>
									 </div>
								-->
									 <section class="postion_re" style="position:relative;">
									 	<a href="javascript:void(0);" onclick="fnreset()">重置</a>
									 	<a href="javascript:void(0);" class="cur" onclick="fnsure()">确认</a>
									 </section>
								</div>
								<div id="menu4"  class="menu_box1" style="display:none;"></div>
								<!--菜单4结束-->
							</div>
							<div id="menu-backdrop" class="menu-backdrop"></div>
						</div>
		     	</div>
		<article class="khfxWarp" style="padding-bottom:60px;">
			 <section style="margin-top: -20px;" class="itemlist">
			 </section>
		</article>
<!--查询数据-->
<input type="hidden" name="jkday" class="jkday" value="<?php echo ($jkday); ?>"/>
<input type="hidden" name="mtype" class="mtype" value="<?php echo ($mtype); ?>"/>
<input type="hidden" name="cateid" class="cateid" value="<?php echo ($cateid); ?>"/>
<input type="hidden" name="needid" class="needid" value="<?php echo ($needid); ?>"/>

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

    <!-- <a href="<?php echo U('Item/creditv');?>" class="weui-tabbar__item <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')) echo 'weui-bar__item--on';?>">
        <div class="weui-tabbar__icon">
            <?php if(CONTROLLER_NAME=='Item' && (ACTION_NAME=='creditv' || ACTION_NAME=='khlist')):?>
                <img src="/Public/images/ic_promote_selected.png" alt=""/>
             <?php else:?>
                <img src="/Public/images/ic_promote_normal.png" alt=""/>
             <?php endif;?>
        </div>
        <p class="weui-tabbar__label">找信用卡</p>
    </a> -->
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
    var jkday=$('.jkday').val();
    var mtype=$('.mtype').val();
    var cateid=$('.cateid').val();
    var needid=$('.needid').val();
    // dropload
    var dropload = $('.khfxWarp').dropload({
        scrollArea: window,
        loadDownFn: function (me) {

            $.ajax({
                type: 'POST',
                url: "<?php echo U('Item/indexdata');?>",
                data:"pages="+current_page+"&mtype="+mtype+"&cateid="+cateid+"&needid="+needid+"&jkday="+jkday,
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
                            +'<section style="border-bottom: 1px solid #f7f7f7;border-bottom: 4px solid #f7f7f7;">'
							 	+'<dl class="dl_hot">'
							 		+'<a href=\"<?php echo U("Item/detail");?>?id='+data.message[i].ID+'\" class="k_logo"><img src="'+data.message[i].Logurl+'" style="width:58px; height: 58px;"></a>'
							 		+'<dd>'+data.message[i].Name+'<a href=\"<?php echo U("Item/detail");?>?id='+data.message[i].ID+'\"><img src="/Public/images/icon_hot.png" style="width: 45px;"></a></dd>'
							 		+'<dt class="dt1">'+data.message[i].Intro+'</dt>'
									+'<dt class="dt2"><span style="color: #5D95F1">'+data.message[i].AppNumbs+'</span>人申请</dt>'
									+'<a href=\"<?php echo U("Item/detail");?>?id='+data.message[i].ID+'\" class="btn_shenqing">申请</a>'
								+'</dl>'
								+'<ul class="ul_index2">'
									+'<a href=\"<?php echo U("Item/detail");?>?id='+data.message[i].ID+'\">'
										+'<li>'
											+'<span class="sp_1">额度(元)</span>'
											+'<span class="sp_2">'+data.message[i].eduname+'</span>'
										 +'</li>'
										 +'<li>'
											+'<span class="sp_1">借款期限</span>'
											+'<span class="sp_2">'+data.message[i].Jkdays+'</span>'
										 +'</li>'
										 +'<li style="border-right: none;">'
											+'<span class="sp_1">日费率</span>'
											+'<span class="sp_2" style="color:#fb8828;">'+data.message[i].DayfeeRate+'</span>'
										 +'</li>'
										 +'<div class="clear"></div>'
						          	+'</a>'
								+'</ul>'
								+'<section class="borderbt8"></section>'
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
                    //XB.Tip('加载数据出错');
                    $.alert('加载数据出错');
                    // 即使加载出错，也得重置
                    me.lock();
                    me.noData();
                }
            });

        }
    });
});
//贷款期限搜索
function fnjktimes(obj){
	var jktimesid=$(obj).attr('jktimesid');
	$('.jkday').val(jktimesid);
	againselect();
}
//金额类型搜索
function fnmoney(obj){
	var moneyid=$(obj).attr('moneyid');
	$('.mtype').val(moneyid);
	againselect();
}
//我有  分类的选择 单选
function fncate(obj){
	$(obj).addClass("selected");
	var cateid=$(obj).attr('cateid');
	$('.catesel').each(function(){
		if($(this).attr('cateid')!=cateid){
			$(this).removeClass('selected');
		}
	});
}
//我需要 多选
function fnneed(obj){
	if($(obj).hasClass('selected')){
		$(obj).removeClass('selected');
	}else{
		$(obj).addClass("selected");
	}
}
//重置
function fnreset(){
	//我有 默认选择 全部
	$('.catesel').each(function(){
		if($(this).attr('cateid')!=0){
			$(this).removeClass('selected');
		}else{
			$(this).addClass('selected');
		}
	});
	//我需要  全部取消
	$('.needsel').each(function(){
		$(this).removeClass('selected');
	});
}
//确定搜索
function fnsure(){
	//选择选中的选项
	var myhave=[];
	var myneed=[];
	var myhaves='';
	var myneeds='';
	$('.catesel').each(function(){
		if($(this).hasClass('selected')){
			myhave.push($(this).attr('cateid'));
		}
	});
	$('.needsel').each(function(){
		if($(this).hasClass('selected')){
			myneed.push($(this).attr('needid'));
		}
	});
    if(myhave.length>0){
    	myhaves=myhave.join(',');
    }
    if(myneed.length>0){
    	myneeds=myneed.join(',');
    }
    $('.cateid').val(myhaves);
    $('.needid').val(myneeds);
    $('.menu_box1').css('display','none');
    $('#menubg').css('display','none');
    againselect();
}
//重新获取搜索数据
function againselect(){
	var jkday=$('.jkday').val();
	var mtype=$('.mtype').val();
	var cateid=$('.cateid').val();
	var needid=$('.needid').val();
	var url="<?php echo U('Item/index');?>";
	if(jkday || mtype || cateid || needid){
		//url+='?mtype='+mtype+'&cateid='+cateid+'&needid='+needid;
		url+='?jkday='+jkday+'&mtype='+mtype+'&cateid='+cateid+'&needid='+needid;
	}
	window.location.href=url;
}
</script>

 <script>
	var finReload = false;

	var menuWrapper = document.getElementById("menu-wrapper");
	var menubg = document.getElementById("menubg");
	var menu1 = document.getElementById("menu1");
	var menu2 = document.getElementById("menu2");
	var menu3 = document.getElementById("menu3");
	var menu4 = document.getElementById("menu4");
	var menuWrapperClassList = menuWrapper.classList;
	var backdrop = document.getElementById("menu-backdrop");
	var iconmenu1 = document.getElementById("icon-menu1");
	var iconmenu2 = document.getElementById("icon-menu2");
	var iconmenu3 = document.getElementById("icon-menu3");
	var iconmenu4 = document.getElementById("icon-menu4");

	function btnbg(){
		menu1.style.display='none';
		menu2.style.display='none';
		menu3.style.display='none';
		menu4.style.display='none';
		menubg.style.display='none';
	}
	function btn1(){
		if(menu1.style.display==""){
			menu1.style.display='none';
			menubg.style.display='none';
			}else{
			menu1.style.display='';
			menu2.style.display='none';
			menu3.style.display='none';
			menu4.style.display='none';
			menubg.style.display='';

		}
		//综合排序
		//window.location.href="<?php echo U('Item/index');?>";
	}
	function btn2(){ if(menu2.style.display==""){
		menu2.style.display='none';
		menubg.style.display='none';
		}else{
		menu1.style.display='none';
		menu2.style.display='';
		menu3.style.display='none';
		menu4.style.display='none';
		menubg.style.display='';
	}
	}function btn3(){ if(menu3.style.display==""){
		menu3.style.display='none';
		menubg.style.display='none';
		}else{
		menu1.style.display='none';
		menu2.style.display='none';
		menu3.style.display='';
		menu4.style.display='none';
		menubg.style.display='';
	}
	}
	function btn4(){ if(menu4.style.display==""){
		menu4.style.display='none';
		menubg.style.display='none';
		}else{
		menu1.style.display='none';
		menu2.style.display='none';
		menu3.style.display='none';
		menu4.style.display='';
		menubg.style.display='';
	}
	}

	backdrop.addEventListener('tap', toggleMenu);
	document.getElementById("icon-menu1").addEventListener('tap',toggleMenu)
	mui('#menu1').on('tap', 'li', function() {
		toggleMenu();
		iconmenu1.classList.remove('mui-icon-arrowdown');
		iconmenu1.innerHTML = this.innerHTML;
		menu1.style.display='none';
		menubg.style.display='none';

        setQuerys(this);
        pageReload();
	});
	mui('#menu2').on('tap', 'li', function() {
		toggleMenu();
		iconmenu2.classList.remove('mui-icon-arrowdown');
		iconmenu2.innerHTML = this.innerHTML;
		menu2.style.display='none';
		menubg.style.display='none';

        setQuerys(this);
        pageReload();
	});
	mui('#menu3').on('tap', 'li', function() {
		toggleMenu();
		iconmenu3.classList.remove('mui-icon-arrowdown');
		iconmenu3.innerHTML = this.innerHTML;
		menu3.style.display='none';
		menubg.style.display='none';

        setQuerys(this);
        pageReload();
	});
	mui('#menu4').on('tap', 'li', function() {
		toggleMenu();
		iconmenu4.classList.remove('mui-icon-arrowdown');
		iconmenu4.innerHTML = this.innerHTML;
		menu4.style.display='none';
		menubg.style.display='none';

        setQuerys(this);
        pageReload();
	});

	var qs = {};
	for(var k in qs){
		var li = $('li[f="'+k+'"][v="'+qs[k]+'"]');
		mui.trigger(li[0],'tap');
		li.addClass('mui-active');
		li.siblings().removeClass('mui-active');

		var ccont = li.closest('.mui-control-content');
		ccont.addClass('mui-active');
		ccont.siblings().removeClass('mui-active');

		var disid = ccont.attr('id');
		$('a[href="#'+disid+'"]').addClass('mui-active');
		$('a[href="#'+disid+'"]').siblings().removeClass('mui-active');
	}
	finReload = true;

	$('#btnsearch').change(function () {
        location.href = 'officelisting.php?kw=' + this.value;
    });

	var busying = false;
	function toggleMenu() {
		if (busying) {
			return;
		}
	}

	function setQuerys(obj) {
	    if(!$(obj).attr('f')){
	        return;
        }

        if($(obj).attr('v') == 'clear'){
            delete querys[$(obj).attr('f')];
        }else {
            querys[$(obj).attr('f')] = $(obj).attr('v');
        }
    }

	function pageReload()
	{
		if(!finReload)return false;

		var query = [];
		for(var f in querys ){
			if( querys[f] !== '' && querys[f] != null ){
				query.push(f + "=" + querys[f]);
			}
		}
		location.href = 'officelisting.php?' + query.join('&');
	}
</script>