<?php
namespace Api\Controller\Core;
use Think\Controller\RestController;
use XBCommon\CacheData;
use XBCommon\XBCache;

class BaseController extends RestController{
    const T_TABLE = 'mem_info';
    const T_BASIC = 'sys_basicinfo';

    //被继承的父类,检测提交POST的数据
    public function _initialize()
    {
        //统一返回数据的字符编码
        header('Content-Type:application/json; charset=utf-8');
        global $BasicInfo;

        $cache=new XBCache();
        $BasicInfo = $cache->GetCache('BasicInfo');

        if(!$BasicInfo){
            $cache=new CacheData();
            $BasicInfo = $cache->BasicInfo();
        }

        if(IS_POST){
            //post 方式
            $para=get_json_data(); //接收参数
            if(empty($para)){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，POST请求必须携带参数！')));
            }
            if(empty($para['token'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，token参数不能为空！')));
            }
            if(empty($para['client'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、PC')));
            }
            if(empty($para['package'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端包名')));
            }
            if(empty($para['ver'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写当前软件版本号')));
            }

            $common_package = common_package($para['client'],$para['package'],$para['ver']);
            if($common_package['result'] == 0){
                //exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
            }

            /*$AppInfo=XBCache::GetCache($para['token']);
            if(!empty($AppInfo)){
                //判断token的有效期
                $last_time=strtotime($AppInfo['TimeOut']);
                $current_time=strtotime(date("Y-m-d H:i:s"));
                $active_time=get_basic_info('Session'); //单位:分钟
                if(($current_time-$last_time)/60>$active_time){
                    //已过期重新登录
                    XBCache::Remove($para['token']);
                    exit(json_encode(array('result'=>-1,'message'=>'登录已失效,点击确定后重新登录!','data'=>array())));
                }else{
                    //未过期更新过期时间
                    $AppInfo['TimeOut']=date('Y-m-d H:i:s');
                    XBCache::Insert($para['token'],$AppInfo);
                }
            }else{
                exit(json_encode(array('result'=>-1,'message'=>'登录已失效,请点击确定后重新登录!','data'=>array())));
            }*/
        }elseif(IS_GET){
            //get 方式
            $get_data=I('get.');

            if(empty($get_data['client'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、PC')));
            }
            if(empty($get_data['package'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端包名')));
            }
            if(empty($get_data['ver'])){
                exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写当前软件版本号')));
            }
			if(empty($get_data['upload'])){
                $common_package = common_package($get_data['client'],$get_data['package'],$get_data['ver']);
				if($common_package['result'] == 0){
					//exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
				}
            }

        }else{
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，提交方式不对!')));
        }
    }
}