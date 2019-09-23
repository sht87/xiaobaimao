<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 吴
// +----------------------------------------------------------------------
// | Date: 2018/1/22
// +----------------------------------------------------------------------

return array(

    //* 其他配置  *//
    'Help_Sex' => array(0=>"保密",1=>"男",2=>"女"), //性别
    'Right_Wrong' => array(1=>"<font color=\"#FF0000\" style=\"font-size:18px\">√</font>",0=>"<font color=\"#0000FF\" style=\"font-size:18px\">×</font>"),//

    'Help_Normal' => array(0=>"隐藏",1=>"正常"), //正常隐藏状态
    'Help_Enable' => array(0=>"禁用",1=>"启用"), //启用禁用状态

    'Help_Cycle_Type' => array(1=>"分钟",2=>"小时",3=>'天数'), //周期间隔的类型


    'Help_SystemClass' => array(
        //'goods' => array( 'name'=>'商品管理', 'table'=>'goods_class', 'child'=>array( 'Cid'=>'', 'CidVal'=>'', ), ),
        'article' => array( 'name'=>'内容管理', 'table'=>'sys_contentcategories', 'child'=>array( 'Cid'=>'', 'CidVal'=>'', ), ),
        'user' => array(
            'name'=>'会员中心', 'table'=>'', 'child'=>array(
                    1=>array( 'Cid'=>'1', 'CidVal'=>'会员消息',),
                    //2=>array( 'Cid'=>'2', 'CidVal'=>'我的推广', ),
                    //4=>array( 'Cid'=>'4',  'CidVal'=>'我的佣金', ),
                ),
            ),
        //'other' => array( 'name'=>'内置模块', 'table'=>'', 'child'=>array( 2=>array( 'Cid'=>'2', 'CidVal'=>'9.9专区', ),3=>array( 'Cid'=>'3', 'CidVal'=>'排行榜', )   ), ),
    ),

    //微信公众号(服务号)配置
    'Wachet_Token' => 'SzMhwjccnRFvsVC2NnuAQmQuMyzuuZic',
    'Wachet_AppId' => 'wx0b660201f26c5e6b',
    'Wachet_AppSecret' => 'ee807f6c51dfe4b4c4c3f484a3bb3872',
);