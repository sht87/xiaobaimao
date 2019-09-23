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
<div id="tt" class="easyui-tabs" >
    <div title="网贷详情" style="padding: 20px; background-color: #f4f4f4">
        <fieldset style="border: 1px solid #ccc;margin:5px; ">
            <legend  style="color: #0092DC">网贷信息</legend>
            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable EditTableMax">
                <tbody>
                <tr>
                    <td width="120" align="right">网贷平台：</td>
                    <td width="200">
                        <?php echo ($Info['Name']); ?>
                    </td>
                    <td width="120" align="right">平台编码：</td>
                    <td width="200">
                        <?php echo ($Info['GoodsNo']); ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">关键字：</td>
                    <td width="200">
                        <?php echo ($Info['Keyeords']); ?>
                    </td>
                    <td width="120" align="right">描述：</td>
                    <td width="200">
                        <?php echo ($Info['Describe']); ?>
                    </td>
                </tr>
                <tr >
                    <td width="120" align="right">申请人数：</td>
                    <td width="200">
                        <?php echo ($Info['AppNumbs']); ?>
                    </td >
                    <td width="120" align="right">通过率：</td>
                    <td>
                        <?php echo ($Info['PassRate']); ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">日费率：</td>
                    <td>
                        <?php echo ($Info['DayfeeRate']); ?>
                    </td>
                    <td width="120" align="right">结算周期：</td>
                    <td>
                        <?php echo ($Info['Settletime']); ?>
                    </td>
                </tr>

                <tr>
                    <td width="120" align="right">贷款类型：</td>
                    <td width="200" colspan="3">
                        <?php echo ($Info['CateName']); ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">贷款额度：</td>
                    <td width="200" colspan="3">
                        <?php echo ($Info['TypeName']); ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">借款期限：</td>
                    <td width="200" colspan="3">
                        <?php echo ($Info['jktimename']); ?>
                    </td>
                </tr>
				<tr>
                    <td width="120" align="right">跳转类型：</td>
                    <td width="200" colspan="3">
                        <?php echo ($Info['link']); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        </fieldset>
        <fieldset style="border: 1px solid #ccc;margin:5px; ">
        <legend style="color: #0092DC" >条件与材料</legend>
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
            <tbody>
            <tr >
                <td width="120" align="right">申请条件：</td>
                <td width="200" colspan="3">
                    <?php echo ($Info['ConditName']); ?>
                </td>
            </tr>
            <tr >
                <td width="120" align="right">所需材料：</td>
                <td width="200" colspan="3">
                    <?php echo ($Info['NeedName']); ?>
                </td>
            </tr>
            </tbody>
        </table>
        </fieldset>
        <fieldset style="border: 1px solid #ccc;margin:5px; ">
        <legend style="color: #0092DC" >奖金返点</legend>
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
            <tbody>
            <tr >
                <td width="120" align="right">普通会员返点：</td>
                <td width="200">
                    <?php echo (number_format($Info['BonusRate1'],2)); ?>%
                </td>
                <td width="120" align="right">初级代理返点：</td>
                <td>
                    <?php echo (number_format($Info['BonusRate1'],2)); ?>%
                </td>
            </tr>
            <tr >
                <td width="120" align="right">中级代理返点：</td>
                <td width="200">
                    <?php echo (number_format($Info['BonusRate3'],2)); ?>%
                </td>
                <td width="120" align="right">高级代理返点：</td>
                <td>
                    <?php echo (number_format($Info['BonusRate4'],2)); ?>%
                </td>
            </tr>

            <tr >
                <td width="120" align="right">普通会员申请返佣：</td>
                <td width="200">
                    <?php echo ($Info['Smoney1']); ?>元
                </td>
                <td width="120" align="right">初级代理申请返佣：</td>
                <td>
                    <?php echo ($Info['Smoney2']); ?>元
                </td>
            </tr>
            <tr >
                <td width="120" align="right">中级代理申请返佣：</td>
                <td width="200">
                    <?php echo ($Info['Smoney3']); ?>元
                </td>
                <td width="120" align="right">高级代理申请返佣：</td>
                <td>
                    <?php echo ($Info['Smoney4']); ?>元
                </td>
            </tr>
            </tbody>
        </table>
        </fieldset>
        <fieldset style="border: 1px solid #ccc;margin:5px; ">
        <legend style="color: #0092DC" >其他信息</legend>
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="EditTable">
            <tbody>
                <tr>
                    <td width="120" align="right">平台logo图：</td>
                    <td width="200">
                        <div onclick="bigView(this)" style="width:100px;height:100px;background: url(<?php echo ($Info['Logurl']); ?>) center no-repeat;background-size:auto 100px;display: inline-block;margin-left: 5px;cursor: pointer"></div>
                    </td>
                    <td width="120" align="right">专属海报1：</td>
                    <td >
                        <div onclick="bigView(this)" style="width:100px;height:100px;background: url(<?php echo ($Info['ZsUrl1']); ?>) center no-repeat;background-size:auto 100px;display: inline-block;margin-left: 5px;cursor: pointer"></div>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">专属海报2：</td>
                    <td >
                        <div onclick="bigView(this)" style="width:100px;height:100px;background: url(<?php echo ($Info['ZsUrl2']); ?>) center no-repeat;background-size:auto 100px;display: inline-block;margin-left: 5px;cursor: pointer"></div>
                    </td>
                    <td width="120" align="right">专属海报3：</td>
                    <td >
                        <div onclick="bigView(this)" style="width:100px;height:100px;background: url(<?php echo ($Info['ZsUrl3']); ?>) center no-repeat;background-size:auto 100px;display: inline-block;margin-left: 5px;cursor: pointer"></div>
                    </td>
                </tr>
                <tr >

                    <td width="120" align="right">简单描述：</td>
                    <td colspan="3">
                        <?php echo ($Info['Intro']); ?>
                    </td>

                </tr>
                <tr>
                    <td width="120" align="right">下款攻略：</td>
                    <td width="200" colspan="3">
                        <?php if(empty($QuanycontsArr)): ?>暂无描述
                            <?php else: ?>
                            <table cellspacing="0">
                                <?php if(is_array($QuanycontsArr)): $i = 0; $__LIST__ = $QuanycontsArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                        <td width="500" colspan="3" style="padding: 5px 5px 0;font-size:12px;color: #555;border:1px solid transparent; <?php if($i!=count($QuanycontsArr)){echo 'border-bottom:1px solid #fff;';} ?> "><?php echo ($i); ?>、<?php echo ($vo); ?></td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </table><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">是否推荐：</td>
                    <td width="200">
                        <?php switch($Info['IsRec']): case "0": ?>不推荐<?php break;?>
                            <?php case "1": ?>推荐<?php break; endswitch;?>
                    </td>
                    <td width="120" align="right">是否热门：</td>
                    <td width="200">
                        <?php switch($Info['IsHot']): case "0": ?>不热门<?php break;?>
                            <?php case "1": ?>热门<?php break; endswitch;?>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right">添加人：</td>
                    <td width="200">
                       <?php echo ($Info['OperatorID']); ?>
                    </td>
                    <td width="120" align="right">添加时间：</td>
                    <td width="200">
                        <?php echo ($Info['UpdateTime']); ?>
                    </td>
                </tr>

				<tr>
                    <td width="120" align="right">阶梯价格：</td>
                    <td width="200" colspan="3">
                        <?php if(empty($itemPriceArr)): ?>暂无描述
                            <?php else: ?>
                            <table cellspacing="0">
                                <?php if(is_array($itemPriceArr)): $i = 0; $__LIST__ = $itemPriceArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                        <td width="500" colspan="3" style="padding: 5px 5px 0;font-size:12px;color: #555;border:1px solid transparent; <?php if($i!=count(itemPriceArr)){echo 'border-bottom:1px solid #fff;';} ?> "><?php echo ($i); ?>、申请人数<?php echo ($vo["num"]); ?>以内，CPA价格为<?php echo ($vo["price"]); ?></td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </table><?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        </fieldset>
    </div>
</div>
<div style="text-align:center;margin:20px 0px;">
    <input name="Btn" type="button" value=" 关闭  " onclick="parent.$('#W1').window('close');" />
</div>
</body>

<script type="text/javascript">
    function bigView(obj) {
        window.top.$('#tan').show();
        var back=$(obj).css('background-image');
        window.top.$('.tan_content').attr('style','background:'+back+'center no-repeat;background-size:auto 100%;');
    }
</script>
</html>