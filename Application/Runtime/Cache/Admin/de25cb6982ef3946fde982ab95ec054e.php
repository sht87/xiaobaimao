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
                <td width="90" align="right">发送对象账号：</td>
                <td width="180">
                    <input id="ObjectID" name="ObjectID" type="text" />
                </td>
                <td width="90" align="right">发送的内容：</td>
                <td width="180">
                    <input type="text" id="SendMess" name="SendMess" style="width:300px" />
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
            <td width="90" align="right">消息发送类型：</td>
            <td width="100">
                <select id="Type" name="Type">
                    <option value="-5">选择发送类型</option>
                    <option value="0">系统发送</option>
                    <option value="1">手工发送</option>
                </select>
            </td>
            <td width="90" align="right">消息接收模式：</td>
            <td width="100">
                <select id="Mode" name="Mode">
                    <option value="-5">选择接收模式</option>
                    <option value="0">手机短信</option>
                    <option value="1">内部消息</option>
                    <option value="2">短信&消息</option>
                </select>
            </td>
            </tbody>
        </table>
    </div>
    <div id="tabelContent" class="tabelContent">
        <table id="DataList"></table>
    </div>
    <input name="__RequestVerificationToken" type="hidden" value="kITjSi9DAnmmGPXAbT-XpFRHe7Qfq14LkKHIyK2qt59u8gTC1RsZkCQTbFHiFr70tjkc9bqzWAZTPg9g8OJarcykm_qMgOp1FofukWTJ__k1" />
</form>

<script type="text/javascript">
    $(function () {
        var frozenColumns = [
            { field: 'ID', checkbox: true },
            { field: 'ObjectID', title: '发送的对象', width: 150 }
        ];
        var columns = [
            { field: 'Type', title: '消息发送类型', width: 100 },
            { field: 'Mode', title: '消息接收模式', width: 100 },
            { field: 'SendMess', title: '发送的内容', width: 500 },
            { field: 'Status', title: '状态', width: 70, sortable: true },
            { field: 'SendTime', title: '发送时间', width: 150 }
        ];

        $.XB.datagrid({ "frozenColumns": frozenColumns, "columns": columns });
        $.XB.enter();
    });
</script>
<?php echo W('RolePerm/RolePermBottom');?>
</body>
</html>