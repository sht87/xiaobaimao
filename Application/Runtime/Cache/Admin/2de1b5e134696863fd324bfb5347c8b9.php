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
        <form id="FF" method="post">
        <div id="tools" class="tools">

            <?php echo W('RolePerm/RolePermTop');?>

        </div>
        <div id="search" class="search">
            <table border="0" class="SearchTable" cellpadding="3">
                <thead>
                    <tr>
                        <td width="70" align="right">卡名称：</td>
                        <td width="180">
                            <input id="Title" name="Title" type="text" />
                        </td>
                        <td width="70" align="right">所属银行：</td>
                        <td width="180">
                            <select name="BankID" id="BankID">
                                <option value="-5">全部</option>
                                <?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ID"]); ?>"><?php echo ($vo["BankName"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td width="70" align="right">卡种类：</td>
                        <td width="180">
                            <select name="KatypeID" id="KatypeID">
                                <option value="-5">全部</option>
                                <?php if(is_array($mtypeList)): $i = 0; $__LIST__ = $mtypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ID"]); ?>"><?php echo ($vo["Name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td>
                            <input id="btnSearch" onclick="$.XB.search();" type="button" value="查 询">
                        </td>
                        <td>
                            <input id="MoreSearch" onclick="$.XB.moresearch()" type="button" value="更多条件">
                        </td>
                    </tr>
                </thead>
                <tbody id="stbody">
                    <tr>
                        <td width="70" align="right">显示位置：</td>
                        <td width="180">
                            <select name="Showtype" id="Showtype">
                                <option value="-5">全部</option>
                                <option value="1">找借贷</option>
                                <option value="2">贷款分销</option>
                                <option value="3">全部出现</option>
                            </select>
                        </td>
                        <td width="70" align="right">出现城市：</td>
                        <td width="180">
                            <select name="Isshow" id="Isshow">
                                <option value="-5">全部</option>
                                <option value="1">全国</option>
                                <option value="2">部分城市</option>
                            </select>
                        </td>
                        <td width="70" align="right">佣金模式：</td>
                        <td width="180">
                            <select name="Yjtype" id="Yjtype">
                                <option value="-5">全部</option>
                                <option value="1">按比例</option>
                                <option value="2">按金额</option>
                            </select>
                        </td>
                    </tr>
                     <tr>
                        <td width="70" align="right">状态：</td>
                        <td width="180">
                            <select name="Status" id="Status">
                                <option value="-5">全部</option>
                                <option value="1">启用</option>
                                <option value="0">禁用</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            <div id="tabelContent" class="tabelContent">
                <table id="DataList"></table>
            </div>
    </form>
    <script type="text/javascript">

        $(function () {
                var frozenColumns=[
                 { field: 'ID', checkbox: true },
                 { field: 'Name', title: '卡名称', width: 150 }
                ];
                var columns= [
                    { field: 'Showtype', title: '显示位置', width: 90},
                    { field: 'Isshow', title: '出现的城市', width: 80},
                    { field: 'BankID', title: '所属银行', width: 80,sortable:true},
                    { field: 'SubjectID', title: '卡片主题', width: 80 ,sortable:true},
                    { field: 'KatypeID', title: '卡类型', width: 80 ,sortable:true},
                    { field: 'BitypeID', title: '币种', width: 80 ,sortable:true},
                    { field: 'YearfeeID', title: '年费', width: 80 ,sortable:true},
                    { field: 'GoodsNo', title: '卡编码', width: 100 ,sortable: true},
                    { field: 'AppNumbs', title: '申请人数', width: 80 ,sortable: true},
//                    { field: 'PassRate', title: '通过率', width: 100,sortable: true },
                    { field: 'Yeardesc', title: '年费备注', width: 150 ,sortable: true},
                    { field: 'Jifen1', title: 'RMB积分描述', width: 150 ,sortable: true},
                    { field: 'Jifen2', title: 'USD积分描述', width: 150 ,sortable: true},
                    { field: 'Freetime', title: '免息天数', width: 80 ,sortable: true},
                    { field: 'Freedesc', title: '免息描述', width: 150 ,sortable: true},
                    { field: 'Levelname', title: '卡等级', width: 80 ,sortable: true},
                    { field: 'Settletime', title: '结算周期', width:90},
                    { field: 'Yjtype', title: '佣金模式', width: 70},
                    { field: 'BonusRate1', title: '普通会员奖金点', width: 110 ,sortable: true},
                    { field: 'BonusRate2', title: '初级代理奖金点', width: 110 ,sortable: true},
                    { field: 'BonusRate3', title: '中级代理奖金点', width: 110 ,sortable: true},
                    { field: 'BonusRate4', title: '高级代理奖金点', width: 110 ,sortable: true},

                    { field: 'Ymoney1', title: '普通会员佣金', width: 110 ,sortable: true},
                    { field: 'Ymoney2', title: '初级代理佣金', width: 110 ,sortable: true},
                    { field: 'Ymoney3', title: '中级代理佣金', width: 110 ,sortable: true},
                    { field: 'Ymoney4', title: '高级代理佣金', width: 110 ,sortable: true},
                    { field: 'Status', title: '状态', width: 70,sortable:true,},
                    { field: 'IsRec', title: '是否推荐', width: 100,sortable:true,formatter:Common.DoFormatter },
                    { field: 'IsHot', title: '是否热门', width: 100 ,sortable:true,formatter:Common.DoFormatter},
                    { field: 'Sort', title: '排序', width: 70 ,sortable:true},
                    { field: 'OperatorID', title: '添加人', width: 100 },
                    { field: 'UpdateTime', title: '添加时间', width: 150, sortable: true },
                ];
            $.XB.datagrid({'frozenColumns':frozenColumns,'columns':columns});
            $.XB.enter();
        });
    </script>
        <?php echo W('RolePerm/RolePermBottom');?>
	</body>
</html>