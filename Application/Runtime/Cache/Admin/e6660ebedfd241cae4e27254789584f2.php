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
<body class="Bodybg">
<div class="easyui-panel" data-options="fit:true,border:false,bodyCls:'Bodybg',footer:'#ft'">
    <form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
            <thead>
                <tr>
                    <td colspan="4">说明：<span class="Red">*</span>不要频繁操作!</td>
                </tr>
            </thead>
        </table>
        <fieldset style="border: 1px solid #ccc;margin:5px;">
            <legend>自定义菜单</legend>
            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                <tbody>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">一级菜单：</td>
                    <td style="width:30%;text-align: center;" align="right">第一列</td>
                    <td style="width:30%;text-align: center;" align="right">第二列</td>
                    <td style="width:30%;text-align: center;" align="right">第三列</td>
                </tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right"></td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1" name="name1" value="<?php echo ($memuinfos['button'][0]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1" name="linkurl1" value="<?php echo ($memuinfos['button'][0]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2" name="name2" value="<?php echo ($memuinfos['button'][1]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2" name="linkurl2" value="<?php echo ($memuinfos['button'][1]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3" name="name3" value="<?php echo ($memuinfos['button'][2]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3" name="linkurl3" value="<?php echo ($memuinfos['button'][2]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.1 start-->
                <tr><td style="height:5px;"></td></tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">二级菜单NO.1</td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1_chird1" name="name1_chird1" value="<?php echo ($memuinfos['button'][0]['sub_button'][0]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1_chird1" name="linkurl1_chird1" value="<?php echo ($memuinfos['button'][0]['sub_button'][0]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2_chird1" name="name2_chird1" type="text" value="<?php echo ($memuinfos['button'][1]['sub_button'][0]['name']); ?>" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2_chird1" name="linkurl2_chird1" value="<?php echo ($memuinfos['button'][1]['sub_button'][0]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3_chird1" name="name3_chird1" value="<?php echo ($memuinfos['button'][2]['sub_button'][0]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3_chird1" name="linkurl3_chird1" value="<?php echo ($memuinfos['button'][2]['sub_button'][0]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.1 end-->
                <!--二级菜单NO.2 start-->
                <tr><td style="height:5px;"></td></tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">二级菜单NO.2</td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1_chird2" name="name1_chird2" type="text" value="<?php echo ($memuinfos['button'][0]['sub_button'][1]['name']); ?>" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1_chird2" name="linkurl1_chird2" value="<?php echo ($memuinfos['button'][0]['sub_button'][1]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2_chird2" name="name2_chird2" value="<?php echo ($memuinfos['button'][1]['sub_button'][1]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2_chird2" name="linkurl2_chird2" value="<?php echo ($memuinfos['button'][1]['sub_button'][1]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3_chird2" name="name3_chird2" value="<?php echo ($memuinfos['button'][2]['sub_button'][1]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3_chird2" name="linkurl3_chird2" value="<?php echo ($memuinfos['button'][2]['sub_button'][1]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.2 end-->
                <!--二级菜单NO.3 start-->
                <tr><td style="height:5px;"></td></tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">二级菜单NO.3</td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1_chird3" name="name1_chird3" value="<?php echo ($memuinfos['button'][0]['sub_button'][2]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1_chird3" name="linkurl1_chird3" value="<?php echo ($memuinfos['button'][0]['sub_button'][2]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2_chird3" name="name2_chird3" value="<?php echo ($memuinfos['button'][1]['sub_button'][2]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2_chird3" name="linkurl2_chird3" value="<?php echo ($memuinfos['button'][1]['sub_button'][2]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3_chird3" name="name3_chird3" value="<?php echo ($memuinfos['button'][2]['sub_button'][2]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3_chird3" name="linkurl3_chird3" value="<?php echo ($memuinfos['button'][2]['sub_button'][2]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.3 end-->
                <!--二级菜单NO.4 start-->
                <tr><td style="height:5px;"></td></tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">二级菜单NO.4</td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1_chird4" name="name1_chird4" value="<?php echo ($memuinfos['button'][0]['sub_button'][3]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1_chird4" name="linkurl1_chird4" value="<?php echo ($memuinfos['button'][0]['sub_button'][3]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2_chird4" name="name2_chird4" value="<?php echo ($memuinfos['button'][1]['sub_button'][3]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2_chird4" name="linkurl2_chird4" value="<?php echo ($memuinfos['button'][1]['sub_button'][3]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3_chird4" name="name3_chird4" value="<?php echo ($memuinfos['button'][2]['sub_button'][3]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3_chird4" name="linkurl3_chird4" value="<?php echo ($memuinfos['button'][2]['sub_button'][3]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.4 end-->
                <!--二级菜单NO.5 start-->
                <tr><td style="height:5px;"></td></tr>
                <tr>
                    <td style="width:10%;text-align: center;" align="right">二级菜单NO.5</td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name1_chird5" name="name1_chird5" value="<?php echo ($memuinfos['button'][0]['sub_button'][4]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl1_chird5" name="linkurl1_chird5" value="<?php echo ($memuinfos['button'][0]['sub_button'][4]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name2_chird5" name="name2_chird5" value="<?php echo ($memuinfos['button'][1]['sub_button'][4]['name']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl2_chird5" name="linkurl2_chird5" value="<?php echo ($memuinfos['button'][1]['sub_button'][4]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:30%;text-align: center;" align="right">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                            <tr>
                                <td><div style="width:40px;">名称:</div></td>
                                <td><input id="name3_chird5" name="name3_chird5" value="<?php echo ($memuinfos['button'][2]['sub_button'][4]['name']); ?>"  type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                            <tr>
                                <td>URL:</td>
                                <td><input id="linkurl3_chird5" name="linkurl3_chird5" value="<?php echo ($memuinfos['button'][2]['sub_button'][4]['url']); ?>" type="text" class="easyui-textbox" style="width:70%;"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--二级菜单NO.5 end-->
                </tbody>
            </table>
        </fieldset>
    </form>
</div>
<div id="ft" style="padding:4px;text-align:center;height:40px;line-height:40px;">
    <input onclick="$.XB.pagesave({
    'url': '/admin.php/Wechat/Wechatmenus/save/', 'fn': function (data) {
        $.XB.success({
            'message': data.message, 'fn': function () {
                $.XB.topreload();
            }
        });
    }
});" type="button" id="saveb" value=" 确定保存 ">
</div>
<script>

$(function () {
        $('#FF').form('load', 'shows?ID=0&_=' + Math.random() + '');
        $("#MainDiv").height($(window).height());
        $.XB.pictips({ 'id': '#Logodd', 'path': '#Logo' });
        $.XB.pictips({ 'id': '#MLogodd', 'path': '#MLogo' });
        $.XB.pictips({ 'id': '#WeChatQRdd', 'path': '#WeChatQR' });
        $.XB.pictips({ 'id': '#AppUpCodedd', 'path': '#AppUpCode' });
 });

//$(function () {
//    var count=14;
//    //根据条件判断是否需要提醒
//    if(count>0){
//        $.XB.CorOPUrl('/admin.php/System/Notes/index', '提醒信息', '590', '320', 'Icon83');
//    }
//})

</script>
</body>
</html>