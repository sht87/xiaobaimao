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
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax"
>
            <thead>
                <tr>
                    <td colspan="2">说明：带<span class="Red">*</span>必填；</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="right">所属分类：</td>
                    <td>
                        <select id="CategoriesID" name="CategoriesID" class="easyui-combotree" data-options="required:true,editable:false,url:'<?php echo U('System/Contentmanagement/add');?>',queryParams: {'Add': '[{&quot;id&quot;:-5,&quot;text&quot;:&quot;所有分类&quot;}]'}"></select>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="200"><span class="Red">*</span> 标题：</td>
                    <td>
                        <input id="Title" name="Title" type="text" class="easyui-textbox" data-options="required:true,validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td align="right">封面图片：</td>
                   <td>
                        <input id="CoverImage" name="CoverImage" type="text" class="easyui-textbox" data-options="width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'CoverImage','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                        <a href="javascript:void(0)" id="dd2" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                    </td>
                </tr>
                <tr>
                    <td align="right">文章来源：</td>
                    <td>
                        <input id="Soruce" name="Soruce" type="text" class="easyui-textbox" data-options="validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td align="right">文章作者：</td>
                    <td>
                        <input id="Author" name="Author" type="text" class="easyui-textbox" data-options="validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td align="right">内容简介：</td>
                    <td>
                        <textarea id="Lead" name="Lead" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">文章内容：</td>
                    <td>
                        <textarea id="Contents" name="Contents"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">排序：</td>
                    <td>
                        <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:1,formatter: $.XB.JSSortInt,prompt:'越小越靠前'">
                    </td>
                </tr>
                <tr>
                    <td align="right">是否发布：</td>
                    <td>
                        <select id="IsPublish" name="IsPublish">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">是否推荐：</td>
                    <td>
                        <select id="IsTui" name="IsTui">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
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
            </tbody>
        </table>
        <input type="hidden" id="ID" name="ID" value="<?php echo ($id); ?>"/>
    </form>
</div>
<div id="ft" style="padding:4px;text-align:center;">
    <input onclick="$.XB.pagesave({'isiframe':true,'isClose':true});" type="button" value=" 确 定 ">
    <input onclick="$.XB.easyuireset();" type="button" style="margin-left:20px;" value=" 重 置 ">
</div>
    <!-- <?php echo load_editor_js('ueditor');?> -->
    <?php echo load_editor_js('kindeditor');?>
    <script>
     $(function () {
        var id=$("#ID").val();
         $('#FF').form('load', '../shows?ID='+id+'&_=' + Math.random() + '').form({
             onLoadSuccess: function (data) {
                 <?php echo editor('kindeditor',0,'Contents');?>
             }
         });
     });
     $.XB.pictips({ 'id': '#CoverImage', 'path': '#CoverImage' });
    </script>
</body>
</html>