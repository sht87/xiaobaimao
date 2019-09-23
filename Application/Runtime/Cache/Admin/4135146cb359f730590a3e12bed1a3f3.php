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
                <td align="right">广告位名称：</td>
                <td>
                    <input id="Name" name="Name" type="text" />
                </td>
                <td align="right">状态：</td>
                <td>
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
    <!-- @Html.AntiForgeryToken() -->
</form>

<script type="text/javascript">
    $(function () {
        var frozenColumns = [
            { field: 'ID', checkbox: true },
            { field: 'Name', title: '广告位', width: 150 }
        ];
        var columns = [
            { field: 'Sort', title: '排序', width: 50, sortable: true, formatter: $.XB.JSSortInt },
            { field: 'Status', title: '状态', width: 70, sortable: true },
            { field: 'OperatorID', title: '操作人', width: 70 },
            { field: 'UpdateTime', title: '添加时间', width: 130, sortable: true }
        ];

        $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>