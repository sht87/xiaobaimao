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
            <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>原始密码：</td>
            <td>
                <input id="Password" name="Password" type="text" class="easyui-textbox" />
                <span class="Hui"> 当前登录的密码</span>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>新的密码：</td>
            <td>
                <input id="NewPWD" name="NewPWD" type="text" class="easyui-textbox" />
                <span class="Hui"> 请设置一个新的密码，长度不低于6位</span>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>确认密码：</td>
            <td>
                <input id="ConfirmPWD" name="ConfirmPWD" type="text" class="easyui-textbox" />
                <span class="Hui"> 重复输入新密码,和设置的新密码保持一致</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="height:230px;"></td>
        </tr>
        </tbody>
    </table>
</form>
</body>
</html>