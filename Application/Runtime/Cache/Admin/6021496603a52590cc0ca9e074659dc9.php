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
        <thead>
        <tr>
            <td colspan="4">说明：带<span class="Red">*</span>必填；</td>
        </tr>
        </thead>
        <tbody>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>平台名称：</td>
            <td>
                <input id="Name" name="Name" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
            <td width="120" align="right"><span class="Red">*</span>平台编码：</td>
            <td>
                <input id="GoodsNo" name="GoodsNo" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>关键字：</td>
            <td>
                <input id="Keyeords" name="Keyeords" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
            <td width="120" align="right"><span class="Red">*</span>描述：</td>
            <td>
                <input id="Describe" name="Describe" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>显示位置：</td>
            <td>
                <select name="Showtype" id="Showtype">
                    <option value="1">找借贷</option>
                    <option value="2">贷款分销</option>
                    <option value="3">全部出现</option>
                </select>
            </td>
			<td width="120" align="right"><span class="Red">*</span>获客单价：</td>
            <td>
                <input id="price" name="price" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,precision:2 ,required:true"/>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>借款类型：</td>
            <td colspan="3">
                <!-- <select id="CateID" name="CateID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Items/Product/getCate');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select> -->
                <?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="CateID_<?php echo ($vo['ID']); ?>" style="padding-right: 10px">
                        <input type="checkbox" id="CateID_<?php echo ($vo['ID']); ?>" name="CateID[]" value="<?php echo ($vo['ID']); ?>" <?php if(in_array(($vo['ID']), is_array($cateArr)?$cateArr:explode(',',$cateArr))): ?>checked<?php endif; ?>><?php echo ($vo['Name']); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>借款额度：</td>
            <td colspan="3">
                <!-- <select id="MoneytypeID" name="MoneytypeID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Items/Product/getType');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select> -->
                <?php if(is_array($mtypeList)): $i = 0; $__LIST__ = $mtypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="MoneytypeID_<?php echo ($vo['ID']); ?>" style="padding-right: 10px">
                        <input type="checkbox" id="MoneytypeID_<?php echo ($vo['ID']); ?>" name="MoneytypeID[]" value="<?php echo ($vo['ID']); ?>" <?php if(in_array(($vo['ID']), is_array($moneytypeArr)?$moneytypeArr:explode(',',$moneytypeArr))): ?>checked<?php endif; ?>><?php echo ($vo['Name']); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>借款期限：</td>
            <td colspan="3">
                <!-- <select id="JktimesID" name="JktimesID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Items/Product/getJktimes');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select> -->
                <?php if(is_array($jklist)): $i = 0; $__LIST__ = $jklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="JktimesID_<?php echo ($vo['ID']); ?>" style="padding-right: 10px">
                        <input type="checkbox" id="JktimesID_<?php echo ($vo['ID']); ?>" name="JktimesID[]" value="<?php echo ($vo['ID']); ?>" <?php if(in_array(($vo['ID']), is_array($jktimesArr)?$jktimesArr:explode(',',$jktimesArr))): ?>checked<?php endif; ?>><?php echo ($vo['Name']); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>申请人数：</td>
            <td>
                <input id="AppNumbs" name="AppNumbs" type="text" class="easyui-numberbox" data-options="required:true,suffix:'人'"/>
            </td>
            <td width="120" align="right"><span class="Red">*</span>通过率：</td>
            <td>
                <input id="PassRate" name="PassRate" type="text" class="easyui-textbox" data-options="required:true,prompt:'内容格式：95%'" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>日费率：</td>
            <td>
                <input id="DayfeeRate" name="DayfeeRate" type="text" class="easyui-textbox" data-options="required:true,prompt:'内容格式：0.15%'" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right">佣金模式：</td>
            <td>
                <select name="Yjtype" id="Yjtype">
                    <option value="1">按比例</option>
                    <option value="2">按金额</option>
                </select>
            </td>
            <td width="120" align="right">结算周期：</td>
            <td>
                <input id="Settletime" name="Settletime" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
        </tr>
        <!--按比例 start-->
        <tr class="biliecl">
            <td width="120" align="right">普通会员下款返佣：</td>
            <td>
                <input id="BonusRate1" name="BonusRate1" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
            <td width="120" align="right">初级代理下款返佣：</td>
            <td>
                <input id="BonusRate2" name="BonusRate2" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
        </tr>
        <tr class="biliecl">
            <td width="120" align="right">中级代理下款返佣：</td>
            <td>
                <input id="BonusRate3" name="BonusRate3" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
            <td width="120" align="right">高级代理下款返佣：</td>
            <td>
                <input id="BonusRate4" name="BonusRate4" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
        </tr>
        <!--按比例 end-->
        <!--按金额 start-->
        <tr class="jinecl" style="display:none;">
            <td width="120" align="right">普通会员下款返佣：</td>
            <td>
                <input id="Ymoney1" name="Ymoney1" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
            <td width="120" align="right">初级代理下款返佣：</td>
            <td>
                <input id="Ymoney2" name="Ymoney2" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
        </tr>
        <tr class="jinecl" style="display:none;">
            <td width="120" align="right">中级代理下款返佣：</td>
            <td>
                <input id="Ymoney3" name="Ymoney3" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
            <td width="120" align="right">高级代理下款返佣：</td>
            <td>
                <input id="Ymoney4" name="Ymoney4" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
        </tr>
        <!--按金额 end-->
        <!--申请返佣-->
        <tr>
            <td width="120" align="right">普通会员申请返佣：</td>
            <td>
                <input id="Smoney1" name="Smoney1" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
            <td width="120" align="right">初级代理申请返佣：</td>
            <td>
                <input id="Smoney2" name="Smoney2" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
        </tr>
        <tr>
            <td width="120" align="right">中级代理申请返佣：</td>
            <td>
                <input id="Smoney3" name="Smoney3" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
            <td width="120" align="right">高级代理申请返佣：</td>
            <td>
                <input id="Smoney4" name="Smoney4" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
        </tr>
        <!--申请返佣end-->
        <tr >
            <td width="120" align="right"><span class="Red">*</span> 平台logo图：</td>
            <td>
                <input id="Logurl" name="Logurl" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/UploadBatch',array('file'=>'Logurl','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="dd" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                <span class="Hui">建议大小（121x107）</span>
            </td>
            <td width="120" align="right"><span class="Red">*</span> 平台链接地址：</td>
            <td>
                <input id="Openurl" name="Openurl" type="text" class="easyui-textbox" data-options="required:true,prompt:'默认以http://开头'" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right"> 专属海报1：</td>
            <td>
                <input id="ZsUrl1" name="ZsUrl1" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/UploadBatch',array('file'=>'ZsUrl1','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl1" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                <span class="Hui">建议大小753x1179,二维码位置（510, 925）</span>
            </td>
            <td width="120" align="right">排序：</td>
            <td colspan="3">
                <input type="text" class="easyui-numberbox" id="Sort" name="Sort" data-options="min:0,max:999,value:999,prompt:'0-999，越小越靠前'">
            </td>
        </tr>
        <tr >
            <td width="120" align="right">专属海报2：</td>
            <td>
                <input id="ZsUrl2" name="ZsUrl2" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/UploadBatch',array('file'=>'ZsUrl2','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl2" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                <span class="Hui">建议大小753x1179,二维码位置（510, 925）</span>
            </td>
            <td width="120" align="right">是否推荐：</td>
            <td>
                <select name="IsRec" id="IsRec">
                    <option value="0">不推荐</option>
                    <option value="1">推荐</option>
                </select>
            </td>
        </tr>
        <tr >
            <td width="120" align="right">专属海报3：</td>
            <td>
                <input id="ZsUrl3" name="ZsUrl3" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/UploadBatch',array('file'=>'ZsUrl3','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl3" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
                <span class="Hui">建议大小753x1179,二维码位置（510, 925）</span>
            </td>
            <td width="120" align="right">是否热门：</td>
            <td>
                <select name="IsHot" id="IsHot">
                    <option value="0">不热门</option>
                    <option value="1">热门</option>
                </select>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"> 阶梯基础值：</td>
            <td>
                <input type="text" id="StepBase" name="StepBase" class="easyui-numberbox" data-options="precision:2">
            </td>
            <td width="120" align="right">阶梯介绍1：</td>
            <td>
                <input type="text" id="StepIntro1" name="StepIntro[]" value="<?php echo ($StepIntroArr[0]); ?>"  placeholder="文本限制18个字">
            </td>
        </tr>
        <tr >
            <td width="120" align="right"> 阶梯增长值1：</td>
            <td>
                <input type="text" id="StepInc1" name="StepInc1" class="easyui-numberbox" data-options="precision:2">
            </td>
            <td width="120" align="right">阶梯介绍2：</td>
            <td>
                <input type="text" id="StepIntro2" name="StepIntro[]" value="<?php echo ($StepIntroArr[1]); ?>"  placeholder="文本限制18个字">
            </td>
        </tr>
        <tr >
            <td width="120" align="right">阶梯增长值2：</td>
            <td>
                <input type="text" id="StepInc2" name="StepInc2" class="easyui-numberbox" data-options="precision:2">
            </td>
            <td width="120" align="right"> 阶梯介绍3：</td>
            <td>
                <input type="text" id="StepIntro3" name="StepIntro[]" value="<?php echo ($StepIntroArr[2]); ?>" placeholder="文本限制18个字">
            </td>
        </tr>
        <tr >
            <td width="120" align="right">阶梯值单位：</td>
            <td>
                <input type="text" id="StepUnit" name="StepUnit" class="easyui-textbox" data-options="prompt:'如%，元等'">
            </td>
            <td width="120" align="right"><span class="Red">*</span>状态：</td>
            <td>
                <select name="Status" id="Status">
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>申请条件：</td>
            <td colspan="3">
                <?php if(is_array($condition)): $i = 0; $__LIST__ = $condition;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="ConditIDs_<?php echo ($vo['ID']); ?>" style="padding-right: 10px">
                        <input type="checkbox" id="ConditIDs_<?php echo ($vo['ID']); ?>" name="ConditIDs[]" value="<?php echo ($vo['ID']); ?>" <?php if(in_array(($vo['ID']), is_array($condArr)?$condArr:explode(',',$condArr))): ?>checked<?php endif; ?>><?php echo ($vo['Name']); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>所需材料：</td>
            <td colspan="3">
                <?php if(is_array($need)): $i = 0; $__LIST__ = $need;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="NeedIDs_<?php echo ($vo['ID']); ?>" style="padding-right: 10px">
                        <input type="checkbox" id="NeedIDs_<?php echo ($vo['ID']); ?>" name="NeedIDs[]" value="<?php echo ($vo['ID']); ?>" <?php if(in_array(($vo['ID']), is_array($needArr)?$needArr:explode(',',$needArr))): ?>checked<?php endif; ?>><?php echo ($vo['Name']); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>简短介绍：</td>
            <td colspan="3">
                <textarea name="Intro" id="Intro" style="height:45px;width:400px;" maxlength="50" placeholder="50字以内"></textarea>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span> 基本工资简介：</td>
            <td colspan="3">
                <input type="text" id="BaseFee" name="BaseFee" class="easyui-textbox" data-options="required:true"  style="width:400px" >
            </td>
        <tr>
        </tr>
        <td width="120" align="right"><span class="Red">*</span>阶梯工资简介：</td>
        <td colspan="3">
            <input type="text" id="StepFee" name="StepFee" class="easyui-textbox" data-options="required:true" style="width:400px" >
        </td>
        </tr>
        <tr>
            <td width="120" align="right">下款攻略：</td>
            <td colspan="3">
                <table id="Downconts">
                    <?php if($credit['Downconts'] != ''): if(is_array($DowncontsArr)): $i = 0; $__LIST__ = $DowncontsArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td>
                                    <input type="text" name="Downconts[]" value="<?php echo ($vo); ?>" style="width:400px" />
                                </td>
                                <td width="100" align="center">
                                    <?php if($i == 1): ?><a href="javascript:void(0)" class="add btn"  onclick=add('Downconts')  >增加</a>
                                        <?php else: ?>
                                        <a href="javascript:void(0)" class="rem btn" data="<?php echo ($val["ID"]); ?>" onclick="rem(this)">移除</a><?php endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <tr>
                            <td>
                                <input type="text" name="Downconts[]" style="width:400px" />
                            </td>
                            <td width="100" align="center">
                                <a href="javascript:void(0)" data="0" class="add btn"  onclick="add('Downconts')">增加</a>
                            </td>
                        </tr><?php endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">参与方式：</td>
            <td colspan="3">
                <table id="PartType">
                    <?php if($credit['PartType'] != ''): if(is_array($PartTypeArr)): $i = 0; $__LIST__ = $PartTypeArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td>
                                    <input type="text" name="PartType[]" value="<?php echo ($vo); ?>" style="width:400px" />
                                </td>
                                <td width="100" align="center">
                                    <?php if($i == 1): ?><a href="javascript:void(0)" class="add btn"  onclick=add('PartType')  >增加</a>
                                        <?php else: ?>
                                        <a href="javascript:void(0)" class="rem btn" data="<?php echo ($val["ID"]); ?>" onclick="rem(this)">移除</a><?php endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <tr>
                            <td>
                                <input type="text" name="PartType[]" style="width:400px" />
                            </td>
                            <td width="100" align="center">
                                <a href="javascript:void(0)" data="0" class="add btn"  onclick="add('PartType')">增加</a>
                            </td>
                        </tr><?php endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">工资介绍：</td>
            <td colspan="3">
                <table id="FeeIntro">
                    <?php if($credit['FeeIntro'] != ''): if(is_array($FeeIntroArr)): $i = 0; $__LIST__ = $FeeIntroArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td>
                                    <input type="text" name="FeeIntro[]" value="<?php echo ($vo); ?>" style="width:400px" />
                                </td>
                                <td width="100" align="center">
                                    <?php if($i == 1): ?><a href="javascript:void(0)" class="add btn"  onclick=add('FeeIntro')  >增加</a>
                                        <?php else: ?>
                                        <a href="javascript:void(0)" class="rem btn" data="<?php echo ($val["ID"]); ?>" onclick="rem(this)">移除</a><?php endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <tr>
                            <td>
                                <input type="text" name="FeeIntro[]" style="width:400px" />
                            </td>
                            <td width="100" align="center">
                                <a href="javascript:void(0)" data="0" class="add btn"  onclick="add('FeeIntro')">增加</a>
                            </td>
                        </tr><?php endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">结算方式：</td>
            <td colspan="3">
                <table id="AccountType">
                    <?php if($credit['AccountType'] != ''): if(is_array($AccountTypeArr)): $i = 0; $__LIST__ = $AccountTypeArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td>
                                    <input type="text" name="AccountType[]" value="<?php echo ($vo); ?>" style="width:400px" />
                                </td>
                                <td width="100" align="center">
                                    <?php if($i == 1): ?><a href="javascript:void(0)" class="add btn"  onclick=add('AccountType')  >增加</a>
                                        <?php else: ?>
                                        <a href="javascript:void(0)" class="rem btn" data="<?php echo ($val["ID"]); ?>" onclick="rem(this)">移除</a><?php endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <tr>
                            <td>
                                <input type="text" name="AccountType[]" style="width:400px" />
                            </td>
                            <td width="100" align="center">
                                <a href="javascript:void(0)" data="0" class="add btn"  onclick="add('AccountType')">增加</a>
                            </td>
                        </tr><?php endif; ?>
                </table>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>出现的高级：</td>
            <td>
                <select name="Isshow" id="Isshow">
                    <option value="1">全国</option>
                    <option value="2">部分高级</option>
                </select>
            </td>

			<td width="120" align="right">跳转类型：</td>
            <td>
                <select name="linkType" id="linkType">
					<option value="1">间接跳转</option>
                    <option value="2">直接跳转</option>
                </select>
            </td>
        </tr>

		<tr>
            <td width="120" align="right">注册率阈值：</td>
            <td>
                <input id="registerPv" name="registerPv" type="text" class="easyui-numberbox" data-options="precision:2" />
            </td>
            <td width="120" align="right">UV阈值：</td>
            <td>
                <input id="UV" name="UV" type="text" class="easyui-numberbox" data-options="min:0,max:9999" />
            </td>
        </tr>
		<tr>
			<td width="120" align="right">自动下线方式：</td>
            <td>
                <select name="enableType" id="enableType">
					<option value="0">不下线</option>
					<option value="1">注册率阈值</option>
                    <option value="2">UV阈值</option>
                </select>
            </td>
            <td width="120" align="right">永久下线次数：</td>
            <td>
                <input id="enableNum" name="enableNum" type="text" class="easyui-numberbox" data-options="min:0,max:99" />
            </td>
        </tr>
        <!--出现的高级 start-->
            <?php if($showflag && $citidArr):?>
                <?php foreach($citidArr as $k=>$v):?>
                    <?php if($k==0):?>
                        <tr id="first_tc" class="selfshop2">
                            <td width="120" align="right"><label for="Phone">高级名</label></td>
                            <td style="width:280px;">
                                <select id="Cityids<?php echo ($k); ?>" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids<?php echo ($k); ?>'),Arr,'<?php echo ($v); ?>','/admin.php/Items/Product/getarea');},onSelect:function(rec){}"></select>
                                <input type="button" name="clkbtn" class="clkbtn" value="+"/>
                            </td>
                        </tr>   
                    <?php else:?>
                        <tr class="selfshop2">
                            <td width="120" align="right">高级名</td>
                            <td style="width:280px;">
                                <select id="Cityids<?php echo ($k); ?>" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids<?php echo ($k); ?>'),Arr,'<?php echo ($v); ?>','/admin.php/Items/Product/getarea');},onSelect:function(rec){}"></select>
                                <input type="button" name="clkbtn" class="clkbtn" value="-" onclick="javascript:$(this).parent().parent().remove();"/>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach;?>
            <?php else:?>
                <!--相关套餐-->
            <tr id="first_tc" class="selfshop2" style="<?php if(!$showflag) echo 'display:none;';?>">
                <td width="120" align="right"><label for="Phone">高级名</label></td>
                <td style="width:280px;">
                    <select id="Cityids1" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids1'),Arr,'','/admin.php/Items/Product/getarea');},onSelect:function(rec){}"></select>
                    <input type="button" name="clkbtn" class="clkbtn" value="+"/>
                </td>
            </tr>   
            <?php endif;?>
            <!--出现的高级 end-->
        </tbody>
    </table>
    <input type="hidden" id="ID" name="ID" value="<?php echo ($ID); ?>"/>
