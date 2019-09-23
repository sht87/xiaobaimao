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
<style>
    .layout-panel-west {
        border-right: 1px solid #ccc;
    }
</style>
<body class="easyui-layout">
    <div data-options="region:'west',collapsible:false,border:false,width:170,title:'栏目分类'">
        <ul id="LeftTree"></ul>
    </div>
    <div data-options="region:'center',border:false" style="overflow:hidden;">        
        <iframe name="iframe" id="iframe" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#iframe").attr("src", "list/");
            $('#LeftTree').tree({
                url: 'DataTree',
                animate: true,
                lines: true,
                onClick: function (node) {
                    if (node.attributes.category == 'C') {

                        $("#iframe").attr("src", "<?php echo U('System/Contentmanagement/list');?>?CategoriesID=" + node.id + "");

                    }else if (node.attributes.category == 'N') {
                        $("#iframe").attr("src", "edit/?CategoriesID=" + node.id + "");
                    }
                }
            });
        })
    </script>
</body>
</html>