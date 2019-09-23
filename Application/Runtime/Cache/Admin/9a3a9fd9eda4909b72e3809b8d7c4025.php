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
<style type="text/css">
    .btn{
        padding: 5px 10px ;
        border-radius: 4px;
        color: #eee;
    }
    .add{
        background-color: #0092DC;
    }
    .rem{
        background-color: #ffa8a8;
    }
</style>
<body class="Bodybg">
<form id="FF" class="easyui-form" method="post" data-options="novalidate:true">
	
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
		 
        <tbody>
		<?php if(is_array($itemPriceArr)): $i = 0; $__LIST__ = $itemPriceArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td align="right">
					<?php echo ($i); ?>
				</td>
				<td>
					申请人数<?php echo ($vo["num"]); ?>以内，CPA价格为<?php echo ($vo["price"]); ?>
				</td>
				<td>
					<a href="javascript:void(0)" class="rem btn" data-id="<?php echo ($vo["ID"]); ?>" onclick="rem(this)">移除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>申请人数：</td>
            <td>
                <input id="num" name="num" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
            
        </tr>
		<tr>
		<td width="120" align="right"><span class="Red">*</span>CPA价格：</td>
            <td>
                <input id="price" name="price" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
		</tr>
        </tbody>
    </table>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>
    $(function () {
       
    });
	// 移除行
    function rem(obj) {
		var id = obj.dataset.id;
		$.get("http://xinedai.xyz/admin.php/items/product/ddJtPrice?ID="+id,function(data){
			var ss = JSON.parse(data);
			if(ss.result){
				$(obj).parent().parent().remove();
			}
		});
    }
   
</script>
</body>
</html>