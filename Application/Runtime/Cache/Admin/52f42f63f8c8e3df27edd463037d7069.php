<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>Index</title>
    <meta http-equiv=”X-UA-Compatible” content=”IE=edge,chrome=1″ />
    <meta name="viewport" content="width=device-width" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand" />
    <link href="/Public/Admin/JS/EasyUI/easyui.css" rel="stylesheet" />
    <link href="/Public/Admin/images/H/Main.css" rel="stylesheet" />
    <script src="/Public/Admin/JS/jquery.min.js"></script>
    <script src="/Public/Admin/JS/EasyUI/jquery.easyui.min.js"></script>
    <script src="/Public/Admin/JS/XB.js"></script>
    <link href="/Public/Admin/images/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<style>
    .tree-node {
        padding: 3px 0px;
    }

    .tabs li a.tabs-inner {
        border-radius: 3px 3px 0 0;
    }

    .panel-title {
        font-size: 9pt;
        height: 23px;
        line-height: 23px;
    }
    .panel-with-icon {
        padding-left: 22px;
    }
    .tree-node {
        border-bottom: 1px solid #ccc;
        height: 29px;
    }

    .tree-node .tree-title {
        line-height: 29px;
        font-size:9pt;
    }

    .tree-node .tree-icon {
        margin-top: 7px;
        padding-right: 5px;
    }

    .tree-node .tree-expanded {
        background: url('/Public/Admin/JS/EasyUI/images/accordion_arrows.png') no-repeat 0 0;
    }

    .tree-node .tree-collapsed {
        background: url('/Public/Admin/JS/EasyUI/images/accordion_arrows.png') no-repeat -16px 0;
    }

    .tree-node .tree-hit {
        margin-top: 9px;
        float: right;
        margin-right: 6px;
    }

    .tree-node .tree-hit+span {
        margin-left: 16px;
    }
    #tan{
        width: 100%;
        height: 100%;
        position: fixed;
        top:0;
        left:0;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000000000;
        display: none;
    }
    .tan_content{
        width: 80%;
        height: 80%;
        margin: auto;
        background-color: white;
        margin-top: 5%;
        position: relative;
    }
    .close_btn{
        width: 50px;
        height: 50px;
        text-align: center;
        line-height: 50px;
        font-size: 16px;
        color: grey;
        position: absolute;
        right: -35px;
        top:-35px;
    }
