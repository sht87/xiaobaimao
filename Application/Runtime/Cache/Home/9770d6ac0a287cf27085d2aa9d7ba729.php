<?php if (!defined('THINK_PATH')) exit();?>       <!DOCTYPE html>
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

       <link rel="stylesheet" href="/Public/css/dropload.css" />
       <style type="text/css">
            .weui-panel__bd a {
                border: 1px solid #fff;
            }

            .weui-media-box:before {

            }

            .weui-panel__bd {
                border-bottom: none;
            }

            .weui-navbar:after {
                border-bottom: none;
            }

            .weui-navbar__item:after {

            }

            .weui-navbar__item {
                margin: 10px 0;
                padding: 0;
            }

           .menu_box1{
               height:auto;
           }
            .mainmenu{
                width:25%;
            }
        </style>
        <header class="header">
            <a href="<?php echo U('Item/credit');?>" class="go_left"></a>
            <span class="header_til">信用卡中心</span>
        </header>
        <div class="" style="height: 50px;"></div>
        <!---->
       <section class="hd"></section>
       <!---->

       <div class="box_con"  style="margin-top: 45px;" >
           <div style="width:100%; height:100%; background-color:#000000; position: fixed; top:45px; left:0; z-index:998;filter:alpha(opacity=70);-moz-opacity:0.7;opacity:0.7; display:none;" id="menubg" onClick="btnbg()"></div>
           <div style="position: fixed; width:100%;top:45px;overflow:visible; z-index:999">
               <div style="height:40px;background-color:#fff;">
                   <!-- <div id="icon-menu5" onClick="btn5()" class="mainmenu mui-icon mui-icon-arrowdown" ><span>综合排序</span></div> -->
                   <div id="icon-menu1" onClick="btn1()" class="mainmenu mui-icon mui-icon-arrowdown" ><span>所有银行</span></div>
                   <div id="icon-menu2" onClick="btn2()" class="mainmenu mui-icon mui-icon-arrowdown" ><span>卡种</span></div>
                   <div id="icon-menu3" onClick="btn3()" class="mainmenu mui-icon mui-icon-arrowdown" ><span>币种</span></div>
                    <div id="icon-menu4" onClick="btn4()" class="mainmenu mui-icon mui-icon-arrowdown" ><span>年费</span></div>
               </div>
               <div id="menu-wrapper" >
                   <!--菜单1 按银行种类搜索-->
                   <div id="menu1" class="menu_box1" style="display:none;">
                       <ul class="ul_jine"><!--mui-table-view-->
                           <?php foreach($bankList as $k=>$v):?>
                           <li class="mui-table-view-cell" f="pr" v="clear" data-val="<?php echo ($v["ID"]); ?>" onclick="fnbank(this)" style="<?php if($BankID==$v['ID']) echo 'color:#5461eb;';?>"><?php echo ($v["BankName"]); ?></li>
                           <?php endforeach;?>
                       </ul>
                   </div>
                   <!--菜单1结束-->

                   <!--菜单2 按卡中搜索-->
                   <div id="menu2" class="menu_box1" style="display:none;">
                       <ul class="ul_jine">
                           <?php foreach($cardList as $k=>$v):?>
                           <li class="mui-table-view-cell" f="pr" v="clear" data-val="<?php echo ($v["ID"]); ?>" onclick="fncard(this)" style="<?php if($KatypeID==$v['ID']) echo 'color:#5461eb;';?>"><?php echo ($v["Name"]); ?></li>
                           <?php endforeach;?>
                       </ul>
                   </div>
                   <!--菜单2结束-->

                   <!--菜单3 按币种类搜索-->
                   <div id="menu3" class="menu_box1" style="display:none;">
                       <ul class="ul_jine">
                           <?php foreach($subjectlist as $k=>$v):?>
                           <li class="mui-table-view-cell" f="pr" v="clear" data-val="<?php echo ($v["ID"]); ?>" onclick="fnsubject(this)" style="<?php if($Sbid==$v['ID']) echo 'color:#5461eb;';?>"><?php echo ($v["Name"]); ?></li>
                           <?php endforeach;?>
                       </ul>
                   </div>
                   <!--菜单3结束-->

                   <!--菜单4 按年费用搜索-->
                   <div id="menu4" class="menu_box1" style="display:none;">
                       <ul class="mui-table-view">
                           <?php foreach($feeList as $k=>$v):?>
                           <li class="mui-table-view-cell" f="pr" v="clear" data-val="<?php echo ($v["ID"]); ?>" onclick="fnfee(this)" style="<?php if($YearfeeID==$v['ID']) echo 'color:#5461eb;';?>"><?php echo ($v["Name"]); ?></li>
                           <?php endforeach;?>
                       </ul>
                   </div>
                   <!--菜单4结束-->
               </div>
               <div id="menu-backdrop" class="menu-backdrop"></div>
           </div>
       </div>
        <!---->
       <article class="khfxWarp" style="padding-bottom:60px;">
            <section id="creditList" style="margin-top: -20px;">
            </section>
       </article>

       <!--查询数据-->
       <input type="hidden" name="BankID" class="BankID" value="<?php echo ($BankID); ?>"/>
       <input type="hidden" name="KatypeID" class="KatypeID" value="<?php echo ($KatypeID); ?>"/>
       <input type="hidden" name="Sbid" class="Sbid" value="<?php echo ($Sbid); ?>"/>
       <input type="hidden" name="YearfeeID" class="YearfeeID" value="<?php echo ($YearfeeID); ?>"/>

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
       <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
       <script type="text/javascript" src="/Public/js/dropload.js"></script>
       <script type="text/javascript">
           //加载更多
           $(function () {
               var current_page=0;
               var BankID=$('.BankID').val();
               var KatypeID=$('.KatypeID').val();
               var Sbid=$('.Sbid').val();
               var YearfeeID=$('.YearfeeID').val();
               // dropload
               var dropload = $('.khfxWarp').dropload({
                   scrollArea: window,
                   loadDownFn: function (me) {

                       $.ajax({
                           type: 'POST',
                           url: "<?php echo U('Item/clistdata');?>",
                           data:"pages="+current_page+'&BankID='+BankID+'&KatypeID='+KatypeID+'&Sbid='+Sbid+'&YearfeeID='+YearfeeID,
                           dataType: 'json',
                           success: function(data){
                               var result = '';
                               if(!data.result){
                                   me.lock();
                                   me.noData();
                                   me.resetload();
                               }else{
                                   result='';
                                   for(var i = 0; i < data.message.length; i++) {
                                       result+='<dl class="dl_hot dl_hot1">'
                                       result+='<a href=\"<?php echo U("Item/cdetail");?>?id='+data.message[i].ID+'\" class="k_logo" style="top:18px"><img src="'+data.message[i].Logurl+'" style="width:86px; height: 52px; "></a><a href=\"<?php echo U("Item/cdetail");?>?id='+data.message[i].ID+'\">'
                                               if(data.message[i].Name.length>10){
                                                   result+='<dd>'+data.message[i].Name.substr(0,10)+'...</dd>'
                                               }else{
                                                   result+='<dd>'+data.message[i].Name+'</dd>'
                                               }
                                       result+='<dt class="dt2" style="padding-top: 5px;">'
                                       result+='<label class="fl lab_1">'+data.message[i].BankName+'</label>'
                                       result+='<label class="fl lab_2">专享</label>'
                                       result+='<span class="fr"><label class="span_label">'+data.message[i].AppNumbs+'</label>人申请</span>'
                                       result+='</dt></a>'
                                       result+='</dl>'
                                   }

                                   setTimeout(function(){
                                       $('#creditList').append(result);
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
           //银行类型搜索
           function fnbank(obj){
               var BankID=$(obj).attr('data-val');
               $('.BankID').val(BankID);
               againselect();
           }
           //卡种类搜索
           function fncard(obj){
               var KatypeID=$(obj).attr('data-val');
               $('.KatypeID').val(KatypeID);
               againselect();
           }
           //币种搜索
           function fnsubject(obj){
               var Sbid=$(obj).attr('data-val');
               $('.Sbid').val(Sbid);
               againselect();
           }
           //年费类搜索
           function fnfee(obj){
               var YearfeeID=$(obj).attr('data-val');
               $('.YearfeeID').val(YearfeeID);
               againselect();
           }

           //重新获取搜索数据
           function againselect(){
               var BankID=$('.BankID').val();
               var Sbid=$('.Sbid').val();
               var KatypeID=$('.KatypeID').val();
               var YearfeeID=$('.YearfeeID').val();
               var url="<?php echo U('Item/clist');?>";
               if(BankID || Sbid || KatypeID || YearfeeID){
                   url+='?BankID='+BankID+'&Sbid='+Sbid+'&KatypeID='+KatypeID+'&YearfeeID='+YearfeeID;
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

           var querys = {kw:$.getUrlParam('kw')};

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
           }

           function btn2(){
               if(menu2.style.display==""){
                   menu2.style.display='none';
                   menubg.style.display='none';
                }else{
                   menu1.style.display='none';
                   menu2.style.display='';
                   menu3.style.display='none';
                   menu4.style.display='none';
                   menubg.style.display='';
                }
           }

           function btn3(){
               if(menu3.style.display==""){
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

           function btn4(){
               if(menu4.style.display==""){
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

           function btn5(){
               if(menu1.style.display==""){
                   menu1.style.display='none';
                   menubg.style.display='none';
               }else{
                   menu1.style.display='none';
                   menu2.style.display='none';
                   menu3.style.display='none';
                   menu4.style.display='none';
                   menubg.style.display='none';

               }
               //综合排序
               window.location.href="<?php echo U('Item/clist');?>";
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
       </script>