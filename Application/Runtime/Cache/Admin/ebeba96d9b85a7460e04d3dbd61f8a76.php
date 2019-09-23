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
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
            <thead>
                <tr>
                    <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right" width="200"><span class="Red">*</span> 标题：</td>
                    <td>
                        <input id="Title" name="Title" type="text" class="easyui-textbox" data-options="required:true,validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td align="right">文章内容：</td>
                    <td>
                        <textarea id="Contents" name="Contents"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">SEO标题：</td>
                    <td>
                        <textarea id="SEOTitle" name="SEOTitle" rows="2"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">SEO关键字：</td>
                    <td>
                        <textarea id="SEOKeyWords" name="SEOKeyWords" rows="2"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">SEO描述：</td>
                    <td>
                        <textarea id="SEODescription" name="SEODescription" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">排序：</td>
                    <td>
                        <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:1,formatter: $.XB.JSSortInt,prompt:'越小越靠前'">
                    </td>
                </tr>
				<tr >
					<td width="120" align="right">是否可用：</td>
					<td>
						<select id="enable" name="enable" >
							<option value="1">是</option>
							<option value="0">否</option>
						</select>
					</td>
				</tr>
            </tbody>
        </table>
        <input type="hidden" id="ID" name="ID" value="<?php echo ($id); ?>"/>
    </form>
</div>
<div id="ft" style="padding:4px;text-align:center;">
    <input onclick="$.XB.pagesave({'isClose':true});" type="button" value=" 确 定 ">
    <input onclick="$.XB.easyuireset();" type="button" style="margin-left:20px;" value=" 重 置 ">
</div>
    <?php echo load_editor_js('kindeditor');?>
    <script>
     $(function () {
         var id=$("#ID").val();
         if(id>0){
             $('#FF').form('load', '/admin.php/System/Simplepage/shows?ID=<?php echo ($id); ?>&_=' + Math.random() + '').form({
                 onLoadSuccess: function () {
                     <?php echo editor('kindeditor',0,'Contents');?>
                 }
             });
         }else{
             <?php echo editor('kindeditor',0,'Contents');?>
         }
     });
     $.XB.pictips({ 'id': '#CoverImage', 'path': '#CoverImage' });
    </script>
</body>
</html>