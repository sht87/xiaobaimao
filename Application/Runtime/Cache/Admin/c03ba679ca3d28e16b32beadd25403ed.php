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
            <td width="120" align="right"><span class="Red">*</span>信用卡名称：</td>
            <td>
                <input id="Name" name="Name" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
            <td width="120" align="right"><span class="Red">*</span>信用卡编码：</td>
            <td>
                <input id="GoodsNo" name="GoodsNo" type="text" class="easyui-textbox" data-options="required:true"/>
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
            <td width="60" align="right"><span class="Red">*</span>卡片主题：</td>
            <td>
                <select id="SubjectID" name="SubjectID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Credit/Items/getSubjects');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>所属银行：</td>
            <td>
                <select id="BankID" name="BankID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Credit/Items/getCate');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select>
            </td>
            <td width="120" align="right"><span class="Red">*</span>信用卡类型：</td>
            <td>
                <select id="KatypeID" name="KatypeID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Credit/Items/getType');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select>
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>年费用：</td>
            <td>
                <select id="YearfeeID" name="YearfeeID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Credit/Items/getFee');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select>
            </td>
            <td width="120" align="right"><span class="Red">*</span>币种：</td>
            <td>
                <select id="BitypeID" name="BitypeID" type="text" class="easyui-combobox" data-options="required:true,editable:false,url:'<?php echo U('Credit/Items/getBitype');?>?ID=<?php echo ($ID); ?>',valueField:'id',textField:'text'"></select>
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
            <td width="120" align="right"><span class="Red">*</span>RMB积分描述：</td>
            <td>
                <input id="Jifen1" name="Jifen1" type="text" class="easyui-textbox" data-options="required:true,value:'RMB ￥1=1积分',prompt:'内容格式：RMB ￥1=1积分'" />
            </td>
            <td width="120" align="right"><span class="Red">*</span>USD积分描述：</td>
            <td>
                <input id="Jifen2" name="Jifen2" type="text" class="easyui-textbox" data-options="required:true,value:'USD $1=21积分',prompt:'内容格式：USD $1=21积分'" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>免息期：</td>
            <td>
                <input id="Freetime" name="Freetime" type="text" class="easyui-textbox" data-options="required:true,value:'30天',prompt:'30天'" />
            </td>
            <td width="120" align="right"><span class="Red">*</span> 免息期描述：</td>
            <td>
                <input id="Freedesc" name="Freedesc" type="text" class="easyui-validatebox" data-options="required:true,validType:['length[1,15]']" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right"><span class="Red">*</span>佣金模式：</td>
            <td>
                <select name="Yjtype" id="Yjtype">
                    <option value="1">按比例</option>
                    <option value="2">按金额</option>
                </select>
            </td>
            <td width="120" align="right"><span class="Red">*</span>结算周期：</td>
            <td>
                <input id="Settletime" name="Settletime" type="text" class="easyui-textbox" data-options="required:true"/>
            </td>
        </tr>
        <!--按比例 start-->
        <tr class="biliecl">
            <td width="120" align="right"><span class="Red">*</span>普通会员返点：</td>
            <td>
                <input id="BonusRate1" name="BonusRate1" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
            <td width="120" align="right"><span class="Red">*</span>初级代理返点：</td>
            <td>
                <input id="BonusRate2" name="BonusRate2" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
        </tr>
        <tr class="biliecl">
            <td width="120" align="right"><span class="Red">*</span>中级代理返点：</td>
            <td>
                <input id="BonusRate3" name="BonusRate3" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
            <td width="120" align="right"><span class="Red">*</span>高级代理返点：</td>
            <td>
                <input id="BonusRate4" name="BonusRate4" type="text" class="easyui-numberspinner" data-options="min:0,max:100,suffix:'%',prompt:'0-100之间的任意两位小数',precision:2" />
            </td>
        </tr>
        <!--按比例 end-->
        <!--按金额 start-->
        <tr class="jinecl" style="display:none;">
            <td width="120" align="right"><span class="Red">*</span>普通会员佣金：</td>
            <td>
                <input id="Ymoney1" name="Ymoney1" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
            <td width="120" align="right"><span class="Red">*</span>初级代理佣金：</td>
            <td>
                <input id="Ymoney2" name="Ymoney2" type="text" class="easyui-numberbox" data-options="min:0,suffix:'元',max:9999" />
            </td>
        </tr>
        <tr class="jinecl" style="display:none;">
            <td width="120" align="right"><span class="Red">*</span>中级代理佣金：</td>
            <td>
                <input id="Ymoney3" name="Ymoney3" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
            <td width="120" align="right"><span class="Red">*</span>高级代理佣金：</td>
            <td>
                <input id="Ymoney4" name="Ymoney4" type="text" class="easyui-numberbox" data-options="suffix:'元',min:0,max:9999" />
            </td>
        </tr>
        <!--按金额 end-->
        <tr >
            <td width="120" align="right"><span class="Red">*</span> 卡logo封面图：</td>
            <td>
                <input id="Logurl" name="Logurl" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'Logurl','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddlogo" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
            </td>
            <td width="120" align="right"><span class="Red">*</span>等级名称：</td>
            <td>
                <input id="Levelname" name="Levelname" type="text" class="easyui-textbox" data-options="required:true,value:'白金卡',prompt:'白金卡'" />
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span> 主题图：</td>
            <td>
                <input id="MainPic" name="MainPic" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'MainPic','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddMainPic" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
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
            <td width="120" align="right"><span class="Red">*</span> 发卡组织logo：</td>
            <td>
                <input id="Fakaurl" name="Fakaurl" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'Fakaurl','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddFakaurl" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
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
            <td width="120" align="right"> 专属海报1：</td>
            <td>
                <input id="ZsUrl1" name="ZsUrl1" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'ZsUrl1','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl1" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
            </td>
            <td width="120" align="right">排序：</td>
            <td colspan="3">
                <input type="text" class="easyui-numberbox" id="Sort" name="Sort" data-options="min:0,max:999,value:999,prompt:'0-999，越小越靠前'">
            </td>
        </tr>
        <tr >
            <td width="120" align="right"> 专属海报2：</td>
            <td>
                <input id="ZsUrl2" name="ZsUrl2" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'ZsUrl2','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl2" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
            </td>
            <td width="120" align="right"><span class="Red">*</span> 平台链接地址：</td>
            <td>
                <input id="Openurl" name="Openurl" type="text" class="easyui-textbox" data-options="required:true,prompt:'默认以http://开头'" />
            </td>
        </tr>
        <tr >
            <td width="120" align="right"> 专属海报3：</td>
            <td>
                <input id="ZsUrl3" name="ZsUrl3" type="text" class="easyui-textbox" data-options="editable:false,width:200,buttonText: '上传',buttonIcon: 'icon30',onClickButton:  function(){$.XB.window({ 'url': '<?php echo U('Attachment/File/uploadbatch',array('file'=>'ZsUrl3','Path'=>'image','ismulti'=>'false'));?>', 'title': '封面', 'width': 514, 'height': 294, 'fn': function () {  } });}" value=""/>
                <a href="javascript:void(0)" id="ddZsUrl3" style="display:inline-block;"><span class="icon317" style="width:16px;display:block;">&nbsp;</span></a>
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
            <td width="120" align="right"> 阶梯增长值2：</td>
            <td>
                <input type="text" id="StepInc2" name="StepInc2" class="easyui-numberbox" data-options="precision:2">
            </td>
            <td width="120" align="right">阶梯介绍3：</td>
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

        <tr>
            <td width="120" align="right"><span class="Red">*</span>简短介绍：</td>
            <td colspan="3">
                <textarea name="Intro" id="Intro" style="height:45px;width:400px;" maxlength="50" placeholder="50字以内"></textarea>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><span class="Red">*</span>年费说明：</td>
            <td colspan="3">
                <textarea name="Yeardesc" id="Yeardesc" cols="30" rows="5" maxlength="50" placeholder="50字以内"></textarea>
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
        <tr>
            <td width="120" align="right">权益说明：</td>
            <td colspan="3">
                <table id="Quanyconts">
                    <?php if($credit['Quanyconts'] != ''): if(is_array($QuanycontsArr)): $i = 0; $__LIST__ = $QuanycontsArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td>
                                    <input type="text" id="Quanyconts<?php echo ($i-1); ?>" name="Quanyconts[]" value="<?php echo ($vo); ?>" style="width:400px" />
                                </td>
                                <td width="100" align="center">
                                    <?php if($i == 1): ?><a href="javascript:void(0)"  class="add btn"   onclick=add('Quanyconts') >增加</a>
                                        <?php else: ?>
                                        <a href="javascript:void(0)" class="rem btn" data="<?php echo ($val["ID"]); ?>" onclick="rem(this)">移除</a><?php endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <input type="text" id="Quanyconts0" name="Quanyconts[]" style="width:400px" />
                            </td>
                            <td width="100" align="center">
                                <a href="javascript:void(0)" data="0" class="add btn"  onclick="add('Quanyconts')">增加</a>
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
			<td width="120" align="right"><span class="Red">*</span>获客价格：</td>
            <td>
                <input id="price" name="price" type="text" class="easyui-numberbox" data-options="required:true,suffix:'元',precision:2"/>
            </td>
        </tr>
        <!--出现的高级 start-->
            <?php if($showflag && $citidArr):?>
                <?php foreach($citidArr as $k=>$v):?>
                    <?php if($k==0):?>
                        <tr id="first_tc" class="selfshop2">
                            <td width="120" align="right"><label for="Phone">高级名</label></td>
                            <td style="width:280px;">
                                <select id="Cityids<?php echo ($k); ?>" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids<?php echo ($k); ?>'),Arr,'<?php echo ($v); ?>','/admin.php/Credit/Items/getarea');},onSelect:function(rec){}"></select>
                                <input type="button" name="clkbtn" class="clkbtn" value="+"/>
                            </td>
                        </tr>   
                    <?php else:?>
                        <tr class="selfshop2">
                            <td width="120" align="right">高级名</td>
                            <td style="width:280px;">
                                <select id="Cityids<?php echo ($k); ?>" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids<?php echo ($k); ?>'),Arr,'<?php echo ($v); ?>','/admin.php/Credit/Items/getarea');},onSelect:function(rec){}"></select>
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
                    <select id="Cityids1" name="Cityids[]" class="easyui-combotree" data-options="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids1'),Arr,'','/admin.php/Credit/Items/getarea');},onSelect:function(rec){}"></select>
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
        $('#FF').form('load', '/admin.php/Credit/Items/loadajax?ID='+"<?php echo ($ID); ?>"+'&_=' + Math.random() + '');
        $.XB.pictips({ 'id': '#ddlogo', 'path': '#Logurl' });
        $.XB.pictips({ 'id': '#ddMainPic', 'path': '#MainPic' });
        $.XB.pictips({ 'id': '#ddFakaurl', 'path': '#Fakaurl' });
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

                tchtml+="onBeforeLoad:function(){var Arr=[];$.XB.combotreedata($('#Cityids"+numb+"'),Arr,'','/admin.php/Credit/Items/getarea');},onSelect:function(rec){}";

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
</script>
</body>
</html>