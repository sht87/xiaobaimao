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
    <div class="easyui-panel" data-options="fit:true,border:false,bodyCls:'Bodybg',footer:'#ft'">
    <form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
            <thead>
                <tr>
                    <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right" width="200"><span class="Red">*</span> 年费名称：</td>
                    <td>
                        <input id="Name" name="Name" type="text" class="easyui-textbox" data-options="required:true,validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td align="right">排序：</td>
                    <td>
                        <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:0,max:999,value:999,formatter: $.XB.JSSortInt,prompt:'0-999越小越靠前'">
                    </td>
                </tr>
                <tr>
                    <td align="right">是否启用：</td>
                    <td>
                        <select id="Status" name="Status">
                            <option value="1">启用</option>
                            <option value="0">禁用</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
    </form>
</div>
    <script>
     $(function () {
        var id=$("#ID").val();

         $('#FF').form('load', '../shows?ID='+id+'&_=' + Math.random() + '');
     });
    </script>
</body>
</html>