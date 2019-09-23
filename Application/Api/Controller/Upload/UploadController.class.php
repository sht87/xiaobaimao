<?php
namespace Api\Controller\Upload;
use Think\Controller\RestController;
use XBCommon\CacheData;
use XBCommon\XBCache;
use XBCommon;
class UploadController extends RestController
{
    const T_MEM='mem_info';

    /**
     * @功能说明: 图片上传
     * @传输格式: 私有token,有提交，明文返回
     * @提交网址: /Upload/Upload/index
     * @提交信息：非josn form 表单 post方式提交 FILES  Multipart/form-data
     * @返回信息: {'result'=>1,'message'=>'修改成功！'}
     */
    public function index(){
        $para=I('post.');//接收参数
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
            exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
        }

        $AppInfo=XBCache::GetCache($para['token']);
        if(!empty($AppInfo)){
            //判断token的有效期
            $last_time=strtotime($AppInfo['TimeOut']);
            $current_time=strtotime(date("Y-m-d H:i:s"));
            $active_time=get_basic_info('Session'); //单位:分钟
            if(($current_time-$last_time)/60>$active_time){
                //已过期重新登录
                XBCache::Remove($para['token']);
                exit(json_encode(array('result'=>-1,'message'=>'登录已失效,点击确定后重新登录!')));
            }else{
                //未过期更新过期时间
                $AppInfo['TimeOut']=date('Y-m-d H:m:s');
                XBCache::Insert($para['token'],$AppInfo);
            }
        }else{
            exit(json_encode(array('result'=>-1,'message'=>'登录已失效,请点击确定后重新登录!')));
        }

        $upload=new XBCommon\XBUpload();
        $result=$upload->uploadimage();
        if($result['result']!='success'){
            $result=json_decode($result,true);
            exit(json_encode(array("result"=>0,"message"=>$result['message'])));
        }
		$data['HeadImg'] = 'http://'.$_SERVER['HTTP_HOST'].$result['path'];
		M(self::T_MEM)->where(array('ID'=>$AppInfo['ID']))->save($data);
        //返回图片存储的相对路径
        exit(json_encode(array("result"=>1,"message"=>$result['message'] ,'path'=>$result['path'] ,'filepath'=>'http://'.$_SERVER['HTTP_HOST'].$result['path'] ) ));
    }
}