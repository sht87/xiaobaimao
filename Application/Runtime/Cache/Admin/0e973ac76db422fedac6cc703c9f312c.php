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
                <td align="right">文件名：</td>
                <td>
                    <input id="FileName" name="FileName" type="text" />
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
            { field: 'Name', title: '备份文件名', width: 200 }
        ];
        var columns = [
            { field: 'Size', title: '文件大小', width: 100 },
            { field: 'DateTime', title: '备份时间', width: 400 }
        ];

        $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>