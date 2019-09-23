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
            <td colspan="2">说明：带<span class="Red">*</span>必填</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td  align="right">所属广告位：</td>
            <td>
                <select id="AdvertisingID" name="AdvertisingID">
                    <?php if(is_array($Advertising)): foreach($Advertising as $key=>$item): ?><option value="<?php echo ($item["ID"]); ?>"><?php echo ($item["Name"]); ?></option><?php endforeach; endif; ?>
                </select>

            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>广告名称：</td>
            <td>
                <input id="Name" name="Name" type="text" class="easyui-textbox" required/>
                <span class="Hui"> 最好2到5个字符之间</span>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span> <label>广告图片</label></td>
            <td>
                <input id="Pic" name="Pic" type="text" class="easyui-textbox" data-options="width:320,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/UploadBatch',array('file'=>'Pic','Path'=>'image','ismulti'=>'false'));?>', 'title': 'Logo上传', 'width': 514, 'height': 294, 'fn': function () {  } });}" />
                <a href="javascript:void(0)" id="dd" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
            </td>
        </tr>

        <tr>
            <td  align="right">链接类型：</td>
            <td>
                <input type="radio" name="UrlType" value="1" checked="checked" />外链地址
                <input type="radio" name="UrlType" value="2" />App应用内地址
				<input type="radio" name="UrlType" value="3" />产品链接
            </td>
            <td></td>
        </tr>

		<tr id="productNo"style="display: <?php if($find['UrlType']==3){echo 'table-row';}else{echo 'none';} ?>">
            <td width="120" align="right"><span class="Red"></span>产品编号：</td>
            <td>
                <input id="productNo" name="productNo" type="text" class="easyui-textbox" style="width: 380px;"/>
            </td>
        </tr>


        <tr id="UrlType1" class="UrlType" style="display: <?php if($find['UrlType']==1){echo 'table-row';}else{echo 'none';} ?>">
            <td width="120" align="right"><span class="Red"></span>跳转地址：</td>
            <td>
                <input id="Url" name="Url" type="text" class="easyui-textbox" style="width: 380px;"/>
                <span class="Hui"> 以http://开头</span>
            </td>
        </tr>
		

        <tr id="UrlType2" class="UrlType" style="display: <?php if($find['UrlType']==2){echo 'table-row';}else{echo 'none';} ?>">
            <td  align="right"><span class="Red"></span>跳转分类：</td>
            <td>
                <div>
                    <input id="SystemClass" name="SystemClass" class="easyui-combotree" data-options="width:200,prompt:'--请选择分类--',editable:false,url:'<?php echo U('System/Adcontent/getLastCate');?>'">
                </div>
                <div style="margin-top: 8px;">
                    <input id="SystemClassVal" name="SystemClassVal"  type="text" class="easyui-combotree" data-options="width:200,prompt:'--请选择分类--',editable:false">
                </div>
            </td>
            <td></td>
        </tr>

        <tr>
            <td  align="right">状态：</td>
            <td>
                <select id="Status" name="Status">
                    <?php if(is_array($StatusList)): foreach($StatusList as $key=>$item): ?><option value="<?php echo ($item["DictValue"]); ?>"><?php echo ($item["DictName"]); ?></option><?php endforeach; endif; ?>
                </select>

            </td>
        </tr>
        <tr>
            <td  align="right">排序：</td>
            <td>
                <input id="Sort" name="Sort" class="easyui-numberspinner" type="text" value="999" data-options="width:150,min:1,suffix:'',increment:10,prompt:''" />
                <span class="Hui"> 不填写默认按添加顺序排</span>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- @Html.AntiForgeryToken() -->
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>

    $(function () {
        $('#FF').form('load', '../shows?ID='+<?php echo ($ID); ?>+'&_=' + Math.random() + '');

        $('input:radio[name="UrlType"]').change(function(){
            if($(this).is(":checked")){
                var val= $(this).val();
				
				$('#productNo').hide();
                $('.UrlType').hide();
                $('tr#UrlType'+ val).show();
				if(val==3){
					$('#productNo').show();
				}
            }
        });

        <?php if($find['SystemClass']){ ?>
            $('#SystemClassVal').combotree({
                url:"<?php echo U('System/Adcontent/getCate');?>?ids=<?php echo ($find['SystemClass']); ?>",
                valueField:'id',
                textField:'text',
                panelHeight:'150px'
            });
        <?php } ?>
    });
    $.XB.pictips({ 'id': '#dd', 'path': '#Pic' });

    $('#SystemClass').combotree({
        onSelect:function(node) {
            $('#SystemClassVal').combotree({
                url:"<?php echo U('System/Adcontent/getCate');?>?ids="+node.id,
                required: true,
                valueField:'id',
                textField:'text',
                panelHeight:'150px'
            });
        }
    })
</script>
</body>
</html>