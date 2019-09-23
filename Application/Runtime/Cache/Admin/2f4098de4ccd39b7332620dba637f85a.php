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
        <div id="tabelContent" class="tabelContent">
            <table id="DataList"></table>
        </div>
        <input name="__RequestVerificationToken" type="hidden" value="e8wVRmTnKfOwYiYVu_hEwHXMSFJ-tZB2p-QtTlw6BamnwAmCT9a_MwdEoUhNwA0atvSCHxoECpHgg4apwtq7NmByEyGlt1w98FcVrzbp4hw1" />
    </form>

    <script type="text/javascript">
        $(function () {
            $('#DataList').treegrid({
                rownumbers: true,
                animate: true,
                border: false,
                lines: true,
                url: 'DataList',
                idField: 'ID',
                treeField: 'Name',
                columns: [[
                    { field: 'Name', title: '菜单名称', width: '200' },
                    { field: 'ID', title: '菜单ID', width: '80', sortable: true },
                    { field: 'Sort', title: '排序', width: 70, sortable: true, formatter: $.XB.JSSortInt },
                    { field: 'Status', title: '菜单状态', width: 80, sortable: true },
                    { field: 'Url', title: '访问地址', width: 180 },
                    { field: 'OperationButton', title: '操作按钮', width: 480 }
                ]],
                onLoadSuccess: function () {
                    $(this).treegrid('resize', {
                        height: $(window).height() - $('#tools').height() - 5
                    });
                },
                onDblClickCell: function (field, row) {
                    OpenWin('edit');
                }
            });
        });
        function PageSave(data) {
            if (data.result) {
                $.XB.success({ "message": data.message, "fn": function () { $("#D1").dialog('close'); } });
            }
            else {
                $.XB.warning({ "message": data.message });
            }
        }
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>