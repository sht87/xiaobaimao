<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2017-09-12 09:10
 * 功能说明: 父类控制器
 */

namespace Home\Controller;
use Think\Controller;
use XBCommon\CacheData;
use XBCommon\XBCache;

class HomeController extends Controller
{
    /* 空操作，用于跳转到首页 */
    public function _empty(){
    	$this->error("目前访问的地址有误,现返回到首页!",'/');
    }
    public function _initialize(){
    	//定义全局的配置缓存变量
       // 调用方法  tem  {$GLOBALS['BasicInfo']['SystemName']}
       global $BasicInfo;

       $cache=new XBCache();
       $BasicInfo = $cache->GetCache('BasicInfo');

       if(!$BasicInfo)
       {
           $cache=new CacheData();
           $BasicInfo = $cache->BasicInfo();
       }
    }
    /**
      * AJAX返回数据标准
      * @param int $status  状态
      * @param string $msg  内容
      * @param mixed $data  数据
      * @param string $dialog  弹出方式
      */
     protected function ajaxReturn($status = 0, $msg = '成功', $data = '', $dialog = '')
     {
         $return_arr = array();
         if (is_array($status)) {
             $return_arr = $status;
         } else {
             $return_arr = array(
                 'result' => $status,
                 'message' => $msg,
                 'des' => $data,
                 'dialog' => $dialog
             );
         }
         ob_clean();
         echo json_encode($return_arr);
         exit;
     }
     //收藏商品
     public function Collent(){
        $uid = session('logininfo')['UserID'];
        if(!$uid){
            $this->ajaxReturn(80, '当前还没登录，请重新登录', "/Login/index");
        }
        $gid = I('get.id',0,'intval');
        $find = M('goods')->where('ID='.$gid)->find();
        if(!$find){
            $this->ajaxReturn(0,"未找到商品信息");
        }
        $cind = M('mem_collect')->where('Gid='.$gid.' and Uid='.$uid)->find();
        if($cind){
            $this->ajaxReturn(0,"已经收藏过，不能重复收藏");
        }
        $data['Uid'] = $uid;
        $data['Gid'] = $gid;
        $data['Addtime'] = date('Y-m-d H:i:s');

        $result = M('mem_collect')->add($data);

        if($result){
            M('goods')->where(array('ID'=>$gid))->setInc('Collent',1);
            $this->ajaxReturn(1,'收藏成功');
        }else{
            $this->ajaxReturn(0,"收藏失败!");
        }
    }
    public function area(){
        $id = I('get.id',0,'intval');
        $province = M('sys_areas')->where(array('Pid'=>$id))->select();
        $this->ajaxReturn($province);
    }
    //收货地址的添加and修改页面
    public function address_add(){
        $uid = session('logininfo')['UserID'];
        $result = array();
        $where['Uid'] = $uid;
        $count = M('mem_address')->where($where)->count();
        $this->assign("count",$count);

        $province = M('sys_areas')->where(array('Pid'=>1))->select();
        $this->assign("province",$province);

        $id = I('get.id',0,'intval');
        if($id){
            $result = M('mem_address')->where('Uid='.$uid.' and ID='.$id)->find();


            $city = M('sys_areas')->where(array('Pid'=>$result['province']))->select();

            $county = M('sys_areas')->where(array('Pid'=>$result['city']))->select();

            $this->assign("result",$result);
            $this->assign("city",$city);
            $this->assign("county",$county);

            $this->display('address_edit');
        }else{
            $this->assign("result",$result);
            $this->display();
        }
    }

	

}