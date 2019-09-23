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
                <td colspan="2">说明：设置好权限后，请仔细检查下菜单和按钮选中情况，以免手误让角色下用户权限越权。</td>
            </tr>
        </thead>
    </table>
    <div id="aa" class="easyui-accordion" data-options="border:false">
        <?php if(is_array($menulist)): $i = 0; $__LIST__ = $menulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div title="<?php echo ($val["Name"]); ?>" data-options="iconCls:'<?php echo ($val["IconCls"]); ?>'">
                <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
                    <tbody>

                        <?php if(is_array($val["children"])): $i = 0; $__LIST__ = $val["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td width="120" align="right">
                                    <label for="C<?php echo ($vo["ID"]); ?>">
                                        <input type="checkbox" id="C<?php echo ($vo["ID"]); ?>" name="MBID[]"  onclick="Select(this)" <?php if($vo["Select"] == 1): ?>checked="checked"<?php endif; ?> value="<?php echo ($val["ID"]); ?>:<?php echo ($vo["ID"]); ?>:0" /> <?php echo ($vo["Name"]); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php if($vo['children'] and !$vo['OperationButton']): ?><table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
                                            <tbody>
                                            <?php if(is_array($vo["children"])): $i = 0; $__LIST__ = $vo["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?><tr>
                                                    <td width="120" align="right">
                                                    <label for="C<?php echo ($v1["ID"]); ?>">
                                                        <input type="checkbox" id="C<?php echo ($v1["ID"]); ?>" name="MBID[]"  onclick="Select(this)" <?php if($v1["Select"] == 1): ?>checked="checked"<?php endif; ?> value="<?php echo ($vo["ID"]); ?>:<?php echo ($v1["ID"]); ?>:<?php echo ($val["ID"]); ?>" /> <?php echo ($v1["Name"]); ?>
                                                    </label>
                                                </td>
                                                    <td>
                                                        <?php if(is_array($v1["OperationButton"])): foreach($v1["OperationButton"] as $key=>$v2): if($v2["ButtonID"] == 1): ?><input type="checkbox" id="C<?php echo ($v1["ID"]); ?>c<?php echo ($v2["ButtonID"]); ?>c1"  name="MBID[]" value="<?php echo ($vo["ID"]); ?>:<?php echo ($v1["ID"]); ?>:<?php echo ($v2["ButtonID"]); ?>" <?php if($v2["select"] == 1): ?>checked="checked"<?php endif; ?>/> <?php echo ($v2["Name"]); ?>
                                                                <?php else: ?>
                                                                <label for="C<?php echo ($v1["ID"]); ?>c<?php echo ($v2["ButtonID"]); ?>">
                                                                    <input type="checkbox" id="C<?php echo ($v1["ID"]); ?>c<?php echo ($v2["ButtonID"]); ?>"  name="MBID[]" value="<?php echo ($vo["ID"]); ?>:<?php echo ($v1["ID"]); ?>:<?php echo ($v2["ButtonID"]); ?>" <?php if($v2["select"] == 1): ?>checked="checked"<?php endif; ?>/><?php echo ($v2["Name"]); ?>
                                                                </label><?php endif; endforeach; endif; ?>
                                                    </td>
                                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </tbody>
                                        </table>
                                        <?php else: ?>
                                        <?php if(is_array($vo["OperationButton"])): $i = 0; $__LIST__ = $vo["OperationButton"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v["ButtonID"] == 1): ?><input type="checkbox" id="C<?php echo ($vo["ID"]); ?>c<?php echo ($v["ButtonID"]); ?>c1"  name="MBID[]" value="<?php echo ($val["ID"]); ?>:<?php echo ($vo["ID"]); ?>:<?php echo ($v["ButtonID"]); ?>" <?php if($v["select"] == 1): ?>checked="checked"<?php endif; ?>/> <?php echo ($v["Name"]); ?>
                                                <?php else: ?>
                                                <label for="C<?php echo ($vo["ID"]); ?>c<?php echo ($v["ButtonID"]); ?>">
                                                    <input type="checkbox" id="C<?php echo ($vo["ID"]); ?>c<?php echo ($v["ButtonID"]); ?>"  name="MBID[]" value="<?php echo ($val["ID"]); ?>:<?php echo ($vo["ID"]); ?>:<?php echo ($v["ButtonID"]); ?>" <?php if($v["select"] == 1): ?>checked="checked"<?php endif; ?>/><?php echo ($v["Name"]); ?>
                                                </label><?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>


                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                    </tbody>
                </table>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <input name="__RequestVerificationToken" type="hidden" value="EcX545l0zDnRcaFj4HEHY4flfo_w3GFwlvhk6Xb2AJxdCiGDDBr-iYCvQuWBKDGo46_AOk2Z37drRGJ2SI8MecrIKnGKH9vILOTKRryFdXw1" />
    <input type="hidden" id="ID" name="ID" value="<?php echo ($roleID); ?>" />
</form>

<script>
    function Select(T) {
        var $T = $(T);
        if ($T.is(":checked")) {
            $T.parent().parent().parent().find("input").prop('checked', 'checked');
        }
        else {
            $T.parent().parent().parent().find("input").removeAttr('checked');
        }
    }
</script>
</body>
</html>