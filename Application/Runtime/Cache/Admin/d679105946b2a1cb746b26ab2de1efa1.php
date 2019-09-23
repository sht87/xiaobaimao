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
                <td width="90" align="right">会员id：</td>
                <td width="180">
                    <input id="UserID" name="UserID" type="text" />
                </td>
                <td width="90" align="right">真实姓名：</td>
                <td width="180">
                    <input id="TrueName" name="TrueName" type="text" />
                </td>
                <td width="90" align="right">消息标题：</td>
                <td width="180">
                    <input type="text" id="Title" name="Title" />
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
            <!--<td width="90" align="right">消息接收模式：</td>-->
            <!--<td width="100">-->
                <!--<select id="Mode" name="Mode">-->
                    <!--<option value="-5">全部</option>-->
                    <!--<option value="0">内部消息</option>-->
                    <!--<option value="1">手机短信</option>-->
                    <!--<option value="2">短信&消息</option>-->
                <!--</select>-->
            <!--</td>-->
            <td width="90" align="right">消息发送类型：</td>
            <td width="100">
                <select id="Type" name="Type">
                    <option value="-5">全部</option>
                    <option value="0">系统发送</option>
                    <option value="1">通知消息</option>
                </select>
            </td>
            <td width="90" align="right">消息状态：</td>
            <td width="100">
                <select id="Status" name="Status">
                    <option value="-5">全部</option>
                    <option value="0">未发送</option>
                    <option value="1">已发送</option>
                </select>
            </td>
            </tbody>
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
            { field: 'TrueName', title: '真实姓名', width: 80 },
        ];
        var columns = [
            { field: 'Type', title: '消息发送类型', width: 100,sortable:true },
//            { field: 'Mode', title: '消息接收模式', width: 100 ,sortable:true},
            { field: 'Title', title: '内容标题', width: 100 ,sortable:true},
            { field: 'Contents', title: '发送的内容', width: 500 },
            { field: 'Status', title: '状态', width: 70, sortable: true },
            { field: 'SendTime', title: '发送时间', width: 150 ,sortable:true}
        ];

        $.XB.datagrid({ "loadsuccess": function () {
            $(this).datagrid('resize', {
                height: 260
            });
        },"frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>