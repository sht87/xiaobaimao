<?php
namespace Api\Controller\Center;
use Api\Controller\Core\BaseController;
use XBCommon\XBCache;

class MemberController extends BaseController  {

    const T_TABLE       ='mem_info';
    const T_TIMESTAMP   ='sys_timestamp';
    const T_MOBILE_CODE ='code';
    const T_MONEY       ='mem_money';
    const T_BALANCE     ='mem_balances';
    const T_LEVELS      ='mem_levels';
    const T_ITEMS       ='items';
    const T_RESULT      ='apply_listresult';
	const T_APPLYLIST      ='apply_list';
    const T_ZENXIN      ='zenxin_list';
    const T_PAGES       ='sys_simplepage';
	const T_PRODUCT       ='product_statistics';
	const T_CHANNEL       ='channel_statistics';



    /**
     * @功能说明: 手机号码和密码直接登录
     * @传输格式: get提交
     * @提交网址: /center/member/login
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&mobile=17602186118&token=2&ticksid=1&ticks=BE8C02977DB77AB35D4D476CF9D3AD
     * @返回信息: {'result'=>1,'message'=>'恭喜您，登录成功！','data'} 返回加密后的私有token!
     */
    public function login(){

        $json_data = I('get.');
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,登录失败!')));
        }
        if(empty($json_data['ticksid'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，未能获取正确的时间戳标识!')));
        }
        //时间戳的判断
        $db=M(self::T_TIMESTAMP);
        $Val=$db->where(array('ID'=>$json_data['ticksid']))->getField('Val'); //从数据库获取
        if(empty($Val)){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，时间戳不存在!')));
        }
        if($json_data['ticks']!=$Val){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，传递的时间戳不正确!')));
        }
        if($json_data['mobile']==''){
            exit(json_encode(array('result'=>0,'message'=>'请输入手机号')));
        }
        
        //登录判断
        $db=M(self::T_TABLE);
        $where=array(
            'Mobile'=>$json_data['mobile'],
            'IsDel' => 0,
        );
        $memInfo=$db->where($where)->find();
        if(empty($memInfo)){
            $memInfo=array(
				'TrueName'=>substr($json_data['mobile'],-4),
				'UserName'=>$json_data['mobile'],
				'Mobile'=>$json_data['mobile'],
				'Tjcode'=>$json_data['mobile'],
				'Password'=>md5("Aa123456"),
				'RegTime'=>date("Y-m-d H:i:s"),
				'Mtype'=>1,
				'PhoneType'=>$json_data['client'],
                'Status' => 1,
			);
            $memInfo['ID'] = $db->add($memInfo);
			//self::channelStatisticsReg();
        }

        if($memInfo['Status']==0){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，该会员已被禁用，无法登录!')));
        }

        $client = $json_data['client'];
        $ticksid = $json_data['ticksid'];

		$linkType = $json_data['loginType'];
		if(empty($linkType)){
			$linkType = 2;
		}
		
		if($linkType==1){
			$psd = $json_data['code'];
			$name = substr(md5(md5($memInfo['Mobile'])),2,30);
			$key = 'XB'.substr(md5($name.$Val),2,30);
			$iv = 'XB'.substr(md5(md5($psd).$ticksid),2,14);
			$token = substr(md5($name.$Val),0,30).substr(md5($client.$psd.$ticksid),2,30);
			//判断验证码是否合法
			$mdb=M(self::T_MOBILE_CODE);
			$code=$mdb->where(array('Name'=>$json_data['mobile'],'Code'=>$json_data['code']))->order('UpdateTime ASC')->find();

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
		}
		if($linkType==2){
			$psd = $memInfo['Password'];
			$name = substr(md5(md5($memInfo['Mobile'])),2,30);
			$key = 'XB'.substr(md5($name.$Val),2,30);
			$iv = 'XB'.substr(md5($psd.$ticksid),2,14);
			$token = substr(md5($name.$Val),0,30).substr(md5($client.$psd.$ticksid),2,30);

			if($token <> $json_data['token']){
				exit(json_encode(array('result'=>0,'message'=>'抱歉，账号或密码不正确!','data'=>array('KEY'=>$key,'IV'=>$iv,'Token'=>$token) )));
			}
		}
		if($memInfo['alive']==0){
			self::channelStatisticsAlive($memInfo['TgadminID']);
		}
        //删除旧的token,避免多个手机同时登陆-----start
        if($memInfo['Token']){
          //  XBCache::Remove($memInfo['Token']);
        }
        //删除旧的token,避免多个手机同时登陆-----end
        $data=array(
            'LoginClient'=>$client,
            'LastLoginTime'=>$memInfo['LoginTime'],
            'LoginTime'=>date('Y-m-d H:i:s'),
            'LastLoginIP'=>$memInfo['LoginIP'],
            'LoginIP'=>get_client_ip(),
            'LastIpCity'=>$memInfo['IpCity'],
            'IpCity'=>ip_to_address(get_client_ip()),
            'Token'=> $token,  //会员token
            'KEY' => $key,  //会员私有密钥
            'IV' => $iv,  //会员私有向量\
            'PhoneType'=>$client,
			'alive'=>1
        );
        if($json_data['DeviceToken']){
            $data['DeviceToken']=$json_data['DeviceToken'];
        }
        $result=$db->where(array('ID'=>$memInfo['ID']))->save($data);
		if($linkType==1){
			M('code')->where(array("Name" => $json_data['mobile']))->delete();
		}
        //登录成功后，更新登录信息
        if($result){
            $AppInfo['ID']=$memInfo['ID'];               //会员ID
            $AppInfo['UserName']=$memInfo['UserName'];   //会员用户名
            $AppInfo['Mobile']=$memInfo['Mobile'];       //会员手机号
            $AppInfo['Mtype']=$memInfo['Mtype'];         //会员类型 普通 渠道代理 团队经理  城市经理
            $AppInfo['Token']=$token;                   //会员token
            $AppInfo['KEY']=$key;                       //会员私有密钥
            $AppInfo['IV']=$iv;                         //会员私有向量
            $AppInfo['TimeOut']=date('Y-m-d H:i:s');    //会员登录过期时间
			XBCache::Insert($token,$AppInfo,24 * 3600 * 7);
            //返回私有向量和密钥加密后的数据
            $output=array(
                'result'=>1,
                'message'=>'success',
                'data'=>array('KEY'=>$key,'IV'=>$iv,'Token'=>$token,'ID'=>$memInfo['ID'],'Mtype'=>$memInfo['Mtype'])
            );
            exit(json_encode($output));
        }else{
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，更新登录信息错误!')));
        }
    }

    /**
     * @功能说明: 获取用户基本信息
     * @传输格式: 私有token,无提交，密文返回
     * @提交网址: /center/member/info
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1"}
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function info(){
        $db=M(self::T_TABLE);
        $UserID=get_login_info('ID');
        $mem=$db->where(array('ID'=>$UserID,'Status'=>1,'IsDel'=>0))->find();
        if(empty($mem)){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,未查找到相关的数据!')));
        }

        //通用信息
        $data['ID']=$mem['ID'];
        $data['TrueName']=$mem['TrueName'];
        $data['Mtype']=$mem['Mtype'];
        if($mem['Mtype']==1){
            $data['Rule']="普通会员";
        }else{
            $data['Rule']=M('mem_levels')->where(array('ID'=>$data['Mtype']-1))->getField('Name');
        }
        $data['Mobile'] =substr_replace($mem['Mobile'],"****",3,4);
        if($mem['HeadImg'] != NULL){
            $data['HeadImgVal']=$mem['HeadImg'];//'http://'.$_SERVER['HTTP_HOST'].$mem['HeadImg'];
        }else{
            $data['HeadImgVal']='http://'.$_SERVER['HTTP_HOST'].'/Public/images/photo.png';
        }

        //个人中心首页数据
        //已结算
        $account=M(self::T_MONEY)->field('Money')->where(array('Uid'=>$UserID,'Type'=>0,'IsAduit'=>2))->select();
        $Money=0;
        if($account){
            $chargeArr=array_column($account,'Money');
            for($i=0;$i<count($chargeArr);$i++){
                $Money+=$chargeArr[$i];
            }
            $data['Account']="￥".$Money."元";
        }else{
            $data['Account']="￥0.00元";
        }
        //总收入
        $income=M(self::T_BALANCE)->field('Amount')->where(array('UserID'=>$UserID,'Type'=>0,'SruType'=>array('neq','5')))->select();
        $earnMoney=0;
        if($income){
            $earArr=array_column($income,'Amount');
            for($i=0;$i<count($earArr);$i++){
                $earnMoney+=$earArr[$i];
            }
            $data['Income']="￥".$earnMoney."元";
        }else{
            $data['Income']="￥0.00元";
        }
        //可结算
        $data['Balance']=$mem['Balance']?"￥".$mem['Balance']."元":"￥0.00元";
        //客服电话
        $data['severTel']=$GLOBALS['BasicInfo']['Tel']?$GLOBALS['BasicInfo']['Tel']:'暂未添加';

        //基本信息页数据
        $data['CardNo']=$mem['CardNo'];
        $data['Tjcode']=$mem['Tjcode'];
     	$data['wechatKefu']=$GLOBALS['BasicInfo']['QQa'];
        $data['wechatQR']='http://'.$_SERVER['HTTP_HOST'].'/'.$GLOBALS['BasicInfo']['WeChatQR'];
        switch ($mem['Position']){
            case 0:$data['Position']="";break;
            case 1:$data['Position']="学生族";break;
            case 2:$data['Position']="上班族";break;
            case 3:$data['Position']="自主创业";break;
            case 4:$data['Position']="无业";break;
        }
        switch ($mem['UseTime']){
            case 1:$data['UseTime']="一年以下";break;
            case 2:$data['UseTime']="一年以上";break;
            case 3:$data['UseTime']="两年以上";break;
            case 4:$data['UseTime']="三年以上";break;
        }
        $data['IsCredit']=$mem['IsCredit']?"有":"无";
        if($mem['Referee']){
            $data['Referee']=$db->where(array('ID'=>$mem['Referee']))->getField('TrueName');
        }
        $output=array(
            'result'=>1,
            'message'=>'恭喜您，数据查询成功！',
            'data'=>encrypt_pkcs7(json_encode($data),get_login_info('KEY'),get_login_info('IV'))
        );
        exit(json_encode($output));
    }

    /**
     *@功能说明: 修改用户基本信息
     * @传输方式: 私有token,密文提交，明文返回
     * @提交网址: /center/member/modify
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"android","package":"android.ceshi","ver":"v1.1",
     *              "dynamic":{"TrueName":"真实姓名", "HeadImg":"头像路径","CardNo":"身份证号码", "Position":“身份ID”, "UseTime":"手机使用年限ID","IsCredit":"1有，0无信用卡"}}
     *             Position:1.学生族  2.上班族  3.自主创业  4.无业   UseTime:1.一年以下  2.一年以上  3.两年以上  4.三年以上
     * @返回信息: {'result'=>1,'message'=>'恭喜您,修改保存成功!'}
     */
    public function modify(){
        $para=get_json_data();
        //密文解密
        $json_data=json_decode(decrypt_pkcs7($para['dynamic'],get_login_info('KEY'),get_login_info('IV')),true);
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,修改基本信息失败!')));
        }

        if(empty($json_data['TrueName'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,请填写您的真实姓名!')));
        }
        if(empty($json_data['CardNo'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,请填写您的身份证号码!')));
        }
        if(!preg_match("/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/",$json_data['CardNo'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，身份证号码格式不正确')));
        }
        if($json_data['Position']!=0 && !in_array($json_data['Position'],array(1,2,3,4))){
            exit(json_encode(array('result'=>0,'message'=>"操作有误，请重新选择您的身份")));
        }
        if(!in_array($json_data['IsCredit'],array(1,0))){
            exit(json_encode(array('result'=>0,'message'=>"操作有误，请重新选择您是否有信用卡")));
        }
        if(!in_array($json_data['UseTime'],array(1,2,3,4))){
            exit(json_encode(array('result'=>0,'message'=>"操作有误，请重新选择手机使用年限")));
        }

        $data=array(
            'TrueName'=>$json_data['TrueName'],
            'CardNo'=>$json_data['CardNo'],
            //'HeadImg'=>$json_data['HeadImg'],
            'Position'=>$json_data['Position'],
            'IsCredit'=>$json_data['IsCredit'],
            'UseTime'=>$json_data['UseTime'],
            'UpdateTime'=>date("Y-m-d H:i:s")
        );
        $db=M(self::T_TABLE);
        $result=$db->where(array('ID'=>get_login_info('ID')))->save($data);
        if($result){
            exit(json_encode(array('result'=>1,'message'=>'恭喜您,修改保存成功!')));
        }else{
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,修改数据时保存失败!')));
        }
    }


    /**
     * @功能说明: 获取 代理信息
     * @传输格式: post
     * @提交网址: /Center/Member/getlevel
     * @提交信息：非josn form 表单 post方式提交
     *          array("client"=>"ios","package"=>"ios.ceshi","ver"=>"1.1","dynamic":{}) FILES  Multipart/form-data
     * @返回信息: {'result'=>1,'message'=>'请求成功！'}
     */
    public function getlevel(){
        $AgentInfo=M(self::T_LEVELS)->field('ID,Name,Price')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->limit(3)->select();
        if(!$AgentInfo){
            exit(json_encode(array('result' => 0, 'message' => '很抱歉,暂无可供查看的代理级别信息!')));
        }
        $output = array(
            'result' => 1,
            'message' => '恭喜您，数据查询成功！',
            'data' => $AgentInfo
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 分享推广
     * @传输方式: post
     * @提交网址: /center/member/share
     * @提交信息:  array("client"=>"ios","package"=>"ios.ceshi","ver"=>"1.1")
     * @提交信息说明:
     * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function share(){
        $UserID=get_login_info('ID');
        //校验是否有权限访问
        $Mtype=M('mem_info')->where(array('ID'=>$UserID))->getField('Mtype');
        $flag=true;//默认能分享
        if($Mtype=='1' && $GLOBALS['BasicInfo']['Isshare']=='2'){
            $flag=false;
        }
        if(!$flag){
            exit(json_encode(array('result'=>0,'message'=>'仅代理会员才能分享!')));
        }
        
        $pages=M(self::T_PAGES)->field('Title,Contents')->where(array('ID'=>6))->find();
        if(!$pages){
            exit(json_encode(array('result'=>0,'message'=>'没有更多数据！')));
        }
        $url='/Upload/qrcode/'.$UserID.'/'.$UserID."__shareqrcode.png";
        $url2="http://".$_SERVER['HTTP_HOST'].'/Upload/qrcode/'.$UserID.'/'.$UserID."__shareqrcode.png";
        if(!is_file('./Upload/qrcode/'.$UserID.'/'.$UserID."__shareqrcode.png")){
            $return=PrintQrcodeShare($UserID);
        }
        $shareurl=get_basic_info("SystemDomain").'/Register/index?ui='.$UserID;
        $info['url']=$url;
        $info['url2']=$url2;
        $info['shareurl']=$shareurl;
        exit(json_encode(array('result'=>1,'message'=>'查询成功','data'=>$info)));
    }


    /**
     * @功能说明: 购买代理支付
     * @传输方式: 私有token,密文提交，明文返回
     * @提交网址: /center/member/payagent
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1",
     *                  "dynamic":{"paytpe":"支付方式","id":"代理级别ID"}}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function payagent(){
        //获取数据流
        $para=get_json_data();
        $UserID=get_login_info('ID');
        //密文解密
        $json_data=json_decode(decrypt_pkcs7($para['dynamic'],get_login_info('KEY'),get_login_info('IV')),true);
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,支付失败!')));
        }

        //数据验证
        $meminfo=M(self::T_TABLE)->where(array('ID'=>$UserID))->find();
        // if($meminfo['Mtype']!='1'){
        //     exit(json_encode(array('result'=>0,'message'=>'您已经是代理,不能购买了!','data'=>array())));
        // }
        $levelinfo=M(self::T_LEVELS)->where(array('ID'=>$json_data['id'],'Status'=>'1','IsDel'=>'0'))->find();
        if(!$levelinfo){
            exit(json_encode(array('result'=>0,'message'=>'参数id错误','data'=>array())));
        }
        if(!in_array($json_data['paytype'],array('1','2','3','4','5'))){
            exit(json_encode(array('result'=>0,'message'=>'支付方式不正确','data'=>array())));
        }

        //如果已经是购买了会员,就是补差价升级会员
        $curr_price='';//当前级别价格
        if($meminfo['Mtype']>1){
            //校验,不可重复购买当前级别的会员,只能购买高级会员
            if(($meminfo['Mtype']-1)>=$json_data['id']){
                exit(json_encode(array('result'=>0,'message'=>'请购买更高级的代理!','data'=>array())));
            }
            $AgentInfo2=M('mem_levels')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->limit(3)->select();
            //代理价格
            $agentprice=array();
            foreach($AgentInfo2 as $k=>$v){
                $agentprice[$v['ID']][]=$v;
            }
            if($meminfo['Mtype']=='2'){
                //渠道经理
                $curr_price=$agentprice[1][0]['Price'];
            }elseif($meminfo['Mtype']=='3'){
                //团队经理
                $curr_price=$agentprice[2][0]['Price'];
            }
        }
        $levelinfo=M('mem_levels')->where(array('ID'=>$json_data['id'],'Status'=>'1','IsDel'=>'0'))->find();
        if(!$levelinfo){
            exit(json_encode(array('result'=>0,'message'=>'购买的代理信息不存在!','data'=>array())));
        }
        //算出实际支付的金额
        if($curr_price){
            $levelinfo['Price']=$levelinfo['Price']-$curr_price;
        }

        //选择支付方式
        if($json_data['paytype']=='3'){    //余额支付
            $paymoney=$levelinfo['Price'];
            if($meminfo['Balance']<$levelinfo['Price']){
                exit(json_encode(array('result'=>0,'message'=>'余额不足,请选择其他支付方式!','data'=>array())));
            }

            $model=M();
            $model->startTrans();
            $result=$model->table('xb_mem_info')->where(array('ID'=>$UserID))->setDec('Balance',$levelinfo['Price']);
            if(!$result){
                $model->rollback();
                exit(json_encode(array('result'=>0,'message'=>'余额支付失败','data'=>array())));
            }
            //添加代理购买记录表
            $insetdata=array(
                'UserID'=>$UserID,
                'LevelID'=>$levelinfo['ID'],
                'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                'OrderAmount'=>$levelinfo['Price'],
                'OrderTime'=>date('Y-m-d H:i:s'),
                'Status'=>'2',
                'PayType'=>'3',
                'PayTime'=>date('Y-m-d H:i:s'),
            );
            $rest=$model->table('xb_mem_buydaili')->add($insetdata);
            //修改会员表
            $mtype='';
            if($levelinfo['ID']=='1'){
                $mtype='2';
            }elseif($levelinfo['ID']=='2'){
                $mtype='3';
            }elseif($levelinfo['ID']=='3'){
                $mtype='4';
            }
            $memrestul=$model->table('xb_mem_info')->where(array('ID'=>$UserID))->save(array('Mtype'=>$mtype));
            if(!$memrestul || !$rest){
                $model->rollback();
                exit(json_encode(array('result'=>0,'message'=>'余额支付失败','data'=>array())));
            }

            $model->commit();
            //支付成功,记录余额消费明细
            $desc='购买代理支付,订单号:'.$insetdata['OrderSn'];
            balancerecord('1',$paymoney,$UserID,'','',$desc,'','');

            //三级分销 分佣
            //shareOrderMoneyRecord($paymoney,$UserID,$SruType,$Description,$Intro,$oid);
            shareOrderMoneyRecord($paymoney,$UserID,'1',$meminfo['Mobile'],'',0);

        }elseif($json_data['paytype']=='1'){
            //添加代理购买记录表
            $insetdata=array(
                'UserID'=>$UserID,
                'LevelID'=>$levelinfo['ID'],
                'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                'OrderAmount'=>$levelinfo['Price'],
                'OrderTime'=>date('Y-m-d H:i:s'),
                'Status'=>'1',
                'PayType'=>'1',
            );
            $rest=M('mem_buydaili')->add($insetdata);

        }elseif($json_data['paytype']=='2'){
            //支付宝支付
            //添加代理购买记录表
            $insetdata=array(
                'UserID'=>$UserID,
                'LevelID'=>$levelinfo['ID'],
                'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                'OrderAmount'=>$levelinfo['Price'],
                'OrderTime'=>date('Y-m-d H:i:s'),
                'Status'=>'1',
                'PayType'=>'2',
            );
            $rest=M('mem_buydaili')->add($insetdata);
        } elseif($json_data['paytype'] == '5') {
            //添加代理购买记录表
            $insetdata=array(
                'UserID'=>$UserID,
                'LevelID'=>$levelinfo['ID'],
                'OrderSn'=>date('ymd').rand(1,9).date('His').rand(111,999),
                'OrderAmount'=>$levelinfo['Price'],
                'OrderTime'=>date('Y-m-d H:i:s'),
                'Status'=>'1',
                'PayType'=>'5',
            );
            $rest=M('mem_buydaili')->add($insetdata);
        }
        /* 支付结束***************************/
        $rest=array(
            'id'=>$rest,
            'oid'=>$insetdata['OrderSn'],
            );
        $data=array(
            'result'=>1,
            'message'=>'购买代理成功',
            'data'=>$rest,
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 我的消息
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/mynews
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1",}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function mynews(){
        $para=get_json_data();
        $UserID=get_login_info('ID');
        $userInfo = M('mem_info')->where(array('ID'=>$UserID))->find();
        $where['SendTime'] = array('egt', $userInfo['RegTime']);
        $where['UserID']=array('in',array(0,$UserID));
        $where['Status']=1;
        $List1=M('mem_message')->where(array($where,'Type'=>0))->order('SendTime desc')->select();
        $List2=M('mem_message')->where(array($where,'Type'=>1))->order('SendTime desc')->select();
        $List=array();
        array_push($List,$List1,$List2);
        if(!$List){
            exit(json_encode(array('result'=>0,'message'=>'暂无可供查看的消息')));
        }
        $output=array(
            'result'=>1,
            'message'=>'获取我的消息成功',
            'data'=>$List,
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 把消息标记为已读状态
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/setreads
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","type":"1"}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function setreads(){
        $para=get_json_data();
        if(!in_array($para['type'], array('1','0'))){
            exit(json_encode(array('result'=>0,'message'=>'参数提交有误!')));
        }

        $UserID=get_login_info('ID');
        $where['UserID']=array('in',array(0,$UserID));
        $where['Status']=array('eq','1');
        $where['Type']=array('eq',$para['type']);
        $msgList=M('mem_message')->where($where)->order('SendTime desc')->select();
        if($msgList){
            foreach($msgList as $key=>$val){
                $exit='';
                $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$UserID))->find();
                if(!$exit){
                    $msgList[$key]['IsRead']=0;//未读
                    //把此记录标记为已读
                    $sdata=array();
                    $sdata['MID']=$val['ID'];
                    $sdata['UID']=$UserID;
                    $sdata['Time']=date('Y-m-d H:i:s');
                    M('readmessage')->add($sdata);
                }
            }
        }
        $output=array(
            'result'=>1,
            'message'=>'处理成功!',
            'data'=>'',
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 判断现在是否有未读的消息
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/isnoreadmsg
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1"}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function isnoreadmsg(){
        $UserID=get_login_info('ID');
        $where['UserID']=array('in',array(0,$UserID));
        $where['Status']=array('eq','1');
        $List1=M('mem_message')->where(array($where,'Type'=>0))->order('SendTime desc')->select();//系统消息
        $List2=M('mem_message')->where(array($where,'Type'=>1))->order('SendTime desc')->select();//通知消息
        $xtmsg=false;
        $tzmsg=false;
        if($List1){
            foreach($List1 as $key=>$val){
                $exit='';
                $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$UserID))->find();
                if(!$exit){
                    //未读
                    $xtmsg=true;break;
                }
            }
        }
        if($List2){
            foreach($List2 as $key=>$val){
                $exit='';
                $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$UserID))->find();
                if(!$exit){
                    //未读
                    $tzmsg=true;break;
                }
            }
        }
        $rest=array(
            'xtmsg'=>$xtmsg,
            'tzmsg'=>$tzmsg,
            );
        $output=array(
            'result'=>1,
            'message'=>'处理成功!',
            'data'=>$rest,
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 退出操作
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/layout
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1"}
     * @返回信息: {'result'=>1,'message'=>'退出成功!'}
     */
    public function layout(){
        $para=get_json_data(); //接收参数
        if(get_login_info('Token')!=$para['token']){
            exit(json_encode(array('result'=>0,'message'=>'已经退出登录!')));
        }
        $data=array(
            'Key'=>null,
            'IV'=>null,
            'Token'=>null,
        );
        $result=M(self::T_TABLE)->where(array('ID'=>get_login_info('ID')))->save($data);
        if(!$result){
            //exit(json_encode(array('result'=>0,'message'=>'退出时，清空token失败!')));
        }

        //XBCache::Remove($para['token']);
        //退出成功
        exit(json_encode(array('result'=>1,'message'=>'退出成功!')));

    }

    /**
     * @功能说明: 修改密码
     * @传输方式: 私有token,密文提交，明文返回
     * @提交网址: /center/member/change_psd
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1",
     *                  "dynamic":{"oldpwd":"原密码","newpwd":"新密码","surepwd":"确认新密码"}}
     * @返回信息: {'result'=>1,'message'=>'登录密码修改成功!'}
     */
    public function change_psd(){
        $para=get_json_data();//接收参数
        $db=M(self::T_TABLE);
        $UserID=get_login_info('ID');
        $mem=$db->where(array('ID'=>$UserID))->find();
        if(!$mem){
            exit(json_encode(array('result'=>0,'message'=>'数据有误，无法修改密码!')));
        }
        //解密操作
        $json_data=json_decode(decrypt_pkcs7($para['dynamic'],get_login_info('KEY'),get_login_info('IV')),true);
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,修改密码失败!')));
        }

        if(empty($json_data['oldpwd']) || empty($json_data['newpwd']) || empty($json_data['surepwd'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,原密码，新密码和确认新密码都不能为空!')));
        }
        //原密码不正确
        if(md5($json_data['oldpwd']) != $mem['Password']){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,原密码不正确!')));
        }
        //新密码必须包含英文和数字
        if(!is_password($json_data['newpwd'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,新密码格式不正确,请以英文字母开头，与数字的组合且不低于6位!!')));
        }
        //原密码和新密码不能相同
        if($json_data['oldpwd']==$json_data['newpwd']){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,原密码和新密码不能相同!')));
        }
        //新密码和确认密码不一致
        if($json_data['newpwd']!=$json_data['surepwd']){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,新密码和确认密码不一致!')));
        }

        //保存密码
        $data=array(
            'Password'=>md5($json_data['newpwd']),
            'UpdateTime'=>date('Y-m-d H:i:s')
        );
        $result=$db->where(array('ID'=>$UserID))->save($data);
        if($result){
            member_sms($UserID,1,"修改密码","您于".$data['UpdateTime']."已成功修改了您的密码，请妥善保管");
            exit(json_encode(array('result'=>1,'message'=>'登录密码修改成功!')));
        }else{
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,更新密码失败!')));
        }
    }
    
    
    /* ********以下是<<< 贷款分销 >>>模块的接口**************************************************************/

    /**
     * @功能说明: 获取 借贷产品推广 信息
     * @提交网址: /center/member/getpromote
     * @传输方式: 私有token,明文提交，明文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","id":"1"}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function getpromote(){
        //获取数据流
        $para = get_json_data();

        $iteminfo=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo,Logurl,PartType,FeeIntro,BaseFee,StepFee,StepBase,StepInc1,StepInc2,StepUnit,StepIntro,AccountType')->where(array('ID'=>$para['id'],'IsDel'=>'0'))->find();
        if(empty($iteminfo)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，该借贷产品不存在',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        if($iteminfo['PartType']){
            $iteminfo['PartType']=unserialize($iteminfo['PartType']);
        }else{
            $iteminfo['PartType']=array();
        }
        if($iteminfo['FeeIntro']){
            $iteminfo['FeeIntro']=unserialize($iteminfo['FeeIntro']);
        }else{
            $iteminfo['FeeIntro']=array();
        }
        if($iteminfo['StepIntro']){
            $iteminfo['StepIntro']=unserialize($iteminfo['StepIntro']);
        }else{
            $iteminfo['StepIntro']=array();
        }
        if($iteminfo['AccountType']){
            $iteminfo['AccountType']=unserialize($iteminfo['AccountType']);
        }else{
            $iteminfo['AccountType']=array();
        }
        if($iteminfo['StepInc2'] && $iteminfo['StepBase'] && $iteminfo['StepInc1']){
            $iteminfo['AccountFee1']=number_format($iteminfo['StepBase']+$iteminfo['StepInc1'],2,'.','');
            $iteminfo['AccountFee2']=number_format($iteminfo['StepBase']+$iteminfo['StepInc2'],2,'.','');
        }
        //排行榜
        $dates=date('Y-m',strtotime('-1 month'));//上个月
        $dateArr=explode('-',$dates);
        $iteminfo['Dates']=$dateArr[0].'年'.$dateArr[1].'月';
        $iteminfo['Logurl']='http://'.$_SERVER['HTTP_HOST'].$iteminfo['Logurl'];
        $List['iteminfo']=$iteminfo;
        $List['lists']=array();

        $where['GoodsNo']=array('eq',$iteminfo['GoodsNo']);
        $where["DATE_FORMAT(FROM_UNIXTIME(Opentime),'%Y-%m')"]=array('eq',$dates);
        $lists=M(self::T_RESULT)->field('ID,Mobile,Money')->where($where)->order('Money desc')->limit(10)->select();
        if($lists){
            foreach($lists as $k=>$v){
                $lists[$k]['Mobile']=substr_replace($lists[$k]['Mobile'],'****',3,4);
                $lists[$k]['Money']=$v['Money'].'元';
            }
        }else{
            $data=array(
                'result'=>1,
                'message'=>'抱歉，上个月还没有会员贷款',
                'data'=>$List
            );
            exit(json_encode($data));
        }

        $List['lists']=$lists;
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 推广海报 信息
     * @提交网址: /center/member/getposter
     * @传输方式: 私有token,明文提交，明文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","id":"产品ID"}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function getposter(){
        //获取数据流
        $para = get_json_data();
        $UserID=get_login_info('ID');
        $iteminfo=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo,Openurl,ZsUrl1,ZsUrl2,ZsUrl3')->where(array('ID'=>$para['id'],'IsDel'=>'0'))->find();

        if(empty($iteminfo)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的贷款条件',
                'data'=>array()
            );
            exit(json_encode($data));
        }

        if($iteminfo['ZsUrl1']){
            $iteminfo['ZsUrl1']='/Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl1.png";
            if(!is_file('./Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl1.png")){
                PrintQrcode2($UserID,$para['id'],'ZsUrl1');
            }
            $iteminfo['ZsUrl1']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl1'];
        }
        if($iteminfo['ZsUrl2']){
            $iteminfo['ZsUrl2']='/Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl2.png";
            if(!is_file('./Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl2.png")){
                PrintQrcode2($UserID,$para['id'],'ZsUrl2');
            }
            $iteminfo['ZsUrl2']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl2'];
        }
        if($iteminfo['ZsUrl3']){
            $iteminfo['ZsUrl3']='/Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl3.png";
            if(!is_file('./Upload/qrcode/'.$UserID.'/'.'item_'.$para['id'].'/'."ZsUrl3.png")){
                PrintQrcode2($UserID,$para['id'],'ZsUrl3');
            }
            $iteminfo['ZsUrl3']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl3'];
        }
        if($iteminfo['Itype']=='1'){
            //平台网贷
            $iteminfo['shareurl']=get_basic_info('SystemDomain')."/Daibeishop/detail?uid=".$UserID.'&id='.$para['id'];
        }elseif($iteminfo['Itype']=='2'){
            //信用卡贷
            $iteminfo['shareurl']=get_basic_info('SystemDomain')."/Daibeishop/cdetail?uid=".$UserID.'&id='.$para['id'];
        }

        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$iteminfo
        );
        exit(json_encode($data));

    }


    /**
     * @功能说明: 获取 我的钱包 信息
     * @提交网址: /center/member/getwallet
     * @传输方式: 私有token,明文提交，密文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","page":"0","rows":"10"}
     *              "page":"当前页码数，默认0","rows":"每页展示数据数量，默认10"
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function getwallet(){
        //获取数据流
        $para = get_json_data();
        $UserID=get_login_info('ID');

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;
        //统计收入总额
        $income=M(self::T_BALANCE)->where(array('Type'=>'0','UserID'=>$UserID,'SruType'=>array('neq','5')))->Sum('Amount');
        if(!$income){
            $income='0.00';
        }
        $balances=M(self::T_TABLE)->where(array('ID'=>$UserID))->getField('Balance');
        $List['income']=$income;
        $List['balances']=$balances;
        $List['cost']=$GLOBALS['BasicInfo']['Cost'];
        $List['info']=array();

        $where['Uid']=array('eq',$UserID);
        $where['Type']=array('eq','0');
        $where['IsAduit']=array('eq','2');
        $where['IsDel']=array('eq','0');
        //分页
        $info=M(self::T_MONEY)->field('ID,Money,CurlMoney,AddTime')->where($where)->limit($page*$rows,$rows)->order('ID desc')->select();
        if(empty($info)){
            $data=array(
                'result'=>1,
                'message'=>'抱歉，暂没有可展示的提现列表',
                'data'=>encrypt_pkcs7(json_encode($List),get_login_info('KEY'),get_login_info('IV'))
            );
            exit(json_encode($data));
        }

        $List['info']=$info;
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>encrypt_pkcs7(json_encode($List),get_login_info('KEY'),get_login_info('IV'))
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 提现申请
     * @提交网址: /center/member/draw
     * @传输方式: 私有token,密文提交，明文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","dynamic":{"type":"1","card":"10086","money":"20","HolderName":"持卡人姓名","CardNo2":"银行卡号","BankName":"开户行名"}}
     *              "type":"提现账号类型  1银行卡  2支付宝"    "card":"提现账号"    "money":"取款金额，至少20元"
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function draw(){
        //获取数据流
        $para = get_json_data();
        //验证用户是否登录并解密
        $json_data = json_decode(decrypt_pkcs7($para['dynamic'], get_login_info('KEY'), get_login_info('IV')), true);
        if ($json_data == false) {
            exit(json_encode(array('result' => 0, 'message' => '很抱歉,提交的数据非法,无法提现!')));
        }
        $UserID=get_login_info('ID');
        if(!in_array($json_data['type'],array(1,2))){
            exit(json_encode(array('result'=>0,'message'=>"抱歉，请选择正确的提现账户类型")));
        }
        if($json_data['type']=='1'){
            //银行卡
            if(empty($json_data['HolderName'])){
                exit(json_encode(array('result'=>0,'message'=>"请填写持卡人姓名")));
            }
            if(empty($json_data['CardNo2'])){
                exit(json_encode(array('result'=>0,'message'=>"请填写银行卡号")));
            }
            if(empty($json_data['BankName'])){
                exit(json_encode(array('result'=>0,'message'=>"请填写开户行名")));
            }
            $typename='银行卡';
        }elseif($json_data['type']=='2'){
            //支付宝
            if(empty($json_data['card'])){
                exit(json_encode(array('result'=>0,'message'=>"请填写您的提现账号")));
            }
            $typename='支付宝';
        }
        if(empty($json_data['money'])){
            exit(json_encode(array('result'=>0,'message'=>"请填写您的提现金额")));
        }
        if($json_data['money']<20){
            exit(json_encode(array('result'=>0,'message'=>"最少提现20元")));
        }
        //算出手续费
        $Charge=($GLOBALS['BasicInfo']['Cost']/100)*$json_data['money'];
        $Charge=round($Charge,2);
        $dec_money=$Charge+$json_data['money'];

        $balances=M(self::T_TABLE)->where(array('ID'=>$UserID))->getField('Balance');
        if($balances<$dec_money){
            exit(json_encode(array('result'=>0,'message'=>"余额不足,不能提现")));
        }

        //申请提现
        $result=M(self::T_TABLE)->where(array('ID'=>$UserID))->setDec('Balance',$dec_money);
        if(!$result){
            exit(json_encode(array('result'=>0,'message'=>"抱歉，提现失败")));
        }

        //提现记录
        $CurlMoney=M(self::T_TABLE)->where(array('ID'=>$UserID))->getField('Balance');
        $data=array(
            'Uid'=>$UserID,
            'Type'=>0,
            'CurlMoney'=>$CurlMoney,
            'Money'=>$json_data['money'],
            'CardID'=>$typename,
            'Charge'=>$Charge,
            'AddTime'=>date("Y-m-d H:i:s")
        );
        if($json_data['type']=='1'){
            //银行卡
            $data['HolderName']=$json_data['HolderName'];
            $data['CardNo']=$json_data['CardNo2'];
            $data['BankName']=$json_data['BankName'];
        }elseif($json_data['type']=='2'){
            //支付宝
            $data['CardNo']=$json_data['card'];
        }
        $id=M(self::T_MONEY)->add($data);

        //记录余额变动明细
        $logs=array(
            'Type'=>'1',
            'Amount'=>$dec_money,
            'CurrentBalance'=>$CurlMoney,
            'Description'=>'提现操作',
            'UserID'=>$UserID,
            'UpdateTime'=>date('Y-m-d H:i:s'),
            'TradeCode'=>date("YmdHis").rand(10000,99999),
            'AddTime'=>time(),
        );
        M('mem_balances')->add($logs);
        exit(json_encode(array('result'=>1,'message'=>'success','data'=>$id)));
    }


    /**
     * @功能说明: 推荐会员注册完成时我的收入  （一级和二级合并）
     * @提交网址: /center/member/referincome
     * @传输方式: 私有token,明文提交，密文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","page":"0","rows":"10"}
     *                 page:当前页码数，默认值为0     rows:每页展示数据数量，默认值为10
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function referincome(){
        //获取数据流
        $para = get_json_data();
        $UserID=get_login_info('ID');

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;

        $where['a.UserID']=$UserID;
        $where['a.SruType']=1;
        $where['a.Type']=0;

        //分页
        $model=M('mem_balances');
        $info=$model->alias('a')
            ->join('left join xb_mem_info b on b.ID=a.UserID')
            ->field('a.Amount,a.UpdateTime,a.Description as Mobile')
            ->where($where)
            ->limit($page*$rows,$rows)
            ->order('a.ID desc')
            ->select();
        foreach ($info as $key=>$val){
            $info[$key]['HeadImg']="http://".$_SERVER['HTTP_HOST']."/Public/images/icon_touxiang.png";
        }
        if(empty($info)){
            exit(json_encode(array('result'=>0,'message'=>"您还没有推荐会员")));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>encrypt_pkcs7(json_encode($info),get_login_info('KEY'),get_login_info('IV'))
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 推荐的会员贷款、查征信时我的收入
     * @提交网址: /center/member/makeincome
     * @传输方式: 私有token,明文提交，密文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","type":"1","page":"0","rows":"10"}
     *               type:收入类型,默认值为1   2平台网贷 3信用卡贷 4查征信   page:当前页码数，默认值为0     rows:每页展示数据数量，默认值为10
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function makeincome(){
        $para=get_json_data();
        $UserID=get_login_info('ID');

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;
        $type=$para['type']?$para['type']:2;

        if(!in_array($type,array(4,2,3))){
            exit(json_encode(array('result'=>0,'message'=>'请重新选择推广收入的类型')));
        }
        $model=M(self::T_BALANCE);
        $where['a.UserID']=$UserID;
        $where['a.Type']=0;

        if($type==2 || $type==3){
            $where['a.SruType']=$type;
            //分页
            $List=$model->alias('a')
                ->join('left join xb_mem_info b on b.ID=a.UserID')
                ->join('left join xb_apply_listresult c on c.ID=a.oid')
                ->field('a.Amount,a.UpdateTime,c.Mobile,c.Name')
                ->where($where)
                ->limit($page*$rows,$rows)
                ->order('a.ID desc')
                ->select();
        }else{
            $where['a.SruType']=4;
            //分页
            $List=$model->alias('a')
                ->join('left join xb_mem_info b on b.ID=a.UserID')
                ->join('left join xb_zenxin_list c on c.ID=a.oid')
                ->field('a.Amount,a.UpdateTime,c.Mobile,c.TrueName as Name')
                ->where($where)
                ->limit($page*$rows,$rows)
                ->order('a.ID desc')
                ->select();
        }
        if(empty($List)){
            exit(json_encode(array('result'=>0,'message'=>'暂无数据')));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>encrypt_pkcs7(json_encode($List),get_login_info('KEY'),get_login_info('IV'))
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 推广收入网贷and信用卡列表
     * @提交网址: /center/member/getpromotdata
     * @传输方式: 私有token,明文提交，密文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","Itype":"1",,"isdab":"1""page":"0","rows":"10"}
     *               Itype:1贷款商品 2信用卡商品  isdab:0 未达标 1达标了   page:当前页码数，默认值为0     rows:每页展示数据数量，默认值为10
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function getpromotdata(){
        $para=get_json_data();
        $UserID=get_login_info('ID');

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;

        $Itype=$para['Itype']?$para['Itype']:1;
        $isdab=$para['isdab']?$para['isdab']:0;

        $where=array();
        $where['a.Itype']=array('eq',$Itype);
        $where['a.Status']=array('eq',$isdab);
        $where['a.IsDel']=array('eq','0');
        $where['a.UserID']=array('eq',$UserID);

        $info=M('apply_list')->alias('a')
              ->field('a.OrderSn,a.Mobile,a.Addtime,a.Yjtype,a.BonusRate,a.Ymoney,a.Status,a.Bonus,b.Name as goodname,b.Logurl,b.Settletime,a.applyBonus')
              ->join('left join xb_items b on a.ItemID=b.ID')
              ->order('a.ID desc')
              ->where($where)
              ->limit($page*$rows,$rows)
              ->select();
        if(empty($info)){
            exit(json_encode(array('result'=>0,'message'=>'暂无数据')));
        }else{
            foreach($info as $k=>&$v){
                $v['Mobile']=substr_replace($v['Mobile'],'****',3,4);
                $v['Logurl']="http://".$_SERVER['HTTP_HOST'].$v['Logurl'];
            }
            $data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>$info,
            );
            exit(json_encode($data));
        }

    }

    /* *********以下是首页融客店信息***********************************************************************/
    /**
     * @功能说明: 获取 融客店 信息
     * @传输格式: 私有token,无提交，明文返回
     * @提交网址: /center/member/rongke
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1"}
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function rongke(){
        $db=M(self::T_TABLE);
        $UserID=get_login_info('ID');
        $mem=$db->where(array('ID'=>$UserID,'Status'=>1,'IsDel'=>0))->find();
        if(empty($mem)){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,未查找到相关的数据!')));
        }

        $url='/Upload/qrcode/'.$UserID.'/'.$UserID."__rongke.png";
        $url2="http://".$_SERVER['HTTP_HOST'].'/Upload/qrcode/'.$UserID.'/'.$UserID."__rongke.png";
        if(!is_file('./Upload/qrcode/'.$UserID.'/'.$UserID."__rongke.png")){
            $return=PrintQrcode($UserID);
        }
        $shareurl=get_basic_info("SystemDomain").'/Daibeishop/platweb?uid='.$UserID;//get_basic_info("SystemDomain")."/Daibeishop/platweb?uid=".$UserID;
        $info['url']=$url;
        $info['url2']=$url2;
        $info['shareurl']=$shareurl;
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$info
        );
        exit(json_encode($data));
    }


    /* ***********以下是征信查询的接口**************************************************************************************************/

    /**
     * @功能说明: 提交 征信查询的信息
     * @传输格式: 私有token,密文提交，明文返回
     * @提交网址: /center/member/submitinfo
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1",
     *                     "dynamic":{"truename":"真实姓名","cardID":"身份证号码","mobile":"手机号码"}}
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function submitinfo(){
        //获取数据流
        $para=get_json_data();
        //密文解密
        $json_data=json_decode(decrypt_pkcs7($para['dynamic'],get_login_info('KEY'),get_login_info('IV')),true);
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法!')));
        }

        if(!$json_data['truename']){
            $this->ajaxReturn(0,'真实姓名不能为空!');
        }
        if(!$json_data['cardID']){
            $this->ajaxReturn(0,'请输入身份证号!');
        }
        if(!$json_data['mobile']){
            $this->ajaxReturn(0,'请输入手机号码!');
        }
        if(!checkIdCard($json_data['cardID'])){
            $this->ajaxReturn(0,'身份证号码不正确!');
        }
        if(!is_mobile($json_data['mobile'])){
            $this->ajaxReturn(0,'手机号码不正确!');
        }

        $data=array(
            'UserID'=>get_login_info('ID'),
            'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
            'OrderAmount'=>$GLOBALS['BasicInfo']['ZxPay'],
            'TrueName'=>$json_data['truename'],
            'IDCard'=>$json_data['cardID'],
            'Mobile'=>$json_data['mobile'],
            'Status'=>'1',
        );
        $result=M(self::T_ZENXIN)->add($data);
        if(!$result){
            exit(json_encode(array('result'=>0,'message'=>'提交数据失败','data'=>array())));
        }
        $info['ZxPay']=$GLOBALS['BasicInfo']['ZxPay'];
        $info['ID']=$result;
        $output=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$info
        );
        exit(json_encode($output));
    }


    /**
     * @功能说明: 获取 征信查询 列表
     * @传输格式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/getcredibility
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1","page":"0","rows":"10"}
     *              "page":"当前页码数，默认0","rows":"每页展示数据数量，默认10"
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function getcredibility(){
        //获取数据流
        $para = get_json_data();
        $UserID=get_login_info('ID');
        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;

        $where['UserID']=array('eq',$UserID);
        $where['IsDel']=array('eq','0');
        $List=M(self::T_ZENXIN)->field('ID,TrueName,Mobile,Checktime,Status,OrderAmount')->where($where)->limit($page*$rows,$rows)->order('ID desc')->select();
        if(empty($List)){
            exit(json_encode(array('result'=>0,'message'=>'没有查询征信的记录','data'=>array())));
        }
        foreach ($List as $key=>$val){
                switch ($val['Status']){
                    case 1:$List[$key]['Status']="待付款";break;
                    case 2:$List[$key]['Status']="已付款";break;
                    case 3:$List[$key]['Status']="查询失败";break;
                    case 4:$List[$key]['Status']="已查询";break;
                    case 5:$List[$key]['Status']="已取消";break;
                }
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 征信查询 详情
     * @传输格式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/getdetail
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1","id":"15"}
     *              "id":"征信查询的ID"
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function getdetail(){
        //获取数据流
        $para=get_json_data();
        $UserID=get_login_info('ID');
        $infos=M(self::T_ZENXIN)->where(array('ID'=>$para['id'],'UserID'=>$UserID,'IsDel'=>'0'))->find();
        if(!$infos){
            exit(json_encode(array('result'=>0,'message'=>'没有该征信详情','data'=>array())));
        }
        // if($infos['LoanInfos']){
        //     $infos['LoanInfos']=unserialize($infos['LoanInfos']);
        //     $infos['LoanInfos']=json_encode($infos['LoanInfos']);
        //     $infos['LoanInfos']=json_decode($infos['LoanInfos'],true);
        // }
        if($infos['Status']=='4'){
            $infos['detailurl']='http://'.$_SERVER['HTTP_HOST'].'/Index/appzdetail?id='.$infos['ID'];
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$infos
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 征信支付
     * @传输格式: 私有token,密文提交，明文返回
     * @提交网址: /center/member/orderpay
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1",
     *              dynamic:{"paytype":"支付方式  1微信支付 2支付宝支付  3余额支付","id":"查询的征信ID"}}
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function orderpay(){
        //获取数据流
        $para=get_json_data();
        $UserID=get_login_info('ID');
        //密文解密
        $json_data=json_decode(decrypt_pkcs7($para['dynamic'],get_login_info('KEY'),get_login_info('IV')),true);
        if($json_data==false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,提交的数据非法,无法支付!','data'=>array())));
        }
        
        $orderinfo=M(self::T_ZENXIN)->where(array('ID'=>$json_data['id'],'UserID'=>$UserID,'Status'=>'1','IsDel'=>'0'))->find();
        if(!$orderinfo){
            exit(json_encode(array('result'=>0,'message'=>'查询订单不存在!','data'=>array('result'=>'false'))));
        }
        if(!$json_data['paytype']){
            exit(json_encode(array('result'=>0,'message'=>'请选择支付方式!','data'=>array('result'=>'false'))));
        }
        if(!in_array($json_data['paytype'],array('1','2','3','4','5'))){
            exit(json_encode(array('result'=>0,'message'=>'支付方式不正确!','data'=>array('result'=>'false'))));
        }
        $paymoney1=$paymoney=$orderinfo['OrderAmount'];
        if(!$paymoney){
            exit(json_encode(array('result'=>0,'message'=>'征信支付金额异常,请联系管理员!','data'=>array('result'=>'false'))));
        }

        //支付
        if($json_data['paytype']=='3'){
            //余额支付
            $Balance=M(self::T_TABLE)->where(array('ID'=>$UserID))->getField('Balance');
            if($Balance<$paymoney){
                exit(json_encode(array('result'=>0,'message'=>'余额不足，无法支付','data'=>array('result'=>'false'))));
            }
            $model=M();
            $model->startTrans();
            $result=$model->table('xb_mem_info')->where(array('ID'=>$UserID))->setDec('Balance',$paymoney);
            if(!$result){
                $output=array(
                    'result'=>0,
                    'message'=>'余额支付失败',
                    'data'=>array('result'=>'false')
                );
                exit(json_encode($output));
            }
            //支付成功，修改该征信的状态
            $sadata=array(
                'Status'=>'2',
                'PayType'=>'3',
                'PayTime'=>date('Y-m-d H:i:s'),
            );
            $rest=$model->table('xb_zenxin_list')->where(array('ID'=>$json_data['id']))->save($sadata);
            if(!$rest){
                $model->rollback();
                $output=array(
                    'result'=>0,
                    'message'=>'修改该征信的状态失败',
                    'data'=>array('result'=>'false')
                );
                exit(json_encode($output));
            }
            $model->commit();
            //支付成功,记录余额消费明细
            $desc='征信查询支付,订单号:'.$orderinfo['OrderSn'];
            balancerecord('1',$paymoney1,$UserID,'','',$desc,'',$json_data['id']);
            //三级分销 分佣
            $Intro=M(self::T_TABLE)->where(array('ID'=>$UserID))->getField('Mobile');
            shareOrderMoneyRecord($paymoney1,$UserID,'4',$desc,$Intro,$json_data['id']);

            //查询征信
            //$MiguanApi = new \Extend\MiguanApi($GLOBALS['BasicInfo']['Mgaccount'],$GLOBALS['BasicInfo']['Mgsecret'],$orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
            //京东蜜罐查选
            $MiguanApi = new \Extend\MiguanJingdongApi($orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
            $mrest=$MiguanApi->searchs();

            //查询失败
            if($mrest['result']!='1'){
                $updata=array(
                    'Errtxt'=>$mrest['message'],
                    'Checktime'=>date('Y-m-d H:i:s'),
                    'Status'=>'3',
                );
                M(self::T_ZENXIN)->where(array('ID'=>$json_data['id']))->save($updata);

                $output=array(
                    'result'=>0,
                    'message'=>'征信查询失败,请联系客服',
                    'data'=>array('result'=>'false')
                );
                exit(json_encode($output));
            }

            //查询成功
            $searchinfo=serialize($mrest['message']);
            $updata=array(
                'LoanInfos'=>$searchinfo,
                'Checktime'=>date('Y-m-d H:i:s'),
                'Status'=>'4',
            );
            M(self::T_ZENXIN)->where(array('ID'=>$json_data['id']))->save($updata);

        }elseif($json_data['paytype']=='1'){
            //微信支付
        }elseif($json_data['paytype']=='2'){
            //支付宝支付
        }

        $rest=array(
            'id'=>$orderinfo['ID'],
            'oid'=>$orderinfo['OrderSn'],
            );
        $data=array(
            'result'=>1,
            'message'=>'征信查询成功',
            'data'=>$rest,
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 再次 查询征信
     * @传输格式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/againcheck
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"pc","ver":"1.1","id":"15"}
     *              "id":"征信查询的ID"
     * @返回信息：{'result'=>1,'message'=>'恭喜您，数据查询成功！','data'}
     */
    public function againcheck(){
        //获取数据流
        $para=get_json_data();
        $UserID=get_login_info('ID');
        $orderinfo=M(self::T_ZENXIN)->where(array('ID'=>$para['id'],'UserID'=>$UserID,'IsDel'=>'0'))->find();
        if(empty($orderinfo)){
            exit(json_encode(array('result'=>0,'message'=>'传递的参数id错误','data'=>array())));
        }
        if($orderinfo['Status']!='2' && $orderinfo['Status']!='3') {
            exit(json_encode(array('result'=>0,'message'=>'查询的记录状态不正确','data'=>array())));
        }
        //查询征信
        //$MiguanApi = new \Extend\MiguanApi($GLOBALS['BasicInfo']['Mgaccount'],$GLOBALS['BasicInfo']['Mgsecret'],$orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
        //京东蜜罐查询
        $MiguanApi = new \Extend\MiguanJingdongApi($orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
        $mrest=$MiguanApi->searchs();
        //查询失败
        if($mrest['result']!='1'){
            $updata=array(
                'Errtxt'=>$mrest['message'],
                'Checktime'=>date('Y-m-d H:i:s'),
                'Status'=>'3',
            );
            M(self::T_ZENXIN)->where(array('ID'=>$para['id']))->save($updata);
            $output=array(
                'result'=>0,
                'message'=>'征信查询失败,请联系客服',
                'data'=>array()
            );
            exit(json_encode($output));
        }
        
        //查询成功
        $searchinfo=serialize($mrest['message']);
        $updata=array(
            'LoanInfos'=>$searchinfo,
            'Checktime'=>date('Y-m-d H:i:s'),
            'Status'=>'4',
        );
        M(self::T_ZENXIN)->where(array('ID'=>$para['id']))->save($updata);
        $output=array(
            'result'=>$searchinfo,
            'message'=>'征信查询成功',
            'data'=>$para['id']
        );
        exit(json_encode($output));
    }

    /**
     * @功能说明: 会员中心四板块
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/bankuais
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1",}
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function bankuais(){
        $para=get_json_data();
        // $UserID=get_login_info('ID');
        //会员中心 四个板块
        $bankuai=M('mem_bankuai')->field('ID,Name,Intro,Backurl')->where(array('Status'=>'1','IsDel'=>'0'))->select();
        if(!$bankuai){
            exit(json_encode(array('result'=>0,'message'=>'暂无信息')));
        }
        foreach($bankuai as $k=>$v){
            $bankuai[$k]['Backurl']='http://'.$_SERVER['HTTP_HOST'].$v['Backurl'];
        }
        $output=array(
            'result'=>1,
            'message'=>'获取板块信息成功',
            'data'=>$bankuai,
        );
        exit(json_encode($output));
    }


    /**
     *@功能说明: 修改会员IP，city和手机类型
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/modifyAdd
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"android","package":"android.ceshi","ver":"v1.1",
     *              "dynamic":{"ip":"IP", "city":"城市"}}
     * @返回信息: {'result'=>1,'message'=>'恭喜您！修改成功!'}
     */
    public function modifyAdd(){
        $userID=get_login_info('ID');
        $para=get_json_data();

        if(empty($para['dynamic']['ip'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,IP参数缺失!')));
        }

        if (empty($para['dynamic']['city'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,地址参数缺失!')));
        }

        $data = array(
            'PhoneType' => $para['client'],
            'LoginIP' => $para['dynamic']['ip'],
            'IpCity' => $para['dynamic']['city']
        );

        $result  = M(self::T_TABLE)->where(array('ID'=>$userID))->save($data);
        if ($result === false){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉,数据保存失败!')));
        }

        exit(json_encode(array('result'=>1,'message'=>'恭喜您！修改成功!')));
    }
	/**
     *@功能说明: 统计
     * @传输方式: 私有token,明文提交，明文返回
     * @提交网址: /center/member/productStatistics
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"android","package":"android.ceshi","ver":"v1.1",
     *            
     */

	 public function productStatistics(){
		$userID=get_login_info('ID');
		$json_data=get_json_data();
		$productId = $json_data['productId'];
		$item = M(self::T_ITEMS)->find($productId);
		$productName = $item['Name'];
		$ip = get_client_ip();
		$time = date('Y-m-d');
		$b = getTodayIp($time,$ip.','.$productId);
		$mode = M(self::T_PRODUCT)->where(array('productId'=>$productId,'createDate'=>$time))->find();
		if($mode){
			$PV = $mode['PV'] + 1;
			$UV = $mode['UV'];
			if(!$b){
				$UV++;
			}
			$data = array(
				'PV' => $PV,
				'UV' => $UV,
			);
			M(self::T_PRODUCT)->where(array('ID'=>$mode['ID']))->save($data);
		}else{
			$data = array(
				'productId' => $productId,
				'productName' => $productName,
				'PV' => 1,
				'UV' => 1,
				'createDate' => $time,
				'CPA' =>$item['price'],
			);
			M(self::T_PRODUCT)->add($data);
		}
		/*$enableType = $item['enableType'];
		if($enableType==1){
			$registerPv = $item['registerPv'];
			$registerNum = 0;
			$UV = 1;
			if($mode){
				$registerNum = $mode['registerNum'];
				$UV = $mode['UV'];
			}
			$registerNumUV = $registerNum/$UV;
			//查询今天是否有数据
			$itemEn = M('item_enable')->where(array('itemId'=>$productId,'createDate'=>$time))->find();
			if($registerNumUV>=$registerPv&&!$itemEn){//达到
				$datav = array(
					'Status' => 0,
					'ID' => $productId,
				);
				M(self::T_ITEMS)->save($datav);//下线
				$datap = array(
					'itemId' => $productId,
					'createDate' => $time,
				);
				M('item_enable')->add($datap);//添加数据
			}
		}

		if($enableType==2){
			$$UV = 1;
			if($mode){
				$UV = $mode['UV'];
			}
			//查询今天是否有数据
			$UVV = $item['UV'];
			$itemEn = M('item_enable')->where(array('itemId'=>$productId,'createDate'=>$time))->find();
			if($UV>=$UVV&&!$itemEn){//达到
				$datav = array(
					'Status' => 0,
					'ID' => $productId,
				);
				M(self::T_ITEMS)->save($datav);//下线
				$datap = array(
					'itemId' => $productId,
					'createDate' => $time,
				);
				M('item_enable')->add($datap);//添加数据
			}

		}*/


		//频道统计
		$user = M(self::T_TABLE)->where(array('ID'=>$userID))->find();
		$TgadminID = $user['TgadminID'];
		$tg_admin = M('tg_admin')->where(array('Status'=>'1','IsDel'=>'0'))->find($TgadminID);
		$time = date('Y-m-d');
		$RegTime = $user['RegTime'];
		$reg = substr($RegTime,0,10); 
		$mode = M('channel_statistics')->where(array('channelName'=>$tg_admin['Name'],'createDate'=>$time))->find();
		if($mode){
			$newNum = $mode['newNum'];
			$applyNum = $mode['applyNum'];
			if($reg==$time){
				if(!$b){
					$newNum++;
				}
			}
			if(!$b){
				$applyNum = $mode['applyNum'] + 1;
			}
			$data = array(
				'newNum' => $newNum,
				'applyNum' => $applyNum,
			);
			M(self::T_CHANNEL)->where(array('ID'=>$mode['ID']))->save($data);
		}else{
			$newNum = 0;
			if($reg==$time){
				$newNum++;
			}
			$data = array(
				'channelName' => $tg_admin['Name'],
				'createDate' => $time,
				'CPA' =>$tg_admin['price'],
				'newNum' =>$newNum,
				'applyNum' =>1,
			);
			M(self::T_CHANNEL)->add($data);
	
		}
	 }

	 public function channelStatisticsAlive($TgadminID){
		$tg_admin = M('tg_admin')->where(array('Status'=>'1','IsDel'=>'0'))->find($TgadminID);
		$time = date('Y-m-d');
		$mode = M('channel_statistics')->where(array('channelName'=>$tg_admin['Name'],'createDate'=>$time))->find();
		if($mode){
			$aliveNum = $mode['aliveNum'] + 1;
			$data = array(
				'aliveNum' => $aliveNum,
			);
			M(self::T_CHANNEL)->where(array('ID'=>$mode['ID']))->save($data);
		}
	 }

}