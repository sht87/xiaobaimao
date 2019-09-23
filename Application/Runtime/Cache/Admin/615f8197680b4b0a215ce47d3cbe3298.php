<?php if (!defined('THINK_PATH')) exit();?>    <!DOCTYPE html>
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

    <div id="TabelContent" class="TabelContent">
        <table id="DataList"></table>
    </div>
	<input name="__RequestVerificationToken" type="hidden" value="okW3vXllZJYOQgjpB94vuCEA66sg4iwPOYDUcxiraPCq6gAhuCikq9vXMU752lSmSaMhscdCHZjU1wnJ1P8d09XN91LsbiqTeFUBvOd-f3w1" />
</form>

<script type="text/javascript">
$(function () {
            $('#DataList').treegrid({
                rownumbers: true,
                animate: true,
                border: false,
                url: 'DataList',
                lines: true,
                idField: 'ID',
                treeField: 'Name',
                columns: [[
                    { field: 'Name', title: '分类名称', width: '300' },
                    { field: 'EName', title: '英文名称', width: '150' },
                    { field: 'IsRec', title: '是否推荐', width: 80 ,sortable:true,formatter: Common.DoFormatter},
                    { field: 'Imageurl', title: '有无封面图', width: 80 ,sortable:true,formatter: Common.HasFormatter},
                    { field: 'Status', title: '状态', width: 60 ,sortable:true,formatter: Common.StatusFormatter},
                    { field: 'Sort', title: '排序', width: 60, sortable:true,formatter: $.XB.JSSortInt }
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
                top.$.XB.success({ "message": data.message, "fn": function () {$.XB.findiframe().$.XB.reloadtreegrid(); top.$("#D1").dialog('close'); } });
            }
            else {
                $.XB.warning({ "message": data.message });
            }
        }

</script>
 <?php echo W('RolePerm/RolePermBottom');?>
<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">
<div data-options="name:'refresh'">刷新</div>
<div class="menu-sep"></div>
<div data-options="name:'add'">添加</div>
<div data-options="name:'edit'">修改</div>
<div data-options="name:'del'">删除</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>
 
</body>
</html>