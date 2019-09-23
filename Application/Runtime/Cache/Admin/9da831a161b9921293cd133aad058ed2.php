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
                    <td colspan="2">说明：带<span class="Red">*</span>必填；默认展开菜单指角色登录系统后，左边导航默认展开的菜单 不设置则全部折叠</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="120" align="right"><span class="Red">*</span> 角色名称：</td>
                    <td>
                        <input id="Name" name="Name" type="text" class="required:true,easyui-validatebox" />
                    </td>
                </tr>
                <tr>
                    <td align="right">默认展开菜单：</td>
                    <td>
                        <select id="MenuID" name="MenuID" class="easyui-combobox" data-options="required:true,panelHeight:150,editable:false">
                            <option value="-5" >请选择</option>
                            <?php if(is_array($Menus)): foreach($Menus as $key=>$item): ?><option value="<?php echo ($item["ID"]); ?>"><?php echo ($item["Name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">状态：</td>
                    <td>
                        <select id="Status" name="Status">
                            <option value="1">正常</option>
                            <option value="0">隐藏</option>
                        </select>
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