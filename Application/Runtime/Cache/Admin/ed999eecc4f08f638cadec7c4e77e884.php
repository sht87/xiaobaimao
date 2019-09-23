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
    .panel-title {
        height: 24px;
        line-height: 24px;
    }
</style>
<body class="easyui-layout" style="margin-left:1px;margin-top:1px;">
    <div data-options="region:'west',collapsible:false,border:true,width:180,title:'栏目分类'">
        <ul id="LeftTree"></ul>
    </div>
    <div data-options="region:'center',border:false" style="overflow:hidden;">
        <iframe name="iframe" id="iframe" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>d
    </div>
    <script type="text/javascript">
        $(function () {
            $("#iframe").attr("src", "/admin.php/System/Adcontent/lists/");
            $('#LeftTree').tree({
                url: 'DataTree',
                animate: true,
                lines: true,
                onClick: function (node) {
                    console.log(node);
                    if (true) {

                        $("#iframe").attr("src", "<?php echo U('System/Adcontent/lists');?>?AdvertisingID=" + node.ID + "");

                    }else if (node.attributes.category == 'N') {
                        $("#iframe").attr("src", "/admin.php/System/Adcontent/single/?AdvertisingID=" + node.ID + "");
                    }
                }
            });
        })

        function placeSubNode($nodeId, $nodeText)
        {
            $html = '<li><div id="_easyui_tree_1" data-id="'+$nodeId+'" class="tree-node tree-root-first"><span class="tree-indent tree-join"></span><span class="tree-icon tree-file "></span><span class="tree-title">'+$nodeText+'</span></div></li>';
        }
    </script>
</body>
</html>