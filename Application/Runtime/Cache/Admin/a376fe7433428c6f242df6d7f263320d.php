<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
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
</head><body class="Bodybg">
<form id="FF" method="post">
    <div id="tools" class="tools">
        <?php echo W('RolePerm/RolePermTop');?>
    </div>
    <div id="search" class="search">
        <table border="0" class="SearchTable" cellpadding="3">
            <thead>
            <tr>
                <td width="70" align="right">会员id：</td>
                <td width="180">
                    <input id="UserID" Name="UserID" type="text" />
                </td>
                <td width="70" align="right">发送状态：</td>
                <td width="180">
                    <select id="Status" name="Status">
                        <option value="-5">全部</option>
                        <option value="0">失败</option>
                        <option value="1">成功</option>
                    </select>
                </td>
                <td>
                    <input id="btnSearch" onClick="$.XB.search();" type="button" value="查 询">
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
            { field: 'UserID', title: '会员id', width: 60 },
        ];
        var columns = [
            { field: 'Mobile', title: '会员手机号', width: 100 },
            { field: 'TrueName', title: '会员姓名', width: 120 },

            { field: 'Status', title: '发送状态', width: 90 ,sortable: true},
            { field: 'Errmsg', title: '错误信息', width: 150 },
            { field: 'OperatorID', title: '操作者', width: 120 },
            { field: 'UpdateTime', title: '操作时间', width: 130 ,sortable: true},
        ];

        $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>