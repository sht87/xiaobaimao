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
<form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
        <thead>
        <tr>
            <td colspan="2">说明：会员资料只能修改基本信息，有关账号及资金部分不可修改！</td>
        </tr>
        </thead>
        <tbody>
        <!--<tr >-->
            <!--<td width="120" align="right"> 会员账号：</td>-->
            <!--<td>-->
                <!--<input id="UserName" name="UserName" type="text" class="easyui-textbox" data-options="disabled:true"/>-->
                <!--<span class="Hui">注册时产生的账号，唯一且不可更改！</span>-->
            <!--</td>-->
        <!--</tr>-->
        <tr >
            <td width="120" align="right">真实姓名：</td>
            <td>
                <input id="TrueName" name="TrueName" class="easyui-textbox" type="text" />
                <span class="Hui">会员的真实姓名,允许修改！</span>
            </td>
        </tr>
        <tr >
            <td width="120" align="right">会员类型：</td>
            <td>
                <input id="Memtype" name="Memtype" type="text" class="easyui-textbox" data-options="disabled:true" />
                <span class="Hui">会员的真实姓名,允许修改！</span>
            </td>
        </tr>
        <tr >
            <td width="120" align="right">手机号码：</td>
            <td>
                <input id="Mobile" name="Mobile" type="text" class="easyui-textbox" data-options="disabled:true" />
                <span class="Hui">绑定的手机号码，只能重新绑定进行更换！</span>
            </td>
        </tr>
        <!--<tr >-->
            <!--<td width="120" align="right">Email：</td>-->
            <!--<td>-->
                <!--<input id="Email" name="Email" type="text" class="easyui-textbox" data-options="disabled:true" />-->
                <!--<span class="Hui">绑定的Email账号，只能重新绑定进行更换！</span>-->
            <!--</td>-->
        <!--</tr>-->
        <!--<tr >-->
            <!--<td width="120" align="right">会员昵称：</td>-->
            <!--<td>-->
                <!--<input id="NickName" name="NickName" type="text" />-->
                <!--<span class="Hui">会员的显示名称,允许修改！</span>-->
            <!--</td>-->
        <!--</tr>-->

        <!--<tr >-->
            <!--<td width="120" align="right">性别：</td>-->
            <!--<td>-->
                <!--<select id="Sex" name="Sex" style="width:108px;">-->
                    <!--<option value="0">保密</option>-->
                    <!--<option value="1">男</option>-->
                    <!--<option value="2">女</option>-->
                <!--</select>-->
                <!--<span class="Hui">会员的性别，允许修改！</span>-->
            <!--</td>-->
        <!--</tr>-->
        <!--<tr >-->
            <!--<td width="120" align="right">出生日期：</td>-->
            <!--<td>-->
                <!--<input id="BorthDate" name="BorthDate" type="text" class="easyui-datebox" style="width:108px;" />-->
                <!--<span class="Hui">会员的生日，允许修改！</span>-->
            <!--</td>-->
        <!--</tr>-->
        <tr >
            <td width="120" align="right">微信推送消息：</td>
            <td>
                <select id="IsSendwx" name="IsSendwx" >
                    <option value="1">发送</option>
                    <option value="2">不发送</option>
                </select>
                <span class="Hui">是否发送微信推送消息！</span>
            </td>
        </tr>
        <tr >
            <td width="120" align="right">会员状态：</td>
            <td>
                <select id="Status" name="Status" >
                    <option value="0">禁用</option>
                    <option value="1">正常</option>
                </select>
                <span class="Hui">被禁用的会员，无法登录！</span>
            </td>
        </tr>
        <tr>
            <td align="right">排序：</td>
            <td>
                <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:0,max:999,formatter: $.XB.JSSortInt,prompt:'0-999越小越靠前'">
            </td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>
    $(function () {
        $('#FF').form('load', '../shows?ID='+<?php echo ($ID); ?>+'&_=' + Math.random() + '');
    });
</script>
</body>
</html>