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
                <td width="70" align="right">标题：</td>
                <td width="180">
                    <input id="Title" name="Title" type="text" />
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
        <input name="__RequestVerificationToken" type="hidden" value="O4xBctaQJIHAc4XDhzLhTfnYRNIidYs6GCIyh_hGwH30K_ZnOp2Pxm2itzlsyZJS70BJmedeg91fORNxM5Qj-b_rJ7SR59NvMLJ5mpJx5hA1" />
    </form>

    <script type="text/javascript">

        $(function () {
             var frozenColumns = [
            { field: 'ID', checkbox: true },
            { field: 'Title', title: '标题', width: 150 }
            ];
            var columns = [
                { field: 'ViewCounk', title: '浏览量', width: 80 },
                { field: 'Sort', title: '排序', width: 80 , sortable: true },
                { field: 'OperatorName', title: '操作人', width: 70 },

            ];
            $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
            $.XB.enter();
        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>