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
    <div id="aa" class="easyui-accordion" data-options="border:false">
			<?php if(is_array($tgList)): $i = 0; $__LIST__ = $tgList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td width="120" align="right">
						<label for="C<?php echo ($vo["ID"]); ?>">
							<input type="checkbox" id="C<?php echo ($vo["ID"]); ?>" name="MBID[]" <?php if($vo["Select"] == 1): ?>checked="checked"<?php endif; ?> value="<?php echo ($vo["ID"]); ?>" /> <?php echo ($vo["Name"]); ?>
						</label>
					</td>

				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>" />
</form>

<script>
    
</script>
</body>
</html>