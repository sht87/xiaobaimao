<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 张雨
 * 修改时间: 2017/12/14 14:40
 * 功能说明:
 */

namespace Home\Controller;

use Think\Controller;

class SafeController extends UserController
{

    /*
     * 安全中心 首页
     */
    public function center(){
        $UserID=session('loginfo')['UserID'];
        $memInfo=M('mem_info')->where(array('ID'=>$UserID))->getField('Mobile');
        $this->assign(array(
            'mobile'    => $memInfo,
            'type'      => session('loginfo')['Mtype'],
            'title'     => '安全中心',
        ));
        $this->display();
    }

    /*
     * 旧手机验证 页面
     */
    public function oldmobile(){
        $mobile=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mobile');
        $this->assign(array(
            'mobile'    => $mobile,
            'title'     => '更换绑定手机',
        ));
        $this->display();
    }

    /*
     * 验证旧手机
     */
    public function checkold(){
        if(IS_POST){
            $mcode=I('post.mcode','','trim');
            $code=I('post.code','','trim');
            $mobile=I('post.m','','trim');
            if(!$mcode){
                $this->ajaxReturn(0,'手机验证码不能为空！');
            }
            if(!$code){
                $this->ajaxReturn(0,'验证码不能为空！');
            }
            if(!$mobile){
                $this->ajaxReturn(0,'手机号码不能为空！');
            }
            if(!is_mobile($mobile)){
                $this->ajaxReturn(0,'手机号码格式不正确！');
            }
            $getCode=M('code')->where(array('Name'=>$mobile))->getField('Code');

            if($mcode!=$getCode){
                $this->ajaxReturn(0,'手机验证码不正确');
            }



            $verify = new \Think\Verify();
            $res = $verify->check($code);
            if(!$res){
                $this->ajaxReturn(0,"验证失败，验证码错误!");
            }

            $cookieData=array('s'=>2);
            cookie('result',$cookieData,3600);

            M('code')->where(array('Name'=>$mobile))->delete();

            $this->ajaxReturn(2,'');
        }else{
            $this->ajaxReturn(0,'数据提交方式不正确！');
        }
    }

    /*
     * 新手机验证 页面
     */
    public function newmobile(){
        $mtype=session('loginfo')['Mtype'];
        $step=cookie('result')['s'];
        if($step!=2){
            redirect('/Safe/center');
        }
        $this->assign(array(
            'Mtype' => $mtype,
            'title' => '更换绑定手机',
        ));
        $this->display();
    }

    /*
     *  验证新手机
     */
    public function checknew(){
        if(IS_POST){
            $mcode=I('post.mcode','','trim');
            $code=I('post.code','','trim');
            $mobile=I('post.m','','trim');
            $mtype=session('loginfo')['Mtype'];
            $userID=session('loginfo')['UserID'];
            if(!$mcode){
                $this->ajaxReturn(0,'手机验证码不能为空');
            }
            if(!$code){
                $this->ajaxReturn(0,'验证码不能为空');
            }
            if(!$mobile){
                $this->ajaxReturn(0,'手机号码不能为空');
            }
            if(!is_mobile($mobile)){
                $this->ajaxReturn(0,'手机号码格式不正确');
            }

            $checkmobile=M('mem_info')->where(array('Mobile'=>$mobile,'Mtype'=>$mtype))->find();
            if($checkmobile){
                $this->ajaxReturn(0,"该手机已经被绑定");
            }

            $getCode=M('code')->where(array('Name'=>$mobile))->getField('Code');
            if($mcode!=$getCode){
                $this->ajaxReturn(0,'手机验证码不正确');
            }

            $verify = new \Think\Verify();
            $res = $verify->check($code);
            if(!$res){
                $this->ajaxReturn(0,"验证失败，验证码错误");
            }

            $cookieData=array('s'=>3);
            cookie('result',$cookieData,300);

            M('code')->where(array('Name'=>$mobile))->delete();
            $saveRes=M('mem_info')->where(array('ID'=>$userID))->save(array('Mobile'=>$mobile,'UserName'=>$mobile));
            if($saveRes){
                member_sms($userID,"恭喜成功更换了新的绑定手机".substr_replace($mobile,'****',3,4).'！');
                $this->ajaxReturn(2,'');
            }else{
                $this->ajaxReturn(0,'修改失败');
            }

        }else{
            $this->ajaxReturn(0,'数据提交方式不正确！');
        }
    }

    /*
     * 验证完成
     */
    public function success(){
        $step=cookie('result')['s'];
        if($step!=3){
            redirect('/Safe/center');
        }
        $this->assign('title','更改手机');
        $this->display();
    }


    /*
     * 修改密码 页面
     */
    public function password(){
        $this->assign('title','更换密码');
        $this->display();
    }

    /*
     * 修改密码
     */
    public function savepassword(){
        if(IS_POST){
            $oldpass   = I('post.oldpass','','trim');
            $newpass   = I('post.newpass','','trim');
            $agapass   = I('post.agapass','','trim');

            if($oldpass==''){
                $this->ajaxReturn(0,'请输入原密码');
            }
            if($newpass==''){
                $this->ajaxReturn(0,'请输入新密码');
            }
            if($agapass==''){
                $this->ajaxReturn(0,'请再次输入密码');
            }
            if(!is_password($newpass)){
                $this->ajaxReturn(0,'密码格式不正确');
            }
            if($newpass!=$agapass){
                $this->ajaxReturn(0,'两次输入的密码不一致');
            }
            $userID=session('loginfo')['UserID'];
            $pw=M('mem_info')->field('Password')->where(array('ID'=>$userID))->find();

            if(md5($oldpass)!=$pw['Password']){
                $this->ajaxReturn(0,'原密码错误');
            }
            $saveRes=M('mem_info')->where(array('ID'=>$userID))->save(array('Password'=>md5($newpass)));
            if($saveRes){
                member_sms($userID,'恭喜成功更换了登录密码！');
                $this->ajaxReturn(1,'操作成功！');
            }else{
                $this->ajaxReturn(0,'保存失败！');
            }
        }else{
            $this->ajaxReturn(0,'数据提交的方式不正确！');
        }
    }

}