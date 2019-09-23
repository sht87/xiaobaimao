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
    
<form id="form1">
    <div class="tools" id="tools">
        <?php echo W('RolePerm/RolePermTop');?>
    </div>
    <div class="search" id="search">
        <table border="0" id="SearchTable" class="SearchTable" cellpadding="3">
            <thead>
                <tr>
                    <td width="70" align="right">名称查询：</td>
                    <td width="180">
                        <input id="Name" name="Name" type="text" />
                    </td>
                    <td width="70" align="right">是否启用：</td>
                    <td width="180">
                        <select id="Status" name="Status">
                            <option value="-5">全部</option>
                            <option value="1">启用</option>
                            <option value="0">禁用</option>
                        </select>
                    </td>
                    <td>
                        <input id="btnSearch" onclick="$.XB.search();" type="button" value="查 看">
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <div id="TabelContent" class="TabelContent">
        <table id="DataList"></table>
    </div>
</form>

<script type="text/javascript">
    var frozenColumns = [
        { field: 'ID', checkbox: true },
        { field: 'Name', title: '名称', width: 150  },
        { field: 'UserName', title: '用户名', width: 120  },
    ];
    var columns = [
        { field: 'Remark', title: '备注', width: 120  },
        { field: 'Links', title: '推广链接', width: 450},
        { field: 'Status', title: '状态', width: 80, sortable:true,formatter:Common.StatusFormatter },
        { field: 'Sort', title: '排序', width: 80, sortable:true },
        { field: 'OperatorID', title: '添加人', width: 100,sortable:true },
        { field: 'UpdateTime', title: '添加时间', width: 150 ,sortable:true},
		{ field: 'openFree', title: '是否开启扣量', width: 120,formatter:Common.StatusFormatter},
		{ field: 'freekt', title: '扣量比例', width: 120}

    ];
    $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns});
    $.XB.enter();
</script>
    <?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>