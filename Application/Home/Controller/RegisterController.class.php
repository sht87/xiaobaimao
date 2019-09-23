<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/12 10:25
 * 功能说明: 注册控制器
 */
 
 namespace Home\Controller;
 use Think\Log;

 class RegisterController extends HomeController {

     const T_TABLE='mem_info';
	 const T_CHANNEL='channel_statistics';
     //注册页面
     public function index(){
         $referee=$_GET['ui'];
         if( $referee ){
             $this->assign('referee',$referee);
         }
         $Title=M('sys_simplepage')->where(array('ID'=>5))->getField('Title');
         $this->assign('Title',$Title);
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

     //渠道推广注册页面
     public function tuiguang(){
        $puser=I('get.puser','');
		$tgadmin = M('tg_admin')->where(array('Status'=>1,'IsDel'=>0,'UserName'=>$puser))->find();
		if(!$tgadmin){
			$this->error();
		}
		self::channelStatistics($puser);
        $Title=M('sys_simplepage')->where(array('ID'=>5))->getField('Title');
		$bgimg=M('sys_basicinfo')->where(array('ID'=>1))->getField('bgimg');

		$simplepage1=M('sys_simplepage')->where(array('ID'=>5))->find();
		$simplepage2=M('sys_simplepage')->where(array('ID'=>8))->find();
		$simplepage3=M('sys_simplepage')->where(array('ID'=>9))->find();
		$this->assign('linkType',1);
		if($puser){
			$linkType=M('tg_admin')->where(array('UserName'=>$puser))->getField('linkType');
			$this->assign('linkType',$linkType);
		}
        $this->assign(array(
            'puser'=>$puser,
            'Title'=>$Title,
            ));
		$this->assign('bgimg',$bgimg);
		$this->assign('xy1',$simplepage1['enable']);
		$this->assign('xy1title',$simplepage1['Title']);
		$this->assign('xy2',$simplepage2['enable']);
		$this->assign('xy2title',$simplepage2['Title']);
		$this->assign('xy3',$simplepage3['enable']);
		$this->assign('xy3title',$simplepage3['Title']);
        $this->display();
     }

	 //渠道推广注册页面
     public function content(){
        $this->display();
     }

	 public function content_c(){
        $this->display();
     }
		
		
	public function submitContent(){
        if(!IS_POST){
            $this->ajaxReturn(0,"数据传递方式错误！");
        }
		
		$username = I("post.username",'',"trim");
		$mobile = I("post.phone",'',"trim");
		$sex = I("post.sex",'',"trim");
		$money = I("post.money",'',"trim");
		$card = I("post.cardNum",'',"trim");
		$houseType = I("post.houseType",'',"trim");
		$carType = I("post.carType",'',"trim");
		$zy = I("post.zy",'',"trim");
		$work = I("post.work",'',"trim");
		$gjj = I("post.gjj",'',"trim");
		$ysr = I("post.ysr",'',"trim");
		$sb = I("post.sb",'',"trim");
		$xyk = I("post.xyk",'',"trim");
		$bd = I("post.bd",'',"trim");
		$city = I("post.city",'',"trim");
		$mem = M('mem_info')->where(array("Mobile" => $mobile,'IsDel'=>0))->find();
		$userId = $mem['ID'];
		/*$mem_ed = M('mem_ed')->where(array("userId" => $userId))->find();
		if($mem_ed){
			$this->ajaxReturn(0,"不允许重复申请");
		}*/
		
         $data = array(
             "name" => $username,
             "mobile" => $mobile,
             "userId" => $userId,
             "sex" => $sex,
             "createDate" => date("Y-m-d H:i:s"),
             'money'=>$money,
             'card'=>$card,
			 'houseType'=>$houseType,
			 'carType'=>$carType,
			 'zy'=>$zy,
			 'work'=>$work,
			 'gjj'=>$gjj,
			 'ysr'=>$ysr,
			 'sb'=>$sb,
			 'xyk'=>$xyk,
			 'bd'=>$bd,
			 'city'=>$city,
         );
		 $result = M('mem_ed')->add($data);
         if ($result) {
             $this->ajaxReturn(1,"申请成功!");
         } else {
             $this->ajaxReturn(0,"申请失败!");
         }
	}


     /*
      * 后台处理ajax传递的数据
      */
     public function ajaxRegister(){
         if(!IS_POST){
             $this->ajaxReturn(0,"数据传递方式错误！");
         }

         $Mobile = I("post.Mobile",'',"trim");
         $Password = I("post.Password",'',"trim");
         $MsgCode = I("post.MsgCode",'',"trim");
		 $client = I("post.client",'',"trim");
         //$Email = I("post.Email",'',"trim");
		 if(!$Password){
			$Password = "a123456";
		 }
         //校验手机号
         if(!$Mobile){
             $this->ajaxReturn(0,"请填写手机号码");
         }
         if (!is_mobile($Mobile)) {
             $this->ajaxReturn(0,"手机号码格式不正确！");
         }
         $exit=M('mem_info')->where(array('IsDel'=>0,'Mobile'=>$Mobile))->find();
         if($exit){
             $this->ajaxReturn(2,"该手机号码已注册过会员，不能重复使用！");
         }
         //邮箱的校验
         // if(!$Email){
         //     $this->ajaxReturn(0,"请填写邮箱");
         // }
         // if (!is_email($Email)) {
         //     $this->ajaxReturn(0,"邮箱格式不正确！");
         // }
         // $exitemals=M('mem_info')->where(array('IsDel'=>0,'Email'=>$Email))->find();

         // if($exitemals){
         //     $this->ajaxReturn(0,"该邮箱已注册过会员，不能重复使用！");
         // }
         //在discuz中校验
         // if(parent::uc_check_email($Email)!=1){
         //    $this->ajaxReturn(0,"该邮箱不允许注册");
         // }

         //校验短信验证码
         if(!$MsgCode){
             $this->ajaxReturn(0,"请输入短信验证码");
         }
         $Ccodes = M('code')->where(array("Name" => $Mobile))->getField('Code');
         if ($Ccodes != $MsgCode) {
             $this->ajaxReturn(0, "手机验证码错误，请重新输入!");
         }
         //校验密码
         if(!$Password){
             $this->ajaxReturn(0,"请输入您的密码");
         }
         if (!is_password($Password)) {
             $this->ajaxReturn(0,"密码必须是以英文字母开头，6-16位与数字的组合");
         }

         $data = array(
             "UserName" => $Mobile,
             "TrueName" => substr($Mobile,-4),
             "Password" => md5($Password),
             "Mobile" => $Mobile,
             "RegTime" => date("Y-m-d H:i:s"),
             'Tjcode'=>$Mobile,
             'Mtype'=>1,
			 'PhoneType'=>$client,
         );

         //推荐人的id
         $Referee=I('post.Referee',0,'intval');
         if($Referee){
             $exit_refer=M('mem_info')->where( array('ID'=>$Referee,'IsDel'=>0) )->find();
             if( $exit_refer ){
                 $data['Referee']=$Referee;
             }
         }
         //渠道推广
         $puser=$_POST['puser'];
         if($puser){
             $TgadminID=M('tg_admin')->where(array('UserName'=>$puser,'Status'=>'1','IsDel'=>'0'))->getField('ID');
             if($TgadminID){
                 $data['TgadminID']=$TgadminID;
             }
			 //查询今天是否有统计
			 $tg_form = M('tg_form')->where(array('channelId'=>$TgadminID,'createDate'=>date('Y-m-d')))->find();
			 if($tg_form){
				if($client=='ios'){
					$tg_form['ios'] = $tg_form['ios'] + 1;
				}else{
					$tg_form['android'] = $tg_form['android'] + 1;

				}
				$tg_form['num'] = $tg_form['num'] + 1;
				M('tg_form')->save($tg_form);
			 }else{
				$tg_form = array();
				$tg_form['channelId'] = $TgadminID;
				if($client=='ios'){
					$tg_form['ios'] = 1;
				}else{
					$tg_form['android'] = 1;
				}
				$tg_form['num'] = 1;
				$tg_form['createDate'] = date('Y-m-d');
				M('tg_form')->add($tg_form);
			 }
         }
         $result = M('mem_info')->add($data);
		 self::channelStatisticsReg($puser);
         if ($result) {
             M('code')->where(array("Name" => $Mobile))->delete();
             member_sms($result,$type=1,"恭喜您注册成功","欢迎成为本站会员，请妥善保管自己的账号。");
             $this->ajaxReturn(1,"注册成功!");
         } else {
             $this->ajaxReturn(0,"注册失败!");
         }
     }

	//渠道统计
	public function channelStatistics($puser){
		$tg_admin = M('tg_admin')->where(array('UserName'=>$puser,'Status'=>'1','IsDel'=>'0'))->find();
		if($tg_admin){
			$ip = get_client_ip();
			$time = date('Y-m-d');
			$mode = M('channel_statistics')->where(array('channelName'=>$tg_admin['Name'],'createDate'=>$time))->find();
			$b = getTodayIp($time,$ip.','.$puser);
			if($mode){
				$PV = $mode['PV'] + 1;
				$UV = $mode['UV'];
				if(!$b){
					$UV++;
				}
				$data = array(
					'PV' => $PV,
					'UV' => $UV,
					'CPA' =>$tg_admin['price'],
				);
				M(self::T_CHANNEL)->where(array('ID'=>$mode['ID']))->save($data);
			}else{
				$data = array(
					'channelName' => $tg_admin['Name'],
					'channelId' => $tg_admin['ID'],
					'PV' => 1,
					'UV' => 1,
					'createDate' => $time,
					'CPA' =>$tg_admin['price'],
				);
				M(self::T_CHANNEL)->add($data);
			}
		}
		
	 }
	 //渠道统计
	public function channelStatisticsReg($puser){
		if($puser){	
			$tg_admin = M('tg_admin')->where(array('UserName'=>$puser,'Status'=>'1','IsDel'=>'0'))->find();
			$time = date('Y-m-d');
			$mode = M('channel_statistics')->where(array('channelName'=>$tg_admin['Name'],'createDate'=>$time))->find();
			if($mode){
				$registerNum = $mode['registerNum'] + 1;
				$SumMoney = $mode['SumMoney'];
				$CPA = $mode['CPA'];
				$data = array(
					'registerNum' => $registerNum,
					'SumMoney' =>$SumMoney + $CPA,
				);
				M(self::T_CHANNEL)->where(array('ID'=>$mode['ID']))->save($data);
			}
		}else{
			$time = date('Y-m-d');
			$mode = M('channel_statistics')->where(array('channelName'=>'自然流量','createDate'=>$time))->find();
			if($mode){
				$registerNum = $mode['registerNum'] + 1;
				$data = array(
					'registerNum' => $registerNum
				);
				M('channel_statistics')->where(array('ID'=>$mode['ID']))->save($data);
			}else{
				$data = array(
					'registerNum' => 1,
					'channelName' => '自然流量',
					'createDate' => $time,
				);
				M('channel_statistics')->add($data);
			}
		}
	 }

     /**
      * 推荐人以及三级分销
      * $Mtype       int     推荐人的会员类型 1.普通会员 2.渠道代理 3.团队代理 4.城市经理
      * $Referee     int     推荐人ID
      * $Mobile      string  被推荐人的手机号码
      */
     public function refer($Mtype,$Referee,$Mobile){
         //推荐人（不同类型的会员）奖励
         switch ($Mtype){
             case 1:$Balance=$GLOBALS['BasicInfo']['Tfriendsy1'];break;
             case 2:$Balance=$GLOBALS['BasicInfo']['Tfriendsy2'];break;
             case 3:$Balance=$GLOBALS['BasicInfo']['Tfriendsy3'];break;
             case 4:$Balance=$GLOBALS['BasicInfo']['Tfriendsy4'];break;
         }
         M('mem_info')->where(array('ID'=>$Referee))->setInc('Balance',$Balance);
         $CurrentBalance=M('mem_info')->where(array('ID'=>$Referee))->getField('Balance');
         $data_Balance=array(
             'Type'=>0,
             'SruType'=>1,
             'Amount'=>$Balance,
             'Description'=>$Mobile,
             'CurrentBalance'=>$CurrentBalance,
             'UserID'=>$Referee,
             'Mtype'=>1,
             'UpdateTime'=>date('Y-m-d H:i:s'),
             'TradeCode'=>date('YmdHis').rand(10000,99999),
         );
         $res=M('mem_balances')->add($data_Balance);
         if($res){
             member_sms($Referee,$Type=1,"推荐会员获取奖励","恭喜您获得了".$Balance."元，请多多推广我们哦！");
         }
         //多级分佣
         shareOrderMoneyRecord($Balance,$Referee,1,$data_Balance['Description'],"",0);
     }

	//获取下载地址
	public function getdownloadurl(){
		$model = M('sys_basicinfo')->find();
		$array['android'] = $model['Androidurl'];
		$array['ios'] = $model['IOSurl'];
		$this->ajaxReturn(1,'成功',$array);
	}
 }
 