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
        <tbody>
            <tr>
                <td width="120" align="right"><span class="Red">*</span> <label for="Name">分类名称</label></td>
                <td>
                    <input id="Name" name="Name" class="easyui-textbox" type="text"  data-options="required:true" />
                </td>
            </tr>
			<tr>
                <td width="120" align="right"><span class="Red">*</span> <label for="EName">英文名称</label></td>
                <td>
                    <input id="EName" name="EName" class="easyui-textbox" type="text"  data-options="required:true" />
                </td>
            </tr>
            <tr>
                <td width="120" align="right"><span class="Red">*</span> <label for="parentID">上级分类</label></td>
                <td>
                    <select id="ParentID" name="ParentID" class="easyui-combotree" data-options="url:'/admin.php/Items/Category/getLastCate',required:true"></select>
                </td>
            </tr>

            <tr>
                <td width="120" align="right"><span class="Red">*</span> <label for="Imageurl">上传图片</label></td>
                <td>
                    <input id="Imageurl" name="Imageurl" type="text" class="easyui-textbox" data-options="width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'Imageurl','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                    <a href="javascript:void(0)" id="dd" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                </td>
            </tr>

			<tr>
                <td width="120" align="right"><span class="Red">*</span><label for="IsRec">推荐</label></td>
                <td>
                    <input type="radio" name="IsRec" value="1" checked="checked" />推荐
                    <input type="radio" name="IsRec" value="0" />不推荐
                </td>
            </tr>
            <tr>
                <td width="120" align="right"><span class="Red">*</span> <label for="Status">状态</label></td>
                <td>
                    <input type="radio" name="Status" value="1" checked="checked" />启用
                    <input type="radio" name="Status" value="0" />禁用
                </td>
            </tr>
            <tr>
                <td width="120" align="right"> <label for="Sort">排序</label></td>
                <td>
                    <input id="Sort" name="Sort" class="easyui-numberbox" data-options="min:0,max:999,value:999,formatter:$.XB.JSSortInt,prompt:'0-999，越小越靠前'" />
                </td>
            </tr>
    </table>
    <input name="__RequestVerificationToken" type="hidden" value="4hUL6XfsKdWft2VZCn8h5NquUotOSQym-UebEsDn4wP-lrJXKjmpzjC5tIqYZKc7y-zgvhZfYCjrr9lusfiU6jfIqCsXruHSjK5qDdiaoYM1" />
    <input type="hidden" id="ID" name="ID" value="<?php echo ($id); ?>"/>
</form>

<script>
	var ID ="<?php echo ($id); ?>";
	if(ID>0){
		 $(function () {
			$('#FF').form('load', '../Shows?ID='+ID+'&_=' + Math.random() + '');
		 });
	}
    $.XB.pictips({ 'id': '#dd', 'path': '#Imageurl' });
</script>



</body>
</html>