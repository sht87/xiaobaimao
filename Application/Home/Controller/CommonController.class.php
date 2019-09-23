<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: Ghost.42
 * 修改时间: 2017-07-28 8:49
 * 功能说明: 公共控制器
 */

namespace Home\Controller;
use Think\Controller;
use XBCommon\XBCache;
use XBCommon;
class CommonController extends HomeController
{
    const S_TABLE='sys_sms';
    const C_TABLE='code';
	const T_MOBILE_CODE='code';


    /**
     * 生成验证码
     */
    public function selfverify(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->codeSet = '0123456789';
        ob_end_clean();
        $Verify->entry();
    }

    /**
     * 获取手机验证码
     */
    public function getcode(){

        $mobile = I("post.mobile",'','trim');
        $check=I("post.check",'','trim');
        $yzm = I("post.code",'','trim');//图形验证码
		
        //尝试在cookie里取手机号码
        if(!$mobile){
            $mobile=cookie('checkArr')['m'];
        }

        if(!$mobile){
            $this->ajaxReturn(0,'手机号不能为空');
        }elseif(!is_mobile($mobile)){
            $this->ajaxReturn(0,'手机号格式不正确');
        }
		
		if(!$yzm){
			$this->ajaxReturn(0,"图形验证码不正确");
		}

        if($yzm){
            $verify = new \Think\Verify();
            $res = $verify->check($yzm);
            if(!$res){
                $this->ajaxReturn(0,"图形验证码不正确，请重新输入!");
            }
        }


        if($check==1){  //注册验证身份
            $where['Mobile']=$mobile;
            $where['IsDel']=0;
            $find = M('mem_info')->field('ID')->where($where)->find();
            if($find){
                $this->ajaxReturn(2,"该手机号码已注册过会员，不能重复使用！");
            }
        }


        $code=rand(1000,9999);
        $msg = "您的短信验证码为".$code."，10分钟内有效，若非本人操作请忽略。";
        $res = sendmessage($mobile,$msg,2);
        if(!$res){
            $this->ajaxReturn(0,'发送短信异常，请稍后重试！');
        }
        $result=json_decode($res,true);
        if(isset($result['code'])  && $result['code']=='0'){
            $data=array("ObjectID"=>$mobile,"Type"=>1,"Mode"=>1,"SendMess"=>$msg,"Status"=>1,"SendTime"=>date("Y-m-d H:i:s"),"Obj"=>1);
            M(self::S_TABLE)->add($data);

            $res=M(self::C_TABLE)->where(array("Name"=>$mobile,"Type"=>0))->find();
            if($res){
                M(self::C_TABLE)->where(array("Name"=>$mobile))->save(array("Code"=>$code,"UpdateTime"=>date("Y-m-d H:i:s")));
            }else{
                $datas=array("Name"=>$mobile,"Type"=>0,"Code"=>$code,"UpdateTime"=>date("Y-m-d H:i:s"));
                M(self::C_TABLE)->add($datas);
            }
            cookie('yzm',array('yzm'=>$yzm),0);
            $this->ajaxReturn(1,'发送成功,请注意查收！');
        }else{
            $this->ajaxReturn(0,'发送失败,请重新尝试！');
        }
    }

