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
                    <td width="70" align="right">状态：</td>
                    <td width="180">
                        <select id="Status" name="Status" class="easyui-combobox" data-options="panelHeight:90,editable:false">
                            <option value="-5">全部</option>
                            <option value="0">禁用</option>
                            <option value="1">启用</option>
                        </select>
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
        var id = "<?php echo I('get.AdvertisingID');?>";
        $(function () {
            var frozenColumns = [
            { field: 'ID', checkbox: true },
            { field: 'Name', title: '广告名称', width: 350 }
            ];
            var columns = [
                { field: 'CateID', title: '跳转地址', width: 300 },
                { field: 'AdvertisingID', title: '所属广告位', width: 200 ,sortable: true},
                { field: 'Sort', title: '排序', width: 80, sortable: true, formatter: $.XB.JSSortInt },
                { field: 'Status', title: '状态', width: 90, sortable: true },
                { field: 'OperatorID', title: '操作人', width: 100 },
                { field: 'UpdateTime', title: '添加时间', width: 130, sortable: true }
            ];
            $.XB.enter();
            $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns, "url": "/admin.php/System/Adcontent/DataList?AdvertisingID="+id });

        });
</script>
    <?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>