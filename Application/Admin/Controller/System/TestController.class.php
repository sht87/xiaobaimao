<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 刁洪强
 * 修改时间: 2017-04-27 08:50
 * 功能说明:广告管理
 */
namespace Admin\Controller\System;
use Think\Controller;
use XBCommon;
class TestController extends  Controller
{
    public function index()
    {
        $upToken=get_up_token();
        $this->assign('upToken',$upToken);
        $this->display();
    }
}