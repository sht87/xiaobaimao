<?php
namespace Api\Controller\Center;
use Think\Controller\RestController;
use XBCommon\CacheData;
use XBCommon\XBCache;
use XBCommon;

class RegisterController extends RestController{
    const T_TABLE='mem_info';
    const T_TIMESTAMP='sys_timestamp';
    const T_MOBILE_CODE='code';
    const T_SYS_BASICINFO='sys_basicinfo';


    /**
     * @功能说明: 会员注册接口
     * @传输格式: post
     * @提交网址: /center/Register/reg
     * @提交信息：{"client":"android","package":"android.ceshi","ver":"v1.1","Mobile":"18355195990","Code":"888410","Password":"123456","Rid":"推荐人id"}
     * @返回信息: {'result'=>1,'message'=>'恭喜您，注册成功！'}
     */
    public function reg(){
        if(!IS_POST){
    		exit(json_encode(array('result'=>0,'message'=>'提交的方式不正确!')));
    	}
        $json_data =get_json_data();
        //手机号码格式不正确
        if(!is_mobile($json_data['Mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        //请使用英文与数字组合且不低于6位
        if(!is_password($json_data['Password'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,密码格式不正确,请以英文字母开头，与数字的组合且不低于6位!')));
        }

        //判断验证码是否合法
        $mdb=M(self::T_MOBILE_CODE);
        $code=$mdb->where(array('Name'=>$json_data['Mobile'],'Code'=>$json_data['Code']))->order('UpdateTime ASC')->find();

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

        //注册保存，查询是否已存在
        $db=M(self::T_TABLE);
        $IsExitMobile=$db->where(array('Mobile'=>$json_data['Mobile'],'IsDel'=>0))->find();

        if($IsExitMobile){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,此用户已存在!')));
        }
        $data=array(
            'TrueName'=>substr($json_data['Mobile'],-4),
            'UserName'=>$json_data['Mobile'],
            'Mobile'=>$json_data['Mobile'],
            'Tjcode'=>$json_data['Mobile'],
            'Password'=>md5($json_data['Password']),
            'RegTime'=>date("Y-m-d H:i:s"),
            'Mtype'=>1,
			'PhoneType'=>$json_data['client'],
        );

        //推荐人id
        if($json_data['Rid']){
            $exit_refer=M('mem_info')->where( array('ID'=>$json_data['Rid'],'IsDel'=>0) )->find();
            if( $exit_refer ){
                $data['Referee']=$json_data['Rid'];
            }
        }

        $result=$db->add($data);
        if(!$result){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,保存数据失败!','data'=>array() )));
        }

        $BasicInfo=M(self::T_SYS_BASICINFO)->find();
        if($result){
//            if($exit_refer){
//                if($BasicInfo['ReferCount']>0){  //推荐一定次数
//                    // 验证今日赠送是否超过限定次数
//                    $check_num=M('mem_info')->where( array('Referee'=>$json_data['Rid'],'RegTime'=>array('like',date('Y-m-d').'%')) )->count();
//                    if( $check_num <= $BasicInfo['ReferCount'] ){
//                        $this->refer($exit_refer['Mtype'],$json_data['Rid'],$json_data['Mobile']);
//                    }
//                }else{  //无限次推荐
//                    $this->refer($exit_refer['Mtype'],$json_data['Rid'],$json_data['Mobile']);
//                }
//            }
			self::channelStatisticsReg();
            M('code')->where(array("Name" => $json_data['Mobile']))->delete();
            member_sms($result,$type=1,"恭喜您注册成功","欢迎成为本站会员，请妥善保管自己的账号。");
            exit(json_encode(array('result'=>1,'message'=>"注册成功,请登录,完善个人资料信息")));
        } else {
            exit(json_encode(array('result'=>0,'message'=>"注册失败，请重新注册!")));
        }
    }

	public function getxy(){
		$simplepage1=M('sys_simplepage')->where(array('ID'=>5))->find();
		$simplepage2=M('sys_simplepage')->where(array('ID'=>8))->find();
		$simplepage3=M('sys_simplepage')->where(array('ID'=>9))->find();
		$arr = array();
		$arr['enable'] = $simplepage1['enable'];
		$arr['Title'] = $simplepage1['Title'];
		$arr2 = array();
		$arr2['enable'] = $simplepage2['enable'];
		$arr2['Title'] = $simplepage2['Title'];
		$arr3 = array();
		$arr3['enable'] = $simplepage3['enable'];
		$arr3['Title'] = $simplepage3['Title'];
		$ayy = array();
		array_push($ayy,$arr);
		array_push($ayy,$arr2);
		array_push($ayy,$arr3);

		$data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>$ayy
            );
        exit(json_encode($data));
	}

	public function channelStatisticsReg(){
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
    /**
     * @功能说明: 忘记密码第一步
     * @传输格式: get提交
     * @提交网址: /center/register/forgetOne
     * @提交信息：client=android&package=ceshi.app&ver=v1.1&Mobile=17602186118&Code=2
     * @返回信息: {'result'=>1,'message'=>'恭喜您，重置密码成功！'}
     */
    public function forgetOne(){

        $json_data = I('get.');

        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,重置密码失败!')));
        }
        //手机号码格式不正确
        if(!is_mobile($json_data['Mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        //验证码不能为空
        if(!$json_data['Code']){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，验证码不能为空！')));
        }
        //判断验证码是否合法
        $mdb=M(self::T_MOBILE_CODE);
        $code=$mdb->where(array('Name'=>$json_data['Mobile'],'Code'=>$json_data['Code']))->find();

        if($code){
            //没有获取短信验证码
            exit(json_encode(array('result'=>1,'message'=>'恭喜您，可以重置密码了！','data'=>$json_data['Mobile'])));
        }else{
            exit(json_encode(array('result'=>0,'message'=>"很抱歉，输入的验证码不正确！")));
        }

    }

    /**
     * @功能说明: 忘记密码第二步
     * @传输格式: get提交
     * @提交网址: /center/register/forgetTwo
     * @提交信息：client=android&package=ceshi.app&ver=v1.1&Mobile=17602186118&Code=2
     * @返回信息: {'result'=>1,'message'=>'恭喜您，重置密码成功！'}
     */
    public function forgetTwo(){

        $json_data = I('get.');

        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,重置密码失败!')));
        }
        //手机号码格式不正确
        if(!is_mobile($json_data['Mobile'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,手机号码格式不正确!')));
        }
        //请使用英文与数字组合且不低于6位
        if(!is_password($json_data['pwd'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,新密码格式不正确,请以英文字母开头，与数字的组合且不低于6位!')));
        }

        $db=M(self::T_TABLE);
        $mem=$db->where(array('Mobile'=>$json_data['Mobile'],'Status'=>1,'IsDel'=>0))->find();

        if(!$mem){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,您没有修改的权限!')));
        }
        //重置密码
        $data=array(
            'Password'=>md5($json_data['pwd']),
            'UpdateTime'=>date('Y-m-d H:i:s')
        );
        $result=$db->where(array('ID'=>$mem['ID']))->save($data);

        if($result){
            M(self::T_MOBILE_CODE)->where(array('Name'=>$json_data['Mobile']))->delete();
            exit(json_encode(array('result'=>1,'message'=>'恭喜您,密码重置成功!')));
        }else{
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,保存数据失败!')));
        }

    }

    /**
     * 推荐人以及三级分销
     * $Mtype       int     推荐人的会员类型 1.普通会员 2.渠道代理 3.团队代理 4.城市经理
     * $Referee     int     推荐人ID
     * $Mobile      string  被推荐人的手机号码
     */
    public function refer($Mtype,$Referee,$Mobile){
        $BasicInfo=M(self::T_SYS_BASICINFO)->find();
        //推荐人（不同类型的会员）奖励
        switch ($Mtype){
            case 1:$Balance=$BasicInfo['Tfriendsy1'];break;
            case 2:$Balance=$BasicInfo['Tfriendsy2'];break;
            case 3:$Balance=$BasicInfo['Tfriendsy3'];break;
            case 4:$Balance=$BasicInfo['Tfriendsy4'];break;
        }
        M('mem_info')->where(array('ID'=>$Referee))->setInc('Balance',$Balance);
        $CurrentBalance=M('mem_info')->where(array('ID'=>$Referee))->getField('Balance');
        $data_Balance=array(
            'Type'=>0,
            'SruType'=>1,
            'Amount'=>$Balance,
            'Description'=>"您推荐了手机号为".substr_replace($Mobile,'****',3,4)."的新会员，我们赠送您".$Balance."金币",
            'CurrentBalance'=>$CurrentBalance,
            'UserID'=>$Referee,
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
}