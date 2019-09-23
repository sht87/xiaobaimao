<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/12 08:36
 * 功能说明: 登录控制器
 */
 namespace Home\Controller;

 class LoginController extends HomeController {

     const T_TABLE='mem_info';
	 const T_MOBILE_CODE='code';


     public function index(){
         $back=I('get.back','','trim');
         $wx=I('get.wx','','trim');
         //判断是不是微信浏览器访问
         if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!== false && $wx!=1) {
             //redirect('/Wxinfo/index');
         }
         $wxinfos=session('getwxinfo');
         session('getwxinfo',null);
         $this->assign('back',urldecode($back));
         $this->assign('wxinfos',$wxinfos);
//         $this->assign('title',"登录");
         $this->assign('SEOTitle',"登录");

		$simplepage1=M('sys_simplepage')->where(array('ID'=>5))->find();
		$simplepage2=M('sys_simplepage')->where(array('ID'=>8))->find();
		$simplepage3=M('sys_simplepage')->where(array('ID'=>9))->find();
		$this->assign('enable1',$simplepage1['enable']);
		$this->assign('title1',$simplepage1['Title']);

		$this->assign('enable2',$simplepage2['enable']);
		$this->assign('title2',$simplepage2['Title']);

		$this->assign('enable3',$simplepage3['enable']);
		$this->assign('title3',$simplepage3['Title']);
         $this->display();
     }

/*
 * 处理前台的ajax数据
 */
    public function ajaxLogin(){
        if(!IS_POST) {
            $this->ajaxReturn(0,"数据传输方式不正确！");
        }
        $ServiceOpenid=$_POST['ServiceOpenid'];
        $Unionid=$_POST['Unionid'];
        $HeadImg=$_POST['HeadImg'];
        //自动验证
        $rules=array(
            array('Mobile','require',"手机号码必须填写！"),
            array('Password','require',"密码不能为空！"),
        );
        $model=D(self::T_TABLE);
        $FormData=$model->validate($rules)->create();

        if(!$FormData){
            $this->ajaxReturn(0,$model->getError());
        }
        if(!is_mobile($FormData['Mobile'])){
            $this->ajaxReturn(0,"您的手机号码格式不正确！");
        }
        /*if(!is_password($FormData['Password'])){
            $this->ajaxReturn(0,"您的密码格式不正确！");
        }*/
        //条件查找
        $where=array(
           'Mobile'=>$FormData['Mobile'],
            'IsDel'=>0
        );
        $memInfo=$model->where($where)->find();
        if(!$memInfo) {
            $this->ajaxReturn(0,"请注册后登录！");
        }
        if($memInfo['Status']==0){
            $this->ajaxReturn(0,"您已被禁用，请联系管理员！");
        }
        /*if(md5($FormData['Password'])!=$memInfo['Password']){
            $this->ajaxReturn(0,"密码错误！");
        }*/
		//判断验证码是否合法
		$mdb=M(self::T_MOBILE_CODE);
		$code=$mdb->where(array('Name'=>$FormData['Mobile'],'Code'=>$FormData['Password']))->order('UpdateTime ASC')->find();

		if(!$code) {
			exit(json_encode(array('result'=>0,'message'=>'很抱歉,您输入的验证码不正确！')));
		}

		//验证码有效期20分钟
		$curtime=strtotime(date('Y-m-d H:i:s'));
		$lasttime=strtotime($code['UpdateTime']);
		$time=($curtime-$lasttime)/60;  //分钟
		if($time>20){
			exit(json_encode(array('result'=>0,'message'=>'很抱歉,您的验证码已失效！')));
		}

       //更新用户的登录信息
        $loginip=get_client_ip(); //用户登录的ip地址
        $data=array(
            'LastLoginIP'=>$memInfo['LoginIP'],
            'LoginIP'=>$loginip,
            'LastLoginTime'=>$memInfo['LoginTime'],
            'LoginTime'=>date('Y-m-d H:i:s'),
            'IpCity'=>ip_to_address($loginip),
            'LastIpCity'=>$memInfo['IpCity'],
            'PhoneType'=>$FormData['PhoneType']
        );
        if($ServiceOpenid){
            $data['ServiceOpenid']=$ServiceOpenid;
        }
        if($Unionid){
            $data['Unionid']=$Unionid;
        }
        if($HeadImg && !$memInfo['HeadImg']){
            $data['HeadImg']=$HeadImg;
        }
        $res=$model->where(array('ID'=>$memInfo['ID']))->save($data);
        if($res){
            //保存用户数据在session中
            session('loginfo',array('UserID'=>$memInfo['ID'],'UserName'=>$memInfo['UserName'],'Mtype'=>$memInfo['Mtype']));
            $this->ajaxReturn(1,"尊敬的用户".$memInfo['TrueName']."，感谢您的使用！");
        }else{
            $this->ajaxReturn(0,"登录失败，请重新登录！");
        }

    }

    /**
     * 退出登录
     */
    public function logout(){
        session('loginfo',null);
//        $result = parent::uc_logout();
        //echo $result;//输出同步退出,不能去掉
        //$this->redirect('Login/index');
//        $this->ajaxReturn(1,'退出成功!',$result);
        $this->ajaxReturn(1,'退出成功!');
    }
	
 }
 
 