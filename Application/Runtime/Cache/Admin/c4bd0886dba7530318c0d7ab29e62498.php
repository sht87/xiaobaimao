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
 <form id="FF" method="post">
<div id="tools" class="tools">
    <?php echo W('RolePerm/RolePermTop');?>
</div>
<div id="search" class="search">
    <table border="0" class="SearchTable" cellpadding="3">
        <thead>
            <tr>
                <td width="70" align="right">操作账号：</td>
                <td width="180">
                    <input id="UserName" name="UserName" type="text" />
                </td>
                <td width="70" align="right">操作类型：</td>
                <td width="180">
                    <select id="Type" name="Type">
                        <option value="-5">请选择</option>
                        <?php if(is_array($type)): foreach($type as $key=>$vo): ?><option value="<?php echo ($vo["Name"]); ?>"><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                </td>
                <td align="right">日期：</td>
                <td>
                    <input type="text" id="StartTime" name="StartTime" class="easyui-datebox" data-options="width:92,min:0,editable:false" /> -
                    <input type="text" id="EndTime" name="EndTime" class="easyui-datebox" data-options="width:92,min:0,editable:false" />
                </td>
                <td>
                    <input id="btnSearch" onclick="$.XB.search();" type="button" value="查 询">
                </td>
            </tr>
        </thead>
    </table>
</div>
<div id="tabelContent" class="tabelContent">
    <table id="DataList"></table>
</div>
</form>

<script type="text/javascript">
$(function () {
    var frozenColumns = [
        { field: 'ID', checkbox: true },
        { field: 'TrueName', title: '管理员昵称', width: 100 },
        { field: 'UserName', title: '登录账号', width: 80 }
    ];
    var columns = [
        { field: 'URL', title: '访问地址', width: 350 },
        { field: 'ControllerName', title: '控制器', width: 80 },
        { field: 'Type', title: '操作类型', width: 80 },
        { field: 'Des', title: '操作描述', width: 400 },
        { field: 'DateTime', title: '操作时间', width: 130, sortable: true},
        { field: 'IP', title: '操作IP', width: 100 },
        { field: 'IPCity', title: '操作城市', width: 100 }
    ];

    $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
    $.XB.enter();
});
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>