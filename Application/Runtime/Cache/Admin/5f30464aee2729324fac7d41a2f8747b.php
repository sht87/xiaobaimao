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
    
<form id="FF">
    <div class="tools" id="tools">
        <?php echo W('RolePerm/RolePermTop');?>
    </div>
    <div class="search" id="search">
        <table border="0" id="SearchTable" class="SearchTable" cellpadding="3">
            <thead>
                <tr>
                    <td width="70" align="right">日期：</td>
                    <td width="200">
                        <input id="StartTime" name="StartTime" type="text" class="easyui-datebox" data-options="width:93,editable:false" />
                        -
                        <input id="EndTime" name="EndTime" type="text" class="easyui-datebox" data-options="width:93,editable:false" />
                    </td>
                    <td width="70" align="right">渠道：</td>
                    <td width="180">
                        <select name="TgadminID" id="TgadminID">
                            <option value="-5">全部</option>
                            <?php if(is_array($adminlist)): $i = 0; $__LIST__ = $adminlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ID"]); ?>"><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
        { field: 'createDate', title: '日期', width: 150  },
    ];
    var columns = [
        { field: 'num', title: '注册人数', width: 120  },
		{ field: 'android', title: 'android', width: 120  },
		{ field: 'ios', title: 'ios', width: 120  },
		{ field: 'UV', title: 'UV', width: 120  },

    ];
	if('<?php echo ($RoleID); ?>'==9){
		columns = [
			{ field: 'num', title: '注册人数', width: 120  },
			{ field: 'UV', title: 'UV', width: 120  },
		];
	}
    $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns});
    $.XB.enter();
</script>
    <?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>