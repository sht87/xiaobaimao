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
                    <td width="70" align="right">是否推荐：</td>
                    <td width="180">
                        <select id="IsTui" name="IsTui" class="easyui-combobox" data-options="panelHeight:90,editable:false">
                            <option value="-5">全部</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </td>
                    <td>
                        <input id="btnSearch" onclick="$.XB.search();" type="button" value="查 询">
                    </td>
                    <td>
                        <input id="MoreSearch" onclick="$.XB.moresearch()" type="button" value="更多条件">
                    </td>
                </tr>
            </thead>
            <tbody id="stbody">
                <tr>
                    <td width="70" align="right">状态：</td>
                    <td width="180">
                        <select id="IsPublish" name="IsPublish" class="easyui-combobox" data-options="panelHeight:90,editable:false">
                            <option value="-5">全部</option>
                            <option value="0">未发布</option>
                            <option value="1">已发布</option>
                        </select>
                    </td>
                    <td width="70" align="right">所属分类：</td>
                    <td width="180">
                        <select id="CategoriesID" name="CategoriesID" class="easyui-combotree" data-options="editable:false,url:'<?php echo U('System/Contentmanagement/add');?>'"></select>
                    </td>
                </tr>
                <tr>
                    <td align="right">添加时间：</td>
                    <td colspan="3">
                        <input id="AddDateTime1" name="AddDateTime1" type="text" class="easyui-datebox" data-options="editable:false" />
                        -
                        <input id="AddDateTime2" name="AddDateTime2" type="text" class="easyui-datebox" data-options="editable:false" />
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div id="tabelContent" class="tabelContent">
        <table id="DataList"></table>
    </div>
</form>

<script type="text/javascript">
        var id = "<?php echo I('get.CategoriesID');?>";
        $(function () {
            var frozenColumns = [
                { field: 'ID', checkbox: true },
                { field: 'Title', title: '标题', width: 350 }
            ];
            var columns = [
                { field: 'CategoriesID', title: '类别', width: 100 },
                { field: 'Soruce', title: '文章来源', width: 100 },
                { field: 'Author', title: '作者', width: 80 },
                { field: 'ViewCounk', title: '浏览量', width: 50 },
                { field: 'Sort', title: '排序', width: 60, formatter: $.XB.JSSortInt },
                { field: 'IsPublish', title: '是否发布', width: 70,formatter: Common.DoFormatter},
                { field: 'IsTui', title: '是否推荐', width: 70,formatter: Common.DoFormatter},
                { field: 'AddUserName', title: '添加人', width: 70 },
                { field: 'UpdateTime', title: '添加时间', width: 130 }

            ];
            $.XB.enter();
                    $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns, "url": "/admin.php/System/Contentmanagement/DataList?CategoriesID="+id
 });

        });
</script>
    <?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>