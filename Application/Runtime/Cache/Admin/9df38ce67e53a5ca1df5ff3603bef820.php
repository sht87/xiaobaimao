<?php if (!defined('THINK_PATH')) exit(); if(is_array($nav_list)): $key = 0; $__LIST__ = $nav_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($key % 2 );++$key;?><div class="checkbuttonNo panelcheck" id="<?php echo ($nav["ID"]); ?>">
	<div class="checktext">
		<span class="<?php echo ($nav["Icon"]); ?>" style="width:16px;height:16px;display:block;float:left;margin-top:12px;margin-right:5px;"></span>
		<span style="float:left;"><?php echo ($nav["Name"]); ?></span>
	</div>
		<div class="yesno triangleNo"></div>
</div><?php endforeach; endif; else: echo "" ;endif; ?>