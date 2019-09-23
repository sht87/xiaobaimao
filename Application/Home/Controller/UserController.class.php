<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2017-09-12 15:43
 * 功能说明: 会员父类控制器
 */
namespace Home\Controller;
use Think\Controller;
use XBCommon\CacheData;
use XBCommon\XBCache;

class UserController extends Controller
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


       $start=strpos($_SERVER['REQUEST_URI'],'?back=');


       if($start){
            $http=$_SERVER['REQUEST_URI'];
       }else{
           $http='/Login/index?back='.$_SERVER['REQUEST_URI'];
       }

       if(session('loginfo')['UserID']){
       	   //校验会员信息
       	   $result=M('mem_info')->find(session('loginfo')['UserID']);
       	   if($result){
       	   	 if($result['IsDel']=='1'){
                if(IS_POST){
                   $this->ajaxReturn(80,'当前还没登录，请重新登录...',$http);
                }else{
                   redirect($http);
                }
       	   	 }
       	   	 if($result['Status']=='0'){
                if(IS_POST){
                   $this->ajaxReturn(80,'会员已被禁用,请联系管理员...',$http);
                }else{
                    redirect($http);
                }
       	   	 }
       	   }else{
               if(IS_POST){
                  $this->ajaxReturn(80,'当前还没登录，请重新登录',$http);
               }else{
                  redirect($http);
               }
           }
       }else{
           if(IS_POST){
               $this->ajaxReturn(80,'当前还没登录，请重新登录',$http);
            }else{
               redirect($http);
            }
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
}