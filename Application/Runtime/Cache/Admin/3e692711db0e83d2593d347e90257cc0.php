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
                        <td width="70" align="right">产品名称：</td>
                        <td width="180">
                            <input id="Title" name="Title" type="text" />
                        </td>
						<td width="70" align="right">日期：</td>
						<td width="200">
							<input id="StartTime" name="StartTime" type="text" class="easyui-datebox" data-options="width:93,editable:false" />
							-
							<input id="EndTime" name="EndTime" type="text" class="easyui-datebox" data-options="width:93,editable:false" />
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
    </form>
    <script type="text/javascript">

        $(function () {
                var columns= [
					{ field: 'ID', checkbox: true },
                    { field: 'productName', title: '产品名称', width: 120},
                    { field: 'PV', title: '浏览数(PV)', width: 120,sortable:true},
                    { field: 'UV', title: '申请数(UV)', width: 120,sortable:true},
                    { field: 'PVUV', title: '申请转化率(UV/PV)', width: 120},
                    { field: 'registerNum', title: '注册数(可录入)', width: 120},
                    { field: 'registerNumUV', title: '注册转化率(注册数/UV)', width:220},
                    { field: 'CPA', title: 'CPA', width: 120,sortable:true},
                    { field: 'registerNumCPA', title: '结算费用', width: 120},
                    { field: 'registerNumCPAUV', title: 'UV成本价', width: 120 },
                    { field: 'createDate', title: '日期', width: 120,sortable:true},
                ];
            $.XB.datagrid({'columns':columns,'singleSelect':true,'freezeRow':0});
            $.XB.enter();
        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>