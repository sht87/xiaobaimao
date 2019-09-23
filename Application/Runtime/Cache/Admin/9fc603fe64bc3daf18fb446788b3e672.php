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
    <style type="text/css">
        .ke-container .ke-container-simple{width: 50%}
    </style>
<div class="easyui-panel" data-options="fit:true,border:false,bodyCls:'Bodybg',footer:'#ft'">
    <form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
        <thead>
        <tr>
            <td colspan="2">说明：带<span class="Red">*</span>必填；请至少确保每个客户端至少有一个是启用的</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>客户端：</td>
            <td>
                <select id="Client" name="Client">
                    <?php if(is_array($Client)): foreach($Client as $k=>$vo): if($vo): ?><option value="<?php echo ($k); ?>"><?php echo ($vo); ?></option><?php endif; endforeach; endif; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td  align="right"><span class="Red">*</span>包名：</td>
            <td>
                <input id="Package" name="Package" type="text" class="easyui-textbox" style="width: 220px;" class="easyui-textbox"  data-options="required:true"/>
                <span class="Hui"></span>
            </td>
        </tr>
        <tr>
            <td  align="right"><span class="Red">*</span>版本号：</td>
            <td>
                <input id="Ver1" name="Ver1" type="text" class="easyui-numberspinner" data-options="required:true,width:50,increment:1,min:1,max:999,precision:0"/>
                <input id="Ver2" name="Ver2" type="text" class="easyui-numberspinner" data-options="required:true,width:50,increment:1,min:0,max:999,precision:0"/>
                <input id="Ver3" name="Ver3" type="text" class="easyui-numberspinner" data-options="required:true,width:50,increment:10,min:0,max:999,precision:0"/>
            </td>
        </tr>
        <tr>
            <td  align="right"><span class="Red">*</span>地址：</td>
            <td>
                <input id="Url" name="Url" type="text" class="easyui-textbox" style="width: 220px;" class="easyui-textbox"  data-options="required:true"/>
            </td>
        </tr>
        <tr>
            <td  align="right"><span class="Red">*</span>包大小：</td>
            <td>
                <input id="Size" name="Size" type="text" class="easyui-numberspinner" data-options="required:true,width:150,increment:1,precision:0"/>
                <span class="Hui">M</span>
            </td>
        </tr>
        <tr>
            <td  align="right">是否强制更新：</td>
            <td>
                <select id="isForced" name="isForced" style="width: 150px;">
                    <option value="1">强制更新</option>
                    <option value="2">非强制更新</option>
                </select>
                <span class="Hui"></span>
            </td>
        </tr>
        <tr>
            <td  align="right">默认：</td>
            <td>
                <select id="IsDefault" name="IsDefault" style="width: 150px;">
                    <option value="1">是</option>
                    <option value="2">否</option>
                </select>
                <span class="Hui">每个客户端始终只有一个默认</span>
            </td>
        </tr>

        <tr>
            <td  align="right">状态：</td>
            <td>
                <select id="Status" name="Status" style="width: 150px;">
                    <option value="1">启用</option>
                    <option value="2">禁用</option>
                </select>
            </td>
        </tr>

        <tr>
            <td align="right"><span class="Red">*</span>更新内容：</td>
            <td>
                <textarea  id="Updates" name="Updates"></textarea>
            </td>
        </tr>

        </tbody>
    </table>
    <!-- @Html.AntiForgeryToken() -->
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
</div>
<?php echo load_editor_js('ueditor');?>
<script>
    $(function () {
        $('#FF').form('load', '../shows?ID=<?php echo ($ID); ?>&_=' + Math.random() + '').form({
            onLoadSuccess: function (data) {
                <?php echo editor('ueditor',1,'Updates',450,350);?>
            }
        });
    });
</script>
</body>
</html>