</style>
<!--弹窗 start-->
<style type="text/css">
#winpop { width:200px; height:0px; position:absolute; right:0; bottom:0; border:1px solid #666; margin:0; padding:1px; overflow:hidden; display:none;}
#winpop .title { width:100%; height:22px; line-height:20px; background:#FFCC00; font-weight:bold; text-align:center; font-size:12px;}
#winpop .con { width:100%; height:auto; font-weight:bold; font-size:12px; color:blue; text-align:center;background: white;padding-top:0px;padding-bottom:2px;}
#silu { font-size:12px; color:#666; position:absolute; right:0; text-align:right; text-decoration:underline; line-height:22px;}
.close { position:absolute; right:4px; top:-1px; color:#FFF; cursor:pointer}
</style>
<!--弹窗 end-->
<body class="easyui-layout Father">
<div data-options="region:'north',border:false,minWidth:1000">
    <div class="Top">
        <div class="Logo">
            <img src="/Public/Admin/images/H/logo.png" alt="CMS" />
        </div>
        <div id="TopRight" class="TopRight">
            <ul id="topnav">
                <li class="list"><a onclick="Replace();"><span class="c1"></span>后台首页</a></li>

                <li class="list"><a href="https://mpkf.weixin.qq.com/" target="_blank"><span class="c7"></span>微信客服</a></li>
                <li class="list"><a onclick="AddTag('宣传图片', '/admin.php/System/Basicinfo/outimgs', 'icon317');"><span class="c8"></span>宣传图片</a></li>
                <!-- <li class="list"><a onclick="AddTag('基本信息', '/admin.php/System/Basicinfo/index', 'icon279');"><span class="c3"></span>基本信息</a></li> -->
                <li class="list"><a onclick="AddTag('更新缓存', '/admin.php/System/Basicinfo/RefreshCache', 'icon289');"><span class="c6"></span>更新缓存</a></li>
                <li class="list"><a onclick="OpenWin('modifypwd');"><span class="c5"></span>修改密码</a></li>
                <li class="list"><a onclick="LoginOut();"><span class="c4"></span>退出系统</a></li>
            </ul>
        </div>
    </div>
</div>
<div data-options="region:'south',border:false,height:'20px',minWidth:1000">
    <div class="bottomBorder">
        <div class="footer" style="float:left; margin-left: 5px;"><span class="shus"></span>
            <span class="shu"></span>
            <span id="TopDate1"></span>
            <span id="TopDate2"></span>
        </div>
        <div class="footer" style="float:right; text-align: right; margin-right:5px;">
            <text>
                <span style="padding:0px 10px;border-right:1px solid #ccc;">Copyright &copy; 2017 - 2019</span>
                <span style="padding:0px 10px;border-right:1px solid #ccc;">当前账号:<?php echo ($LoginInfo["Admin"]); ?></span>
                <span style="padding:0px 10px;border-right:1px solid #ccc;">所属角色:<?php echo ($LoginInfo["RoleName"]); ?></span>
                <span style="padding:0px 10px;">当前版本Version 1.2.3 [20170919]</span>
            </text>
        </div>
    </div>
</div>
<div data-options="region:'west',collapsible:true,split:true,title:'导航菜单',width:'205px'">
    <div class="easyui-accordion" data-options="fit:true,border:false">
        <?php if(is_array($menu_list)): $i = 0; $__LIST__ = $menu_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><div title='<?php echo ($row["name"]); ?>' data-options="iconCls:'<?php echo ($row["icon"]); ?>'" style="display:none;">
                <ul class="easyui-tree" data-options="animate:true">
                    <?php if(is_array($row["next_menu"])): $i = 0; $__LIST__ = $row["next_menu"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if(empty($val['next_menu']) == true): ?><li data-options="iconCls:'<?php echo ($val["icon"]); ?>',attributes:{url:'/admin.php/<?php echo ($val["url"]); ?>'}"><span><?php echo ($val["name"]); ?></span></li>
                            <?php else: ?>
                            <li data-options="iconCls:'<?php echo ($val["icon"]); ?>'"><span><?php echo ($val["name"]); ?></span>
                                <ul class="easyui-tree" data-options="animate:true,state:'closed'">
                                    <?php if(is_array($val["next_menu"])): $i = 0; $__LIST__ = $val["next_menu"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li data-options="iconCls:'<?php echo ($v["icon"]); ?>',attributes:{url:'/admin.php/<?php echo ($v["url"]); ?>'}"><span><?php echo ($v["name"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <!--弹窗 start-->
    <div id="winpop">
        <div class="title">您有新的消息！<span class="close" onclick="fnclosetc()">X</span></div>
        <div class="con" id="tixianmessid">
        </div>
        <div id="wh_music"></div>
    </div>
    <!--弹窗 end-->
</div>
<div data-options="region:'center'">
    <div id="MTabs" data-options="fit:true,tabHeight:32,scrollIncrement:200,border:false" class="easyui-tabs"></div>
</div>
<script src="/Public/Admin/JS/date.js"></script>
<script>

    function OpenWin(Type) {
        switch (Type) {
            case 'modifypwd':$.XB.open({ 'type':'add','openmode':'0', 'dialog': { 'url': 'admin.php/System/Index/modifypwd/', 'title': '修改密码' } });
                break;

        }}

    $(function () {
        var TopDate = $("#TopDate1");
        showDate(TopDate);
        TopDate = $("#TopDate2");
        setInterval(function () { showTime(TopDate); }, 1000);

        //AddTag("基本信息", "<?php echo U('system/basicinfo/index');?>", "icon279");
        AddTag("后台首页", "<?php echo U('System/Basicinfo/home');?>", "icon314");

        $('.easyui-tree').tree({
            onClick: function (node) {
                if (typeof (node.attributes) != "undefined") {
                    AddTag(node.text, node.attributes.url, node.iconCls);
                }
            }
        });

    });





    function AddTag(title, url, icon) {
        if ($("#MTabs").tabs("exists", title)) {
            $('#MTabs').tabs('update', {
                tab: $('#MTabs').tabs('getTab', title),
                options: {
                    content: '<iframe name="iframe" src="' + url + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
                }
            }).tabs('select', title);
        }
        else {
            $('#MTabs').tabs('add', {
                title: title,
                content: '<iframe name="iframe" src="' + url + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>',
                closable: true,
                selected: true,
                iconCls: icon,
                bodyCls: 'NoScroll'
            });
            TagMenu();
        }
    }
    function TagMenu() {
        /*为选项卡绑定右键*/
        $(".tabs li").on('contextmenu', function (e) {
            /*选中当前触发事件的选项卡 */
            var subtitle = $(this).text();
            $('#MTabs').tabs('select', subtitle);
            //显示快捷菜单
            $('#tab_menu').menu('show', {
                left: e.pageX,
                top: e.pageY
            }).menu({
                onClick: function (item) {
                    closeTab(item.id);
                }
            });

            return false;
        });
        $(".tabs-inner").dblclick(function () {
            var subtitle = $(this).children("span").text();
            $('#MTabs').tabs('close', subtitle);
        })
    }
    function closeTab(action) {
        var alltabs = $('#MTabs').tabs('tabs');
        var currentTab = $('#MTabs').tabs('getSelected');
        var allTabtitle = [];
        $.each(alltabs, function (i, n) {
            allTabtitle.push($(n).panel('options').title);
        })
        switch (action) {
            case "refresh":
                var iframe = $(currentTab.panel('options').content);
                var src = iframe.attr("src");
                $('#MTabs').tabs('update', {
                    tab: currentTab,
                    options: {
                        content: '<iframe name="iframe" src="' + src + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
                    }
                })
                break;
            case "close":
                var currtab_title = currentTab.panel('options').title;
                $('#MTabs').tabs('close', currtab_title);
                break;
            case "closeall":
                $.each(allTabtitle, function (i, n) {
                    $('#MTabs').tabs('close', n);
                });
                break;
            case "closeother":
                var currtab_title = currentTab.panel('options').title;
                $.each(allTabtitle, function (i, n) {
                    if (n != currtab_title) {
                        $('#MTabs').tabs('close', n);
                    }
                });
                break;
            case "closeright":
                var tabIndex = $('#MTabs').tabs('getTabIndex', currentTab);
                $.each(allTabtitle, function (i, n) {
                    if (i > tabIndex) {
                        $('#MTabs').tabs('close', n);
                    }
                });
                break;
            case "closeleft":
                var tabIndex = $('#MTabs').tabs('getTabIndex', currentTab);
                $.each(allTabtitle, function (i, n) {
                    if (i < tabIndex) {
                        $('#MTabs').tabs('close', n);
                    }
                });
                break;
            case "exit":
                $('#tab_menu').menu('hide');
                break;
        }
    }
    function LoginOut() {
        $.post("<?php echo U('System/Login/logout');?>", function (data) {
            if (data.result) {
                top.location.href = data.des;
            }
        }, "json");
    }
</script>
<div id="tab_menu" class="easyui-menu" style="width: 150px;display:none;">
    <div id="refresh">刷新标签</div>
    <div class="menu-sep"></div>
    <div id="close">关闭标签</div>
    <div id="closeall">全部关闭</div>
    <div id="closeother">关闭其他</div>
    <div class="menu-sep"></div>
    <div id="closeright">关闭右边</div>
    <div id="closeleft">关闭左边</div>
    <div class="menu-sep"></div>
    <div id="exit">退出菜单</div>
</div>
<div id="tan" >
    <div class="tan_content" >
        <a href="javascript:void(0)" class="close_btn" onclick="tan_close()" style="background: url('/Public/Admin/images/H/cancel_s.png') center no-repeat"></a>
    </div>
</div>
</body>
<script type="text/javascript">
    function tan_close() {
        $('#tan').hide();
        $('.tan_content').removeAttr('style');
    }
</script>
</html>
<script type="text/javascript">
var tancInterval;
//提现弹窗
function tips_pop(){
  var MsgPop=document.getElementById("winpop");
  var popH=parseInt(MsgPop.style.height);//将对象的高度转化为数字
   if (popH==0){
   MsgPop.style.display="block";//显示隐藏的窗口
  show=setInterval("changeH('up')",2);
   }
  else { 
   hide=setInterval("changeH('down')",2);
  }
}
function changeH(str) {
 var MsgPop=document.getElementById("winpop");
 var popH=parseInt(MsgPop.style.height);
 if(str=="up"){
  if (popH<=100){
  MsgPop.style.height=(popH+4).toString()+"px";
  }
  else{  
  clearInterval(show);
  }
 }
 if(str=="down"){ 
  if (popH>=4){  
  MsgPop.style.height=(popH-4).toString()+"px";
  }
  else{ 
  clearInterval(hide);   
  MsgPop.style.display="none";  //隐藏DIV
  }
 }
}
// window.onload=function(){    //加载
// document.getElementById('winpop').style.height='0px';
// setTimeout("tips_pop()",800);     //3秒后调用tips_pop()这个函数
// }
$(function(){
    //提现弹窗提醒功能
     //setInterval("mytips_pop()",10000); 
      //tancInterval=setInterval("mytips_pop()",10000);
});
function mytips_pop(){
    //查询有多少个未处理信息
    $.ajax({
        type: "POST",
        url: "../admin.php/Jobmanage/Human/getnotices",
        dataType: "json",
        success:function(data){
            if(data.result=='1'){
                //有提现消息
                var msge='';
                if(data.message.comtransfer!='0'){
                    msge+='公司转让<span style="color:#FF0000;">&nbsp;'+data.message.comtransfer+'&nbsp;</span>条未处理<br/>';
                }
                if(data.message.zrliuyan!='0'){
                    msge+='转让留言<span style="color:#FF0000;">&nbsp;'+data.message.zrliuyan+'&nbsp;</span>条未处理<br/>';
                }
                if(data.message.xuqiuliuyan!='0'){
                    msge+='需求留言<span style="color:#FF0000;">&nbsp;'+data.message.xuqiuliuyan+'&nbsp;</span>条未处理<br/>';
                }
                if(data.message.qiyezp!='0'){
                    msge+='企业招聘<span style="color:#FF0000;">&nbsp;'+data.message.qiyezp+'&nbsp;</span>条未处理<br/>';
                }
                if(data.message.rcqiuzhi!='0'){
                    msge+='人才求职<span style="color:#FF0000;">&nbsp;'+data.message.rcqiuzhi+'&nbsp;</span>条未处理<br/>';
                }
                $('#tixianmessid').html(msge);
                //发出提示声音
               // $('#wh_music').append("<audio src='/Public/music/succ.wav'  autoplay='autoplay'></audio>");
                document.getElementById('winpop').style.height='0px';
                tips_pop();
            }
        }
    });
}
//关闭弹窗
function fnclosetc(){
    if(confirm("关闭不再提醒!")){
        clearInterval(tancInterval);
        tips_pop();
    }
}
</script>