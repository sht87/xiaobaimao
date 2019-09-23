<?php
namespace Api\Controller\Core;
use Think;
use XBCommon\XBCache;
use Think\Log;
class ToolController extends BaseController {
    const T_TABLE='sys_timestamp';
    const T_MEM_INFO='mem_info';
    const T_MOBILE_CODE='code';

    /**
     * @功能说明: 获取服务器时间戳
     * @传输方式: get提交
     * @提交网址: /core/tool/timestamp
     * @提交方式: client=android&package=ceshi.app&ver=v1.1
     * @返回方式: {'result'=>1,'message'=>'恭喜您，获取时间戳成功！','data'}
     */
    public function timestamp()
    {
        //根据服务器当前时间，生成时间戳
        $data=array();
        $data['Time']=time();
        $data['Val']=substr(md5($data['Time']+mt_rand(100,999)),0,30);
        $db=M(self::T_TABLE);
        $result=$db->add($data);
        if($result){
            //保存成功,获取最新ID值
            $data['ID']=$result;
        }else{
            exit(json_encode(array('result'=>0,'message'=>'生成时间戳失败,请联系管理员!')));
        }
        //返回时间戳
        $output=array(
            'result'=>1,
            'message'=>'恭喜您，获取时间戳成功！',
            'data'=>$data
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 获取手机验证码
     * @传输方式: get提交
     * @提交网址: /core/tool/getcode
     * @提交方式：client=android&package=ceshi.app&ver=v1.1&Mobile=17602186118&type=2&code=0214
     *              type 2注册  1找回密码      code 图形验证码
     * @返回方式: {'result'=>1,'message'=>'恭喜您!,验证码获取成功!'}
     */
    public function getcode(){
        $para=I('get.');
        if(!$para['type']){
            exit(json_encode(array('result'=>0,'message'=>'请携带发送短信类型参数!')));
        }
        if(!$para['code']){
           //exit(json_encode(array('result'=>0,'message'=>'请输入图形验证码!')));
        }

        $cache=new XBCache();
        $code = $cache->GetCache('code');
        $codeStr=implode(',',$code['verify_code']);
        $code=str_replace(',','',$codeStr);
		if($para['type']!=5&&$para['type']!=6){
			
		}
		if($para['code']!=$code){
			//exit(json_encode(array('result'=>0,'message'=>'请输入正确的图形验证码!')));
		}
        //判断手机格式
        if(!is_mobile($para['Mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        $db=M(self::T_MOBILE_CODE);
        $code=$db->where(array('Name'=>$para['Mobile']))->order('UpdateTime Asc')->find();
        $res=M(self::T_MEM_INFO)->where(array("Mobile"=>$para['Mobile'],'IsDel'=>0))->find();
        if($para['type']==2 && $res){
            exit(json_encode(array('result'=>0,'message'=>'您已注册过账号!')));
        }
		if($para['type']==5 && !$res){
            //exit(json_encode(array('result'=>0,'message'=>'账号未注册!')));
        }
        if($para['type']==1 && $res==''){
            //exit(json_encode(array('result'=>0,'message'=>'该账号尚未注册!')));
        }
        if($code){
            //1分钟内同一个手机只能发送一次验证码
            $curtime=strtotime(date('Y-m-d H:i:s'));
            $lasttime=strtotime($code['UpdateTime']);
            $time=($curtime-$lasttime)/60;  //分钟
            if($time<1){
                exit(json_encode(array('result'=>0,'message'=>'请求过于频繁,请于分钟'.(1-(int)$time).'后尝试!')));
            }else{
                //删除过期的验证码
                $db->where(array('ID'=>$code['ID']))->delete();
            }
        }
        //发送验证码并记录入库
        $data=array(
            'Name'=>$para['Mobile'],
            'Code'=>mt_rand(1000,9999),
            'UpdateTime'=>date('Y-m-d H:i:s'),
        );

        $code = $data['Code'];
        $msg = "您的短信验证码为".$code."，10分钟内有效，若非本人操作请忽略。";


        $res = sendmessage($para['Mobile'],$msg,2);
        $result=json_decode($res,true);
        if(isset($result['code'])  && $result['code']=='0'){
            $res=$db->add($data);
            if($res){
                exit(json_encode(array('result'=>1,'message'=>'恭喜您!,验证码获取成功!')));
            }else{
                exit(json_encode(array('result'=>0,'message'=>'很抱歉,数据保存失败!')));
            }
        }else{
            exit(json_encode(array('result'=>0,'message'=>'验证码获取失败,请联系管理员!')));
        }
    }

	/**
     * @功能说明: 获取手机验证码
     * @传输方式: get提交
     * @提交网址: /core/tool/getcode
     * @提交方式：client=android&package=ceshi.app&ver=v1.1&Mobile=17602186118&type=2&code=0214
     *              type 2注册  1找回密码      code 图形验证码
     * @返回方式: {'result'=>1,'message'=>'恭喜您!,验证码获取成功!'}
     */
    public function logingetcode(){
       $para=I('get.');
        if(!$para['type']){
            exit(json_encode(array('result'=>0,'message'=>'请携带发送短信类型参数!')));
        }
        if(!$para['code']){
            //exit(json_encode(array('result'=>0,'message'=>'请输入图形验证码!')));
        }

        $cache=new XBCache();
        $code = $cache->GetCache('code');
        $codeStr=implode(',',$code['verify_code']);
        $code=str_replace(',','',$codeStr);
		if($para['type']!=5&&$para['type']!=6){
			
		}
		if($para['code']!=$code){
			//exit(json_encode(array('result'=>0,'message'=>'请输入正确的图形验证码!')));
		}
        //判断手机格式
        if(!is_mobile($para['Mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        $db=M(self::T_MOBILE_CODE);
        $code=$db->where(array('Name'=>$para['Mobile']))->order('UpdateTime Asc')->find();
        $res=M(self::T_MEM_INFO)->where(array("Mobile"=>$para['Mobile'],'IsDel'=>0))->find();
        if($para['type']==2 && $res){
            exit(json_encode(array('result'=>0,'message'=>'您已注册过账号!')));
        }
		if($para['type']==5 && !$res){
            exit(json_encode(array('result'=>0,'message'=>'账号未注册!')));
        }
        if($para['type']==1 && $res==''){
            exit(json_encode(array('result'=>0,'message'=>'该账号尚未注册!')));
        }
        if($code){
            //1分钟内同一个手机只能发送一次验证码
            $curtime=strtotime(date('Y-m-d H:i:s'));
            $lasttime=strtotime($code['UpdateTime']);
            $time=($curtime-$lasttime)/60;  //分钟
            if($time<1){
                exit(json_encode(array('result'=>0,'message'=>'请求过于频繁,请于分钟'.(1-(int)$time).'后尝试!')));
            }else{
                //删除过期的验证码
                $db->where(array('ID'=>$code['ID']))->delete();
            }
        }
        //发送验证码并记录入库
        $data=array(
            'Name'=>$para['Mobile'],
            'Code'=>mt_rand(1000,9999),
            'UpdateTime'=>date('Y-m-d H:i:s'),
        );

        $code = $data['Code'];
        $msg = "您的短信验证码为".$code."，10分钟内有效，若非本人操作请忽略。";


        $res = sendmessage($para['Mobile'],$msg,2);
        $result=json_decode($res,true);
        if(isset($result['code'])  && $result['code']=='0'){
            $res=$db->add($data);
            if($res){
                exit(json_encode(array('result'=>1,'message'=>'恭喜您!,验证码获取成功!')));
            }else{
                exit(json_encode(array('result'=>0,'message'=>'很抱歉,数据保存失败!')));
            }
        }else{
            exit(json_encode(array('result'=>0,'message'=>'验证码获取失败,请联系管理员!')));
        }

	}
}