	/**
     * 获取手机验证码
     */
    public function getcodenew(){

        $mobile = I("post.mobile",'','trim');
        $check=I("post.check",'','trim');
        $yzm = I("post.code",'','trim');//图形验证码
		
		//$this->ajaxReturn(0,'系统更新中');
        //尝试在cookie里取手机号码
        if(!$mobile){
            $mobile=cookie('checkArr')['m'];
        }

        if(!$mobile){
            $this->ajaxReturn(0,'手机号不能为空');
        }elseif(!is_mobile($mobile)){
            $this->ajaxReturn(0,'手机号格式不正确');
        }
		
		if(!$yzm){
			$this->ajaxReturn(0,"图形验证码不正确");
		}

        if($yzm){
            $verify = new \Think\Verify();
            $res = $verify->check($yzm);
            if(!$res){
                $this->ajaxReturn(0,"图形验证码不正确，请重新输入!");
            }
        }


        if($check==1){  //注册验证身份
            $where['Mobile']=$mobile;
            $where['IsDel']=0;
            $find = M('mem_info')->field('ID')->where($where)->find();
            if($find){
                $this->ajaxReturn(2,"该手机号码已注册过会员，不能重复使用！");
            }
        }


        $code=rand(1000,9999);
        $msg = "您的短信验证码为".$code."，10分钟内有效，若非本人操作请忽略。";
        $res = sendmessage($mobile,$msg,2);
        if(!$res){
            $this->ajaxReturn(0,'发送短信异常，请稍后重试！');
        }
        $result=json_decode($res,true);
        if(isset($result['code'])  && $result['code']=='0'){
            $data=array("ObjectID"=>$mobile,"Type"=>1,"Mode"=>1,"SendMess"=>$msg,"Status"=>1,"SendTime"=>date("Y-m-d H:i:s"),"Obj"=>1);
            M(self::S_TABLE)->add($data);

            $res=M(self::C_TABLE)->where(array("Name"=>$mobile,"Type"=>0))->find();
            if($res){
                M(self::C_TABLE)->where(array("Name"=>$mobile))->save(array("Code"=>$code,"UpdateTime"=>date("Y-m-d H:i:s")));
            }else{
                $datas=array("Name"=>$mobile,"Type"=>0,"Code"=>$code,"UpdateTime"=>date("Y-m-d H:i:s"));
                M(self::C_TABLE)->add($datas);
            }
            cookie('yzm',array('yzm'=>$yzm),0);
            $this->ajaxReturn(1,'发送成功,请注意查收！');
        }else{
            $this->ajaxReturn(0,'发送失败,请重新尝试！');
        }
    }

    /**
     * 发送邮件
     */
    public function Emailcode(){
        //接收邮箱地址

        $email = I("post.email",'','trim');
        $yzm = I("post.code",'','trim');
        $check = I("post.check",0,'intval');

        if(!$email){
            $this->ajaxReturn(0,'Email不能为空');
        }elseif(!is_email($email)){
            $this->ajaxReturn(0,'Email格式不正确');
        }

        $verify = new \Think\Verify();
        $res = $verify->check($yzm);
        if(!$res){
            $this->ajaxReturn(0,"验证失败，验证码错误!");
        }

        if($check){
            $find = M('mem_info')->field('ID')->where(array('Email'=>$email))->find();
            if($find){
                $this->ajaxReturn(0,"该Email已经存在,不能重复使用");
            }
        }

        $code=rand(1000,9999);
        $message="尊敬的用户，您正在通过邮箱验证，邮箱的验证码：".$code." 请注意查收!";
        $res=sendmessage($email,$message,'验证码通知',1);
        if($res){
            $res=json_decode($res,true);
            if($res['result']!=''){

                cookie('Emailyzm',array('yzm'=>$code,'Email'=>$email),300);

                $this->ajaxReturn(1,"邮件发送成功，请注意查收！");
            }else{
                $this->ajaxReturn(0,$res['error']);
            }
        }

    }


   /*
    * 地区 三级联动
    */
    public function getaddr(){
        $ID=I('post.id',0,'intval');
        $addrInfo = M('sys_areas')->field('ID,Name')->where(array('Pid' => $ID, 'Status' => 1))->select();
        if ($addrInfo) {
            $this->ajaxReturn(1, $addrInfo);
        } else {
            $this->ajaxReturn(0, '没有数据');
        }

    }
    public function getImgCode(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $Verify =     new \Think\ImgCode($config);
        $Verify->codeSet = '0123456789';
        ob_end_clean();
        $Verify->entry();
    }
	public function gethcodeV(){
        $para=I('get.');
        //判断手机格式
        if(!is_mobile($para['mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        $db=M(self::T_MOBILE_CODE);
        $code=$db->where(array('Name'=>$para['mobile']))->order('UpdateTime Asc')->find();
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
		$code=rand(000000,999999);
        $data=array(
            'Name'=>$para['mobile'],
            'Code'=>$code,
            'UpdateTime'=>date('Y-m-d H:i:s'),
        );
        $msg = "您的短信验证码为".$code."，10分钟内有效，若非本人操作请忽略。";
        $res = sendmessage($para['mobile'],$msg,2);
        if(!$res){
            $this->ajaxReturn(0,'发送短信异常，请稍后重试！');
        }
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