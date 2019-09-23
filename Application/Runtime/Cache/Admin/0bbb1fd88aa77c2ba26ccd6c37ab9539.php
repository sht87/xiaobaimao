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
            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
                <thead>
                    <tr>
                        <td colspan="4">说明：带<span class="Red">*</span>必填；访问地址父菜单填写#；排序数字越小越靠前 排序范围：1-100 默认按添加顺序排；菜单按钮四项参数都可留空</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100" align="right"><span class="Red">*</span> 菜单名称：</td>
                        <td>
                            <input id="Name" name="Name" type="text" class="easyui-validatebox" data-options="required:true,validType:['length[1,100]']" />
                        </td>
                        <td width="80" align="right"><span class="Red">*</span> 菜单地址：</td>
                        <td>
                            <input id="Url" name="Url" type="text" class="easyui-textbox" data-options="required:true,validType:['length[1,100]'],prompt:'填写区域和控制器名称组成的绝对地址'" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">父级菜单：</td>
                        <td>
                           <select id="ParentID" name="ParentID" class="easyui-combotree" data-options="required:true,url: '../MenuTree'"/>
                        </td>
                        <td align="right"><span class="Red">*</span> 菜单图标：</td>
                        <td>
                            <input type="text" id="Icon" name="Icon" class="easyui-textbox" data-options="required:true,buttonText: ' 点击选择 ',iconAlign:'left',editable: false,onClickButton: function () {$.XB.window({'url':'admin.php/system/AidPage/icon/?ID=Icon','title':'图标选择','width':590,'height':300});}">
                        </td>
                    </tr>
                    <tr>
                        <td align="right">排序：</td>
                        <td>
                            <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:1,formatter: $.XB.JSSortInt" />
                        </td>
                        <td align="right">状态：</td>
                        <td>
                            <select id="Status" name="Status">
                                <option value="1">正常</option>
                                <option value="0">隐藏</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
                                <thead>
                                    <tr style="background-image:none;background-color:#e5e5e5;color:#444;cursor:pointer;">
                                        <td width="100">按钮名称</td>
                                        <td title="点击按钮触发的地址，默认：菜单地址+按钮英文名称">访问地址</td>
                                        <td title="窗口页面提交后，执行的地址，默认：菜单地址+save">提交地址</td>
                                        <td title="提交地址执行完成后，触发的函数">执行函数</td>
                                        <td title="不填写，默认：650px">窗口宽度</td>
                                        <td title="不填写，默认：400px">窗口高度</td>
                                        <td title="点击按钮后，窗口打开的方式">打开方式</td>
                                        <td><input id="btnSearch" onclick="$.XB.window({ 'url': '/admin.php/system/AidPage/MenuButton/?ID=0', 'title': '菜单按钮', 'width': 620, 'height': 350 });" type="button" value="加按钮"></td>
                                    </tr>
                                </thead>
                                <tbody id="mb">
                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="B<?php echo ($val["ButtonID"]); ?>">
                                        <?php if(($val["ButtonID"] == 1) or ($val["ButtonID"] == 2)): ?><td>
                                                <?php if($val["ButtonID"] == 1): ?><div class="Separator"></div>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="ToolBtn"><span class="<?php echo ($val["Icon"]); ?>"></span><b><?php echo ($val["Name"]); ?></b></a><?php endif; ?>
                                            </td>
                                            <td><input name="ButtonID[]" type="hidden" value="<?php echo ($val["ButtonID"]); ?>" /><input name="ButtonURL[]" value="<?php echo ($val["ButtonURL"]); ?>" type="hidden" /></td>
                                            <td><input name="ButtonSaveURL[]" value="<?php echo ($val["ButtonSaveURL"]); ?>" type="hidden" /></td>
                                            <td><input name="JsFunction[]" value="<?php echo ($val["IsFunction"]); ?>" type="hidden" /></td>
                                            <td><input name="Width[]" value="<?php echo ($val["Width"]); ?>" type="hidden" /></td>
                                            <td><input name="Height[]" value="<?php echo ($val["Height"]); ?>" type="hidden" /></td>
                                            <td><input name="OpenMode[]" value="<?php echo ($val["OpenMode"]); ?>" type="hidden" /></td>

                                         <?php else: ?>
                                            <td><a href="javascript:void(0);" class="ToolBtn"><span class="<?php echo ($val["Icon"]); ?>"></span><b><?php echo ($val["Name"]); ?></b></a></td>
                                            <td><input name="ButtonID[]" type="hidden" value="<?php echo ($val["ButtonID"]); ?>" /><input name="ButtonURL[]" value="<?php echo ($val["ButtonURL"]); ?>" type="text" style="width:120px;" /></td>
                                            <td><input name="ButtonSaveURL[]" value="<?php echo ($val["ButtonSaveURL"]); ?>" type="text" style="width:120px;" /></td>
                                            <td><input name="JsFunction[]" value="<?php echo ($val["JsFunction"]); ?>" type="text" style="width:45px;" /></td>
                                            <td><input name="Width[]" value="<?php echo ($val["Width"]); ?>" type="text" style="width:35px;" />px</td>
                                            <td><input name="Height[]" value="<?php echo ($val["Height"]); ?>" type="text" style="width:35px;" />px</td>
                                            <td>
                                                <select name="OpenMode[]" style="width:70px;">
                                                    <?php if(is_array($openmode_name)): $i = 0; $__LIST__ = $openmode_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($val["OpenMode"] == $key): ?><option value="<?php echo ($key); ?>"  selected="selected"><?php echo ($v); ?></option>
                                                         <?php else: ?>
                                                            <option value="<?php echo ($key); ?>"><?php echo ($v); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                </select>
                                            </td><?php endif; ?>
                                        <td><span class="icon310 ButtonSort" onclick="mbdel('.B<?php echo ($val["ButtonID"]); ?>',this);"></span><span class="icon207 ButtonSort" onclick="UP(this)"></span><span class="icon204 ButtonSort" onclick="Down(this)"></span></td>
                                        <input name="MenuButtonID[]" type="hidden" value="<?php echo ($val["ID"]); ?>">
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
              <input type="hidden" id="ID" name="ID" value="<?php echo ($id); ?>"/>
        </form>
    </div>
    <div id="ft" style="padding:4px;text-align:center;">
        <input onclick="$.XB.pagesave({ 'istree': true });" type="button" value=" 确 定 " />
        <input onclick="$.XB.easyuireset();" type="button" style="margin-left:20px;" value=" 重 置 " />
    </div>
    <script>
        function mbdel(t, f) {
            if (typeof (f) == 'undefined') {
                $(t).remove();
            }
            else {
                $(f).parent().parent().remove();
            }
        }
        function UP(t) {
            var $T = $(t).parent().parent();
            $T.prev().before($T);
        }
        function Down(t) {
            var $T = $(t).parent().parent();
            $T.next().after($T);
        }


        $(function () {
            $('#FF').form('load', '../shows?ID='+<?php echo ($id); ?>+'&_=' + Math.random() + '');
        });

    </script>
</body>
</html>