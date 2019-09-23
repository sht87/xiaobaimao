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
                    <td align="right" width="200"><span class="Red">*</span>搜索关键词：</td>
                    <td>
                        <input id="Keywords" name="Keywords" type="text" class="easyui-textbox" data-options="required:true,validType:['length[1,100]']" /><span class="Hui">*多个关键词请用|格开：例如: 美丽|漂亮|好看</span>
                    </td>
                </tr>
                <tr>
                    <td align="right">关键词类型：</td>
                    <td>
                        <select id="Keystatus" name="Keystatus">
                            <option value="1">完全匹配</option>
                            <option value="2">包含匹配</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">排序：</td>
                    <td>
                        <input id="Sort" name="Sort" type="text" class="easyui-numberbox" data-options="min:0,max:999,value:999,formatter: $.XB.JSSortInt,prompt:'0-999越小越靠前'">
                    </td>
                </tr>
                
                <tr>
                    <td align="right">是否启用：</td>
                    <td>
                        <select id="Status" name="Status">
                            <option value="1">启用</option>
                            <option value="0">禁用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table width="700" border="0" cellpadding="3" cellspacing="0" class="EditTable">
                           <thead>
                               <tr style="background-image:none;height:30px;;background-color:#e5e5e5;color:#444;font-weight:bold;">
                                 <th>标题</th>
                                 <th><span class="salejifenfont">链接地址</span></th>
                                 <th><span class="salejifenfont">图片</span></th>
                               </tr>
                           </thead>
                           <tbody id="Body">
                           <?php if($comtinfos):?>
                              <?php foreach($comtinfos as $k=>$v):?>
                                 <tr id="first_tc">
                                     <td><input id="Title<?php echo ($k); ?>" name="Title[]" value="<?php echo ($v["Title"]); ?>" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:['length[1,100]']" /></td>
                                     <td><input id="linkurl<?php echo ($k); ?>" name="linkurl[]" value="<?php echo ($v["linkurl"]); ?>" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:['length[1,100]']" /></td>
                                     <td>
                                         <input id="Pic<?php echo ($k); ?>" name="Pic[]" value="<?php echo ($v["Pic"]); ?>" type="text" class="easyui-textbox" data-options="width:180,buttonText:'上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '/admin.php/Attachment/File/uploadbatch/?file=Pic<?php echo $k;?>&Path=image&ismulti=false', 'title': '图片上传', 'width': 514, 'height': 294, 'fn': function () {  } });}" />
                                        <?php if($k==0):?>
                                         <input type="button" name="clkbtn" class="clkbtn" value="+"/>
                                        <?php else:?>
                                            <input type="button" name="clkbtn" class="clkbtn" value="-" onclick="javascript:$(this).parent().parent().remove();"/>
                                        <?php endif;?>
                                     </td>
                                 </tr>
                              <?php endforeach;?>
                            <?php else:?>
                                <tr id="first_tc">
                                     <td><input id="Title" name="Title[]" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:['length[1,100]']" /></td>
                                     <td><input id="linkurl" name="linkurl[]" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:['length[1,100]']" /></td>
                                     <td>
                                         <input id="Pic" name="Pic[]" type="text" class="easyui-textbox" data-options="width:180,buttonText:'上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '/admin.php/Attachment/File/uploadbatch/?file=Pic&Path=image&ismulti=false', 'title': '图片上传', 'width': 514, 'height': 294, 'fn': function () {  } });}" />
                                         <input type="button" name="clkbtn" class="clkbtn" value="+"/>
                                     </td>
                                 </tr>
                            <?php endif;?>
                           </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
    </form>
</div>
    <script>
     $(function () {
        var id=$("#ID").val();
         $('#FF').form('load', '../shows?ID='+id+'&_=' + Math.random() + '');

         //添加图文
        var numb=1;
        $('.clkbtn').click(function(){
            if($(this).val()=='+'){
                numb++;
                //添加
                var tchtml='<tr>';
                 tchtml+='<td><input id="Title'+numb+'" name="Title[]" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:[\'length[1,100]\']" /></td>';
                 tchtml+='<td><input id="linkurl'+numb+'" name="linkurl[]" type="text" class="easyui-textbox" data-options="required:true,width:150,validType:[\'length[1,100]\']" /></td>';

                tchtml +='<td><input id="Pic' + numb + '" name="Pic[]" type="text" class="easyui-textbox" data-options="';
                tchtml +="width:180,buttonText:\'上传\',buttonIcon: \'icon30\',onClickButton:  function(){$.XB.window({ 'url': '/admin.php/Attachment/File/uploadbatch/?file=Pic" + numb +"&Path=image&ismulti=false', 'title': '图片上传', 'width': 514, 'height': 294, 'fn': function () {  } });}\" />";

                tchtml+='&nbsp;<input type="button" name="clkbtn" class="clkbtn" value="-" onclick="javascript:$(this).parent().parent().remove();"/></td></tr>';
                var targetObj = $(tchtml).insertAfter("#first_tc");
                $.parser.parse(targetObj);
            }
        });

     });
    </script>
</body>
</html>