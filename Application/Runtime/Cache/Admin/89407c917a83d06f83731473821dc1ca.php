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
<form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
        <thead>
        <tr>
            <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>广告位名称：</td>
            <td>
                <input id="Name" name="Name" type="text" class="easyui-textbox" />
                <span class="Hui"> 最好2到5个字符之间</span>
            </td>
        </tr>
        <tr>
            <td  align="right">状态：</td>
            <td>
                <select id="Status" name="Status">
                    <?php if(is_array($StatusList)): foreach($StatusList as $key=>$item): ?><option value="<?php echo ($item["DictValue"]); ?>"><?php echo ($item["DictName"]); ?></option><?php endforeach; endif; ?>
                </select>

            </td>
        </tr>
        <tr>
            <td  align="right">排序：</td>
            <td>
                <input id="Sort" name="Sort" type="text" class="easyui-numberbox" />
                <span class="Hui"> 不填写默认按添加顺序排</span>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- @Html.AntiForgeryToken() -->
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>

    $(function () {
        $('#FF').form('load', '../shows?ID='+<?php echo ($ID); ?>+'&_=' + Math.random() + '');
    });


</script>
</body>
</html>