</form>
<script>
    $(function () {
        $('#FF').form('load', '/admin.php/Items/Product/loadajax?ID='+"<?php echo ($ID); ?>"+'&_=' + Math.random() + '');
        $.XB.pictips({ 'id': '#dd', 'path': '#Logurl' });
        $.XB.pictips({ 'id': '#ddZsUrl1', 'path': '#ZsUrl1' });
        $.XB.pictips({ 'id': '#ddZsUrl2', 'path': '#ZsUrl2' });
        $.XB.pictips({ 'id': '#ddZsUrl3', 'path': '#ZsUrl3' });
        $.XB.pictips({ 'id': '#ddStepImg1', 'path': '#StepImg1' });
        $.XB.pictips({ 'id': '#ddStepImg2', 'path': '#StepImg2' });
        $.XB.pictips({ 'id': '#ddStepImg3', 'path': '#StepImg3' });

        //出现的高级
        var numb=1;
        $('.clkbtn').click(function(){
            if($(this).val()=='+'){
                numb++;
                //添加
                var tchtml='';
                tchtml+='<tr class="selfshop2"><td width="120" align="right"></td><td style="width:280px;">';
                tchtml+='<select id="Cityids'+numb+'" name="Cityids[]" class="easyui-combotree" data-options="';

                tchtml+="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids"+numb+"'),Arr,'','/admin.php/Items/Product/getarea');},onSelect:function(rec){}";

                tchtml+='"></select>';

                tchtml+='&nbsp;<input type="button" name="clkbtn" class="clkbtn" value="-" onclick="javascript:$(this).parent().parent().remove();"/></td></tr>';
                var targetObj = $(tchtml).insertAfter("#first_tc");
                $.parser.parse(targetObj);
            }
        });
        $('#Isshow').change(function(){
            if($(this).val()==1){
                $('.selfshop2').css('display','none');
            }else{
                $('.selfshop2').css('display','');
            }
        });
        //佣金模式切换
        $('#Yjtype').change(function(){
            if($(this).val()==2){
                $('.biliecl').css('display','none');
                $('.jinecl').css('display','');
            }else{
                $('.biliecl').css('display','');
                $('.jinecl').css('display','none');
            }
        });
        var Yjtype='<?php echo ($Yjtype); ?>';
        if(Yjtype=='2'){
            $('.biliecl').css('display','none');
            $('.jinecl').css('display','');
        }
    });

    // 添加行
    function add(selector) {
        var trHtml  =' <tr><td>'
                +'<input type="text"  name="'+selector+'[]" style="width:400px" />'
                +'</td><td width="100" align="center">'
                +'<a href="javascript:void(0)" class="rem btn"  onclick="rem(this)">移除</a>'
                +'</td></tr>';

        $('#'+selector).append(trHtml);
    }
    // 移除行
    function rem(obj) {
            // 移除行
        $(obj).parent().parent().remove();
    }

    var again_accept="<?php echo $meminfos['Isaccept'];?>";
    if(again_accept==0 || again_accept==2){
        $('.selfshop').css('display','none');
    }
</script>
</body>
</html>