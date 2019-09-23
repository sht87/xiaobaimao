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
					{ field: 'createDate', title: '日期', width: 120},
                    { field: 'banner', title: '轮播图', width: 120},
                    { field: 'message', title: '消息', width: 120},
                    { field: 'cate1', title: '<?php echo ($cate0); ?>', width: 120},
                    { field: 'cate2', title: '<?php echo ($cate1); ?>', width: 120},
                    { field: 'cate3', title: '<?php echo ($cate2); ?>', width: 120},
                    { field: 'cate4', title: '<?php echo ($cate3); ?>', width:120},
                ];
            $.XB.datagrid({'columns':columns,'singleSelect':true,'freezeRow':0});
            $.XB.enter();
        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>