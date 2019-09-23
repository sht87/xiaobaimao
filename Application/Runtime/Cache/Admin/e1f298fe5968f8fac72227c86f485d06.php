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
<style type="text/css">
    .btn{
        padding: 5px 10px ;
        border-radius: 4px;
        color: #eee;
    }
    .add{
        background-color: #0092DC;
    }
    .rem{
        background-color: #ffa8a8;
    }
</style>
<body class="Bodybg">
<form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
    <table width="100%" border="0" height="100px">
        <tbody>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>注册人数：</td>
            <td>
                <input id="registerNum" name="registerNum" type="text" class="easyui-numberbox" data-options="required:true"/>
            </td>
        </tr>
        
        </tbody>
    </table>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>
</script>
</body>
</html>