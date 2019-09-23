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
                <td colspan="2">说明：带<span class="Red">*</span>必填；密码错误次数：超过

次数系统会禁止用户再次登录</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="right"><span class="Red">*</span> 用户名：</td>
                <td>
                    <input id="UserName" name="UserName" type="text" class="easyui-textbox" 

data-options="required:true,validType:['length[1,100]']" />
                </td>
            </tr>
            <tr>
                <td align="right"><span class="Red">*</span> 密码：</td>
                <td>
                    <input id="UserPsd" name="Password" type="password" class="easyui-textbox"

data-options="required:true,validType:['length[1,100]']" />
                </td>
            </tr>
            <tr>
                <td align="right"><span class="Red">*</span> 真实姓名：</td>
                <td>
                    <input id="TrueName" name="TrueName" type="text" class="easyui-textbox" 

data-options="required:true,validType:['length[1,100]']" />
                </td>
            </tr>
            <tr>
                <td align="right">角色：</td>
                <td>
                    <select id="RoleID" name="RoleID" class="easyui-combobox" data-options="required:true,panelHeight:150,editable:false">
                        <option value="-5">请选择</option>
                        <?php if(is_array($roleList)): $i = 0; $__LIST__ = $roleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><option value="<?php echo ($row['ID']); ?>"><?php echo ($row['Name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">密码错误次数：</td>
                <td>
                    <input id="PsdErrorCount" name="PsdErrorCount"  type="text" class="easyui-numberbox" data-options="min:0,max:100,prompt:'设为0值，用户可继续尝试登录'">
                </td>
            </tr>
            <tr>
                <td align="right">状态：</td>
                <td>
                    <select id="Status" name="Status">
                        <option value="1">正常</option>
                        <option value="0">隐藏</option>
                    </select>
                </td>
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
</body>
</html>