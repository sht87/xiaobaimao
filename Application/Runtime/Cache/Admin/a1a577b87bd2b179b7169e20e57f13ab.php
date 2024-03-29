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
    body{background: #fff}
</style>

<body>
<div class="easyui-panel" data-options="fit:true,border:false,footer:'#ft'">
    <form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
        <table width="100%" border="0" cellpadding="3" cellspacing="0" >
            <thead>
            <tr>
                <td colspan="4"></td>
            </tr>
            </thead>
        </table>
        <fieldset style=" border: 1px solid #ccc;margin:5px;">
            <legend>基本信息</legend>
            <table border="0" align="center" cellpadding="8" cellspacing="3" class="fine_table" width="100%">
                <tbody>
                <tr>
                    <td width="50%">当前账号：<?php echo ($LoginInfo["Admin"]); ?></td>
                </tr>
                <tr>
                    <td width="50%">所属角色：<?php echo ($LoginInfo["RoleName"]); ?></td>
                </tr>

                </tbody>
            </table>
        </fieldset>

        <fieldset style=" border: 1px solid #ccc;margin:5px;">
            <legend>配置信息</legend>
            <table border="0" align="center" cellpadding="8" cellspacing="3" class="fine_table" width="100%">
                <tbody>
                <tr>
                    <td width="50%">服务器信息：<?php echo $_SERVER['SERVER_NAME']?>(IP:<?php echo get_client_ip();?>)</td>
                    <td width="50%">Web 服务器：<?php echo $_SERVER['SERVER_SOFTWARE']?></td>
                </tr>
                <tr>
                    <td>当前的数据库版本：<?php echo ($VERSION["0"]["VERSION()"]); ?></td>
                    <td>当前PHP版本：<?php echo PHP_VERSION?></td>
                </tr>
                <tr>
                    <td>服务器系统：<?php echo @PHP_OS.'('.@$_SERVER['SERVER_ADDR'].')'?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>安全模式 (安全模式GID)：<?php echo (boolean) ini_get('safe_mode') ? '是' : '否';?> (<?php echo (boolean) ini_get('safe_mode_gid') ? '是' : '否';?>)</td>
                    <td>上传文件大小：<?php echo @ini_get('upload_max_filesize');?></td>
                </tr>
                <tr>
                    <td>服务器时间：<?php echo date('Y-m-d H:i:s')?></td>
                    <td>程序版本：Version 1.2.2</td>
                </tr>
                </tbody>
            </table>
        </fieldset>


</div>

</body>
</html>