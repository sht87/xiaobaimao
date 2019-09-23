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
                <td width="70" align="left">地区名称：</td>
                <td width="180">
                    <input id="Name" Name="Name" type="text" />
                </td>
                <td width="70" align="left">行政编号：</td>
                <td width="180">
                    <input id="Code" Name="Code" type="text" />
                </td>
                <td width="70" align="left">状态：</td>
                <td width="100">
                    <select id="Status" name="Status">
                        <option value="-5" selected="selected">全部</option>
                        <option value="1">启用</option>
                        <option value="0">禁用</option>
                    </select>
                </td>
                <td>
                    <input id="btnSearch" onclick="$.XB.searchtree()" type="button" value="查 询">
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
        $('#DataList').treegrid({
            rownumbers: false,
            animate: true,
            border: false,
            lines: true,
            url: 'DataList',
            idField: 'ID',
            treeField: 'Name',
            pagination:true,
            pageSize: 20,
            pageList: [10, 20, 50],
            frozenColumns: [[
                { field: 'Name', title: '区域名称',width: 200 ,align:'left',sortable: true},
                { field: 'Code', title: '行政编号' },
            ]],
            columns: [[
                { field: 'ShortEn', title: '英文缩写', width: 120 },
                { field: 'En', title: '英文名称', width: 200 },
                { field: 'UpdateTime', title: '更新时间', width: 150},
                { field: 'OperaterID', title: '操作者', width: 150},
                { field: 'Sort', title: '排序', width: 70, sortable: true, formatter: $.XB.JSSortInt },
                { field: 'Status', title: '状态', width: 80, sortable: true }
            ]],
            onClickRow:function(row){
                if(row.state=='closed') {
                    $('#DataList').treegrid('reload', row.ID);
                }else{
                    $('#DataList').treegrid('toggle', row.ID);
                }
            },
            onLoadSuccess: function () {
                $(this).treegrid('resize', {
                    height: $(window).height() - $('#tools').height() - $('#search').height() - 15
                });
            },
            onDblClickCell: function (field, row) {
                OpenWin('edit');
            }
        });
    });

    function PageSave(data) {
        if (data.result) {
            $.XB.success({ "message": data.message, "fn": function () { $.XB.reloadtreegrid(); $("#D1").dialog('close'); } });
        }
        else {
            $.XB.warning({ "message": data.message });
        }
    }

    function queryChild(row)
    {
        $(this).treegrid("options").url = "DataList?id="+row.id;
    }

</script>

<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>