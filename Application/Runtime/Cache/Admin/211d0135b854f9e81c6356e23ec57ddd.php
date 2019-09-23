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
                        <td width="70" align="right">用户名：</td>
                        <td width="180">
                            <input id="UserName" Name="UserName" type="text" />
                        </td>
                        <td width="70" align="right">真实姓名：</td>
                        <td width="180">
                            <input id="TrueName" name="TrueName" type="text" />
                        </td>
                        <td width="70" align="right">角色：</td>
                        <td width="100">
                            <select id="RoleID" name="RoleID">
                                <option value="-5">请选择</option>
                                <?php if(is_array($roleList)): $i = 0; $__LIST__ = $roleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><option value="<?php echo ($row['ID']); ?>"><?php echo ($row['Name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

                            </select>
                        </td>
                        <td width="70" align="right">用户状态：</td>
                        <td width="100">
                            <select id="Status" name="Status">
                                <option value="-5" selected="selected">全部</option>
                                <option value="1">正常</option>
                                <option value="0">隐藏</option>
                            </select>
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
        <input name="__RequestVerificationToken" type="hidden" value="O4xBctaQJIHAc4XDhzLhTfnYRNIidYs6GCIyh_hGwH30K_ZnOp2Pxm2itzlsyZJS70BJmedeg91fORNxM5Qj-b_rJ7SR59NvMLJ5mpJx5hA1" />
    </form>

    <script type="text/javascript">

        $(function () {

            var frozenColumns = [
                 { field: 'id', checkbox: true },
                 { field: 'UserName', title: '用户名', width: 100 }
            ];
            var columns = [
                    { field: 'TrueName', title: '真实姓名', width: 100 },
                    { field: 'RoleID', title: '所属角色', width: 100, sortable: true },
                    { field: 'Status', title: '状态', width: 70 ,formatter: Common.StatusFormatter},
                    { field: 'LoginCount', title: '成功登录次数', width: 100, sortable: true, formatter: $.XB.JSSortInt },
                    { field: 'ErrorCount', title: '密码错误次数', width: 100, sortable: true, formatter: $.XB.JSSortInt },
                    { field: 'LoginTime', title: '最后登录时间', width: 150, sortable: true },
                    { field: 'LoginIP', title: '最后登录IP', width: 100, sortable: true },
                    { field: 'BindIP', title: '绑定IP', width: 100, sortable: true },
                    { field: 'LoginMAC', title: '最后登录MAC', width: 150, sortable: true },
                    { field: 'BindMAC', title: '绑定MAC', width: 150, sortable: true },
                    { field: 'IpCity', title: '最后登录城市', width: 300, sortable: true }
            ];
            $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
            $.XB.enter();

        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>