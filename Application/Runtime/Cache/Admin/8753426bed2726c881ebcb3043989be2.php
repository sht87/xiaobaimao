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
                        <td width="70" align="right">渠道名称：</td>
                        <td width="180">
							<select name="Title" id="Title">
								<option value="全部">全部</option>
								<?php if(is_array($tgName)): $i = 0; $__LIST__ = $tgName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["Name"]); ?>"><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
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
					{ field: 'ID', checkbox: true},
                    { field: 'channelName', title: '渠道名称', width: 80},
                    { field: 'PV', title: '浏览数(PV)', width: 80,sortable:true},
                    { field: 'UV', title: '申请数(UV)', width: 80,sortable:true},
                    { field: 'PVUV', title: '申请转化率', width: 80},
                    { field: 'registerNum', title: '注册数', width: 80},
                    { field: 'registerNumPV', title: '浏览转化率', width:80},
					{ field: 'registerNumUV', title: '用户转化率', width:80},
					{ field: 'aliveNum', title: '激活数', width:80},
					{ field: 'aliveNumregisterNum', title: '激活率', width:80},
                    { field: 'CPA', title: 'CPA', width: 80,sortable:true},
                    { field: 'priceregisterNum', title: '结算费用', width: 80},
                    { field: 'newNum', title: '申请数新', width: 80 },
					{ field: 'applyNum', title: '申请数总', width: 80 },
					{ field: 'registernewNum', title: '申请转化率', width: 80 },
					{ field: 'registerapplyNum', title: '总申请转化率', width: 80 },
					{ field: 'prUV', title: 'UV新单价', width: 80 },
					{ field: 'prAll', title: 'UV总单价', width: 80 },
                    { field: 'createDate', title: '日期', width: 80},
                ];
            $.XB.datagrid({'columns':columns,'singleSelect':true,'freezeRow':0});
            $.XB.enter();
        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>