<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2017/04/12 16:04
 * 功能说明: 找回密码控制器
 */
 
 namespace Home\Controller;

 class ForgetpwdController extends HomeController{

     //步骤一
     public function stepone(){
         $this->assign(array(
             'title'=>'忘记密码',
         ));
        $this->display();
     }

     /**
      * 验证手机号码是否注册
      */
     public function ajaxcheckmodel(){
         $mobile=I('post.mobile','');
         $infos=M('mem_info')->where(array('Mobile'=>$mobile,'IsDel'=>0))->find();
         if($infos){
             $this->ajaxReturn(1,'存在此会员');
         }else{
             $this->ajaxReturn(0,'该手机号码尚未注册,请先注册');
         }
     }

     /**
      * 验证
      */
     public function checkone(){
         if(!IS_POST) {
             $this->ajaxReturn(0,"数据提交方式不正确");
         }
         $Mobile = I("post.mobile",'',"trim");
         $code = I("post.code",'',"trim");
         //手机号码校验
         if (!$Mobile){
             $this->ajaxReturn(0, "请填写手机号码");
         }
         $member = M('mem_info')->where(array("Mobile" => $Mobile,'IsDel'=>0))->find();
         if (!$member) {
             $this->ajaxReturn(0, "该手机号码的会员不存在");
         }

         //短信验证码
         $exit_code=M('code')->where(array("Name"=>$Mobile,'Code'=>$code))->find();
         if(!$exit_code){
             $this->ajaxReturn(0,"请输入正确的短信验证码");
         }
         cookie('passcheck',array('uid'=>$member['ID'],'code'=>date('YmdHis').$Mobile,'step'=>1),0);
         $this->ajaxReturn(1,"成功");

     }

     //步骤二
     public function steptwo(){
         $passcheck = cookie('passcheck');
         if($passcheck['step']<>1 || !$passcheck['uid']){
             redirect('stepOne',0);
         }
         $this->assign('title','忘记密码');
         $this->display();
     }

     public function checktwo(){
         if(!IS_POST) {
             $this->ajaxReturn(0, '数据提交方式不对');
         }
         $newpass=I('post.newpass','','trim');
         $surepass=I('post.surepass','','trim');

         $userID=cookie('passcheck')['uid'];
         if(!$newpass){
             $this->ajaxReturn(0,'密码不能为空');
         }
         if(!$surepass){
             $this->ajaxReturn(0,'请输入确认密码');
         }
         if($surepass!=$newpass){
             $this->ajaxReturn(0,'两次输入的密码不一致');
         }
         if(!is_password($newpass)){$this->ajaxReturn(0,'密码必须是以英文字母开头，6-16位与数字的组合');}
         $saveData=array(
             'Password'=>md5($newpass),
             'UpdateTime'=>date('Y-m-d H:i:s')
         );
         $saveRes=M('mem_info')->where(array('ID'=>$userID))->save($saveData);
         if($saveRes){
             $Mobile=substr(cookie('passcheck')['code'],14,11);
             M('code')->where(array('Name'=>$Mobile))->delete();
             member_sms($userID,$Type=1,"忘记密码重置","您已成功重置了您的密码，请妥善保管自己的账号。");
             $this->ajaxReturn(1,'密码修改成功');
         }else{
             $this->ajaxReturn(0,'修改失败');
         }
     }
 }
 