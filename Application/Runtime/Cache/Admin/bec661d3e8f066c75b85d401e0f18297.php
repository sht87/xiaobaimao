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
                <td width="70" align="right">角色名称：</td>
                <td width="180">
                    <input id="Name" name="Name" type="text" />
                </td>
                <td width="70" align="right">角色状态：</td>
                <td width="100">
                    <select id="Status" name="Status">
                        <option value="-5">全部</option>
                        <option value="1">正常</option>
                        <option value="0">隐藏</option>
                    </select>
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
    <input name="__RequestVerificationToken" type="hidden" value="kITjSi9DAnmmGPXAbT-XpFRHe7Qfq14LkKHIyK2qt59u8gTC1RsZkCQTbFHiFr70tjkc9bqzWAZTPg9g8OJarcykm_qMgOp1FofukWTJ__k1" />
</form>

<script type="text/javascript">
    $(function () {
        var frozenColumns = [
            { field: 'ID', checkbox: true },
            { field: 'Name', title: '角色名称', width: 150 }
        ];
        var columns = [
            { field: 'MenuID', title: '默认展开菜单', width: 100 },
            { field: 'MenuNum', title: '菜单权限数', width: 100 },
            { field: 'Status', title: '状态', width: 70, sortable: true },
            { field: 'OperatorID', title: '操作者', width: 100 },
            { field: 'UpdateTime', title: '更新时间', width: 150 },
        ];

        $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>

<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>