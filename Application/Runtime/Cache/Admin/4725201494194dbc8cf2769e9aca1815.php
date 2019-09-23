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

                <tr >
                    <td width="120" align="right"><span class="Red">*</span> 发送的对象：</td>
                    <td>
                        <select  name="Obj" id="Obj" class="easyui-combobox" data-options="required:true,editable:false"></select>
                    </td>
                </tr>
                <tr id="objec"></tr>
                <tr>
                    <td width="120" align="right"><span class="Red">*</span> 消息类型：</td>
                    <td width="100">
                        <select id="Type" name="Type" class="easyui-combobox" data-options="required:true,editable:false,onBeforeLoad:function(){var Arr=[{'id':-5,'text':'--请选择--'}];$.XB.ComboData(this,Arr,'-5','<?php echo U('Members/Memmessage/getCate');?>'); },valueField:'id',textField:'text'"></select>
                    </td>
                </tr>
                <!--<tr>-->
                    <!--<td align="right">模板替换</td>-->
                    <!--<td valign="top" class="Hui">-->
                        <!--<a onclick="Insert('{SMS_验证码}')" class="Pointer">验证码</a> |-->
                        <!--<a onclick="Insert('{SMS_密码}')" class="Pointer">密码</a>-->
                    <!--</td>-->
                <!--</tr>-->
                <tr>
                    <td width="120" align="right"><span class="Red">*</span> 内容标题：</td>
                    <td>
                        <input id="Title" name="Title" class="easyui-textbox" data-options="required:true" />
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right"><span class="Red">*</span> 内容：</td>
                    <td>
                        <textarea id="Contents" name="Contents" style="width:400px;height:100px;"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
    </form>

    <script>
        function Insert(str) {
            var obj = document.getElementById('SendMess');
            if (document.selection) {
                obj.focus();
                var sel = document.selection.createRange();
                document.selection.empty();
                sel.text = str;
            } else {
                var prefix, main, suffix;
                prefix = obj.value.substring(0, obj.selectionStart);
                main = obj.value.substring(obj.selectionStart, obj.selectionEnd);
                suffix = obj.value.substring(obj.selectionEnd);
                obj.value = prefix + str + suffix;
            }
            obj.focus();
        }
        $(function(){
            $("#objec").html('');
            $('#Obj').combobox({
                url: "<?php echo U('Members/Memmessage/getObj');?>",
                valueField: 'id',
                textField: 'text',
                onChange:function (val) {
                    if(val==2){
                        var html='<td width="120" align="right"><span class="Red">*</span>发送的会员：</td><td><select name="UserID" id="UserID" class="easyui-combobox" data-options="required:true,editable:true"></select></td>';

                        $("#objec").prepend(html);
                        $('#UserID').combobox({
                            url: "<?php echo U('Members/Memmessage/getmajors');?>",
                            valueField: 'id',
                            textField: 'text',
                        });
                    }else{
                        $("#objec").html('');
                    }
                }
            });

            $('#Obj').combobox({
                url: "<?php echo U('Members/Memmessage/getObj');?>",
                valueField: 'id',
                textField: 'text',
            });

        });
    </script>

</body>
</html>