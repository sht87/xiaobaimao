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

<form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
        <thead>
        <tr>
            <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>平台名称：</td>
            <td>
                <input id="Name" name="Name" type="text" class="easyui-textbox" />
                <span class="Hui"> 填写集成的平台名称 </span>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>英文名称：</td>
            <td>
                <input id="EName" name="EName" type="text" class="easyui-textbox" />
                <span class="Hui"> 用于程序调用使用，且不可随意更改 </span>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">平台简介：</td>
            <td>
                <input id="Intro" name="Intro" type="text" class="easyui-textbox" style="width:300px;" />
                <span class="Hui"> 简单描述下该平台的功用</span>
            </td>
        </tr>
        <tr>
            <td  align="right">状态：</td>
            <td>
                <select id="Status" name="Status" style="width:80px">
                    <?php if(is_array($StatusList)): foreach($StatusList as $key=>$item): ?><option value="<?php echo ($item["DictValue"]); ?>"><?php echo ($item["DictName"]); ?></option><?php endforeach; endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td  align="right">是否默认：</td>
            <td>
                <select id="IsDefault" name="IsDefault" style="width:80px">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
                <span class="Hui"> 同时只有一个短信平台可以被设置为默认</span>
            </td>
        </tr>
        <tr>
            <td style="height:165px;"></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>

    $(function () {
        $('#FF').form('load', '../shows?ID='+<?php echo ($ID); ?>+'&_=' + Math.random() + '');
    });


</script>

</html>