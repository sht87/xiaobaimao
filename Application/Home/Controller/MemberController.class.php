<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/12 9:56
 * 功能说明: 会员中心
 */

namespace Home\Controller;

class MemberController extends UserController
{

    /*
     * 会员中心 首页
     */
    public function index(){
        $UserID=session('loginfo')['UserID'];
        $memInfo=M('mem_info')->where(array('ID'=>$UserID))->find();
        //会员的代理级别
        $levelname='';
        if($memInfo['Mtype']=='1'){
            $levelname='普通会员';
        }else{
            $levelname=M('mem_levels')->where(array('ID'=>$memInfo['Mtype']-1))->getField('Name');
        }
        //已结算
        $account=M('mem_money')->field('Money')->where(array('Uid'=>$UserID,'Type'=>0,'IsAduit'=>2))->select();
        $Money=0;
        if($account){
            $chargeArr=array();
            foreach($account as $k=>$v){
                $chargeArr[]=$v['Money'];
            }
            //$chargeArr=array_column($account,'Money');
            for($i=0;$i<count($chargeArr);$i++){
                $Money+=$chargeArr[$i];
            }
        }
        //总收入
        $income=M('mem_balances')->field('Amount')->where(array('UserID'=>$UserID,'Type'=>0,'SruType'=>array('neq','5')))->select();
        $earnMoney=0;
        if($income){
            $earArr=array();
            foreach($income as $k=>$v){
                $earArr[]=$v['Amount'];
            }
            //$earArr=array_column($income,'Amount');
            for($i=0;$i<count($earArr);$i++){
                $earnMoney+=$earArr[$i];
            }
        }
        //客服电话
        $Tel=$GLOBALS['BasicInfo']['Tel'];
        $noread=false;//未读标识
        if(session('loginfo')['UserID']){
            $retarr=ishavenoread(session('loginfo')['UserID']);
            if($retarr['xtmsg'] || $retarr['tzmsg']){
                $noread=true;
            }
        }
        //会员中心 四个板块
        $bankuai=M('mem_bankuai')->field('ID,Name,Intro,Backurl')->where(array('Status'=>'1','IsDel'=>'0'))->select();
        $bankinfos=array();
        foreach($bankuai as $k=>$v){
            $bankinfos[$v['ID']][]=$v;
        }
        $this->assign(array(
            'levelname'   =>$levelname,
            'memInfo'   =>$memInfo,
            'Tel'       =>$Tel,
            'Money'     =>$Money,
            'earnMoney'     =>$earnMoney,
            'noread'     =>$noread,
            'bankinfos'     =>$bankinfos,
        ));

        $this->display();
    }

    /**
     * 基本信息
     */
    public function info(){
        $UserID=session('loginfo')['UserID'];
        $memInfo=M('mem_info')->alias('a')
            ->join('left join xb_mem_info b on b.ID=a.Referee')
            ->field('a.*,b.TrueName as ReferName')
            ->where(array('a.ID'=>$UserID))->find();

        $ext=$GLOBALS['BasicInfo']['PicExt'];
        $ext=explode(',',$ext);
        $exts='';
        foreach($ext as $val){
            $exts.='*.'.$val.';';
        }

        $this->assign(array(
            'exts'      =>  $exts,
            'memInfo'   =>$memInfo,
            'title'    =>'基本信息'
        ));
        $this->display();
    }

    /**
     * 上传头像
     */
    public function upload(){
        $upload=new \XBCommon\XBUpload();
        $res=$upload->uploadimage();
        if($res['result']=='success'){
            $data['result']=1;
            $data['path']=$res['path'];
        }else{
            $data['result']=0;
            $data['message']='上传失败！';
        }
        $this->ajaxReturn($data);
    }


    /*
     * 保存个人信息
     */
    public function save(){
        if(!IS_POST){
            $this->ajaxReturn(0,'数据提交方式不正确');
        }

        $UserID=session('loginfo')['UserID'];
        $data['HeadImg']=I('post.HeadImg','','trim');
        $data['TrueName']=I('post.TrueName','','trim');
        $data['CardNo']=I('post.CardNo','','trim');
        $data['UseTime']=I('post.UseTime',0,'intval');
        $data['IsCredit']=I('post.IsCredit',0,'intval');
        $data['Position']=I('post.Position',0,'intval');
        $data['UpdateTime']=date("Y-m-d H:i:s");

        if(!$data['TrueName']){
            $this->ajaxReturn(0,'请输入您的真实姓名');
        }
        if(!$data['CardNo']){
            $this->ajaxReturn(0,'请输入您的身份证号码');
        }
        if(!preg_match("/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/",$data['CardNo'])){
            $this->ajaxReturn(0,"身份证号码格式不正确");
        }
        if($data['Position']!=0 && !in_array($data['Position'],array(1,2,3,4))){
            $this->ajaxReturn(0,"操作有误，请重新选择您的身份");
        }
        if(!in_array($data['IsCredit'],array(1,0))){
            $this->ajaxReturn(0,"操作有误，请重新选择您是否有信用卡");
        }
        if(!in_array($data['UseTime'],array(1,2,3,4))){
            $this->ajaxReturn(0,"操作有误，请重新选择手机使用年限");
        }

        $saveRes=M('mem_info')->where(array('ID'=>$UserID))->save($data);
        if($saveRes){
            $this->ajaxReturn(1,'保存成功');
        }else{
            $this->ajaxReturn(0,"保存失败");
        }

    }


    /**
     * 购买代理
     */
    public function buyagent(){
        // 产品介绍 //
        $ProIntro=M('sys_simplepage')->where(array('ID'=>2))->find();
        // 购买需知 //
        $Known=M('sys_simplepage')->where(array('ID'=>3))->find();
        // 成为代理 //
        $AgentInfo=M('mem_levels')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->limit(3)->select();
        // 用户注册协议 //
        $Title=M('sys_simplepage')->where(array('ID'=>7))->getField('Title');
        //如果已经是购买了会员
        $curr_price='';//当前级别价格
        $Mtype=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mtype');
        if($Mtype=='1'){
            $levelname='普通会员';
        }else{
            $levelname=M('mem_levels')->where(array('ID'=>($Mtype-1)))->getField('Name');
        }
        if($Mtype>1){
            //代理价格
            $agentprice=array();
            foreach($AgentInfo as $k=>$v){
                $agentprice[$v['ID']][]=$v;
            }
            if($Mtype=='2'){
                //渠道经理
                $curr_price=$agentprice[1][0]['Price'];
            }elseif($Mtype=='3'){
                //团队经理
                $curr_price=$agentprice[2][0]['Price'];
            }
        }
        $this->assign(array(
            "ProIntro"=>$ProIntro,
            "Known"=>$Known,
            "AgentInfo"=>$AgentInfo,
            "curr_price"=>$curr_price,
            "Title"=>$Title,
            "levelname"=>$levelname,
        ));
        $this->display();
    }

    /**
     * 跳转到支付选择页面的验证
     */
    public function ajaxPay(){
        $ID=I('post.ID',0,'intval');
        $exit=M('mem_levels')->where(array('IsDel'=>0,'Status'=>1,'ID'=>$ID))->find();
        if(!$exit){
            $this->ajaxReturn(0,"操作有误，请重新选择您要购买的代理级别");
        }
        $this->ajaxReturn(1,"");
    }
    /**
     * 支付选择
     */
    public function pay(){
        $ID=I('get.ID',0,'intval');
        $AgentInfo=M('mem_levels')->where(array('ID'=>$ID))->find();
        //判断是什么访问
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            $is_wx = 1;
        }else{
            $is_wx = 0;
        }

        //如果已经是购买了会员,就是不差价升级会员
        $curr_price='';//当前级别价格
        $Mtype=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mtype');
        if($Mtype>1){
            $AgentInfo2=M('mem_levels')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->limit(3)->select();
            //代理价格
            $agentprice=array();
            foreach($AgentInfo2 as $k=>$v){
                $agentprice[$v['ID']][]=$v;
            }
            if($Mtype=='2'){
                //渠道经理
                $curr_price=$agentprice[1][0]['Price'];
            }elseif($Mtype=='3'){
                //团队经理
                $curr_price=$agentprice[2][0]['Price'];
            }
        }
        $paymoney='';//支付的金额
        if($curr_price){
            $paymoney=$AgentInfo['Price']-$curr_price;
        }else{
            $paymoney=$AgentInfo['Price'];
        }
        $this->assign(array(
            "AgentInfo"=>$AgentInfo,
            "paymoney"=>$paymoney,
            "is_wx"=>$is_wx,
        ));
        $this->display();
    }
    //------------------购买会员支付---------
    public function paylevel(){
        if(IS_POST){
            $id=I('post.id','');
            $PayType=I('post.PayType','');
            $meminfo=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->find();
            //如果已经是购买了会员,就是补差价升级会员
            $curr_price='';//当前级别价格
            if($meminfo['Mtype']>1){
                //校验,不可重复购买当前级别的会员,只能购买高级会员
                if(($meminfo['Mtype']-1)>=$id){
                    $this->ajaxReturn(0,'请购买更高级的代理!');
                }
                $AgentInfo2=M('mem_levels')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->limit(3)->select();
                //代理价格
                $agentprice=array();
                foreach($AgentInfo2 as $k=>$v){
                    $agentprice[$v['ID']][]=$v;
                }
                if(session('loginfo')['Mtype']=='2'){
                    //渠道经理
                    $curr_price=$agentprice[1][0]['Price'];
                }elseif(session('loginfo')['Mtype']=='3'){
                    //团队经理
                    $curr_price=$agentprice[2][0]['Price'];
                }
            }
            $levelinfo=M('mem_levels')->where(array('ID'=>$id,'Status'=>'1','IsDel'=>'0'))->find();
            if(!$levelinfo){
                $this->ajaxReturn(0,'购买的代理信息不存在!');
            }
            //算出实际支付的金额
            if($curr_price){
                $levelinfo['Price']=$levelinfo['Price']-$curr_price;
            }

            if(!in_array($PayType,array('1','2','3','4','chanpay'))){
                $this->ajaxReturn(0,'支付方式不正确!');
            }
            //支付
            if($PayType=='3'){
                //余额支付
                //校验
                $paymoney=$levelinfo['Price'];
                if($meminfo['Balance']<$levelinfo['Price']){
                    $this->ajaxReturn(0,'余额不足,请选择其他支付方式!');
                }
                $model=M();
                $model->startTrans();
                $result=$model->table('xb_mem_info')->where(array('ID'=>session('loginfo')['UserID']))->setDec('Balance',$levelinfo['Price']);
                if($result){
                    //添加代理购买记录表
                    $insetdata=array(
                        'UserID'=>session('loginfo')['UserID'],
                        'LevelID'=>$levelinfo['ID'],
                        'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                        'OrderAmount'=>$levelinfo['Price'],
                        'OrderTime'=>date('Y-m-d H:i:s'),
                        'Status'=>'2',
                        'PayType'=>'3',
                        'PayTime'=>date('Y-m-d H:i:s'),
                    );
                    $rest=$model->table('xb_mem_buydaili')->add($insetdata);
                    if($rest){
                        //修改会员表
                        $mtype='';
                        if($levelinfo['ID']=='1'){
                            $mtype='2';
                        }elseif($levelinfo['ID']=='2'){
                            $mtype='3';
                        }elseif($levelinfo['ID']=='3'){
                            $mtype='4';
                        }
                        $memrestul=$model->table('xb_mem_info')->where(array('ID'=>session('loginfo')['UserID']))->save(array('Mtype'=>$mtype));
                        if($memrestul){
                            $model->commit();
                            //支付成功,记录余额消费明细
                            $desc='购买代理支付,订单号:'.$insetdata['OrderSn'];
                            //balancerecord('1',$paymoney,session('loginfo')['UserID'],'','',$desc,'','');
                            $mem_info=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->find();
                            $data=array("Type"=>'1',"Amount"=>$paymoney,"CurrentBalance"=>$mem_info['Balance'], "Description"=>$desc,"UserID"=>session('loginfo')['UserID'],"UpdateTime"=>date('Y-m-d H:i:s'),"TradeCode"=>date("YmdHis").rand(10000,99999));
                            M('mem_balances')->add($data);
                            //重新保存session中会员类型
                            $loginfo=session('loginfo');
                            $loginfo['Mtype']=$mtype;
                            session('loginfo',$loginfo);
                            //三级分销 分佣
                            //shareOrderMoneyRecord($paymoney,session('loginfo')['UserID'],$SruType,$Description,$Intro,$oid);
                            shareOrderMoneyRecord($paymoney,session('loginfo')['UserID'],'1',$meminfo['Mobile'],'',0);
                            $pay_result = 1;
                            $pay_mess = "购买代理成功";
                            $href = '/Member/index';
                            $dialog = '';
                        }else{
                            $model->rollback();
                            $this->ajaxReturn(0,"余额支付失败");
                        }
                    }else{
                        $model->rollback();
                        $this->ajaxReturn(0,"余额支付失败");
                    }
                }
            }elseif($PayType=='1'){
                //添加代理购买记录表
                $insetdata=array(
                    'UserID'=>session('loginfo')['UserID'],
                    'LevelID'=>$levelinfo['ID'],
                    'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                    'OrderAmount'=>$levelinfo['Price'],
                    'OrderTime'=>date('Y-m-d H:i:s'),
                    'Status'=>'1',
                    'PayType'=>'1',
                );
                $rest=M('mem_buydaili')->add($insetdata);
                if($rest){
                    //微信支付
                    $pay_result = 1;
                    $pay_mess = "订单提交成功";
                    $href = "/Wachet/index?id=".$rest."&oid=".$insetdata['OrderSn'];
                    $dialog = '';
                }else{
                    $this->ajaxReturn(0,"微信支付失败!");
                }
            }elseif($PayType=='2'){
                //支付宝支付
                //添加代理购买记录表
                $insetdata=array(
                    'UserID'=>session('loginfo')['UserID'],
                    'LevelID'=>$levelinfo['ID'],
                    'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                    'OrderAmount'=>$levelinfo['Price'],
                    'OrderTime'=>date('Y-m-d H:i:s'),
                    'Status'=>'1',
                    'PayType'=>'2',
                );
                $rest=M('mem_buydaili')->add($insetdata);
                if($rest){
                    $pay_result = 1;
                    $pay_mess = "订单提交成功";
                    $href = "/Alipay/index?id=".$rest."&oid=".$insetdata['OrderSn'];
                    $dialog = '';
                }else{
                    $this->ajaxReturn(0,"支付宝支付失败!");
                }
            }elseif($PayType=='4'){
                //微信 h5支付
                //添加代理购买记录表
                $insetdata=array(
                    'UserID'=>session('loginfo')['UserID'],
                    'LevelID'=>$levelinfo['ID'],
                    'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
                    'OrderAmount'=>$levelinfo['Price'],
                    'OrderTime'=>date('Y-m-d H:i:s'),
                    'Status'=>'1',
                    'PayType'=>'1',
                );
                $rest=M('mem_buydaili')->add($insetdata);
                if($rest){
                    //微信支付 h5
                    $pay_result = 1;
                    $pay_mess = "订单提交成功";
                    $href = "/Wachet/index2?id=".$rest."&oid=".$insetdata['OrderSn'];
                    $dialog = '';
                }else{
                    $this->ajaxReturn(0,"微信支付失败!");
                }
            } elseif($PayType=='chanpay') {

                //添加代理购买记录表
                $insetdata=array(
                    'UserID'=>session('loginfo')['UserID'],
                    'LevelID'=>$levelinfo['ID'],
                    'OrderSn'=>date('ymd').rand(1,9).date('His').rand(111,999),
                    'OrderAmount'=>$levelinfo['Price'],
                    'OrderTime'=>date('Y-m-d H:i:s'),
                    'Status'=>'1',
                    'PayType'=>'5',
                );
                $rest=M('mem_buydaili')->add($insetdata);
                if($rest){
                    $pay_result = 1;
                    $pay_mess = "订单提交成功";
                    $href = "/Payment/bankCard?order_no=".$insetdata['OrderSn'];
                    $dialog = '';
                }else{
                    $this->ajaxReturn(0,"银行卡支付失败!");
                }
            }
            $this->ajaxReturn($pay_result,$pay_mess,$href,$dialog);
        }else{
            $this->ajaxReturn(0,'提交方式不正确');
        }
    }
    
    /**
     * 我的消息
     */
    public function news(){
        $UserID=session('loginfo')['UserID'];
        $where['UserID']=array('in',array(0,$UserID));
        $notice=M('mem_message')->where(array($where,'Type'=>1))->order('SendTime desc')->find();
        $sysmsg=M('mem_message')->where(array($where,'Type'=>0))->order('SendTime desc')->find();
        $this->assign(array(
            'notice'=>$notice,
            'sysmsg'=>$sysmsg,
            'title'=>"我的消息"
        ));
        $this->display();
    }

    /**
     * 消息列表
     */
    public function newslist(){
        $Type=I('get.Type',0,'intval');
        $UserID=session('loginfo')['UserID'];
        $userInfo = M('mem_info')->where(array('ID'=>$UserID))->find();
        $where['UserID']=array('in',array(0,$UserID));
        $where['SendTime'] = array('egt', $userInfo['RegTime']);
        $where['Status']=1;
        $msgList=M('mem_message')->where(array($where,'Type'=>$Type))->order('SendTime desc')->select();
        foreach($msgList as $key=>$val){
            $exit='';
            $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$UserID))->find();
            if($exit){
                $msgList[$key]['IsRead']=1;//已读
            }else{
                $msgList[$key]['IsRead']=0;//未读
                //把此记录标记为已读
                $sdata=array();
                $sdata['MID']=$val['ID'];
                $sdata['UID']=$UserID;
                $sdata['Time']=date('Y-m-d H:i:s');
                M('readmessage')->add($sdata);
            }
        }
        $this->assign(array(
            'msgList'=>$msgList,
        ));
        $this->display();
    }

    /**
     * 读取消息
     */
    public function readmsg(){
        $MID=I('get.MID',0,'intval');
        $UID=I('get.UID',0,'intval');
        $exit=M('readmessage')->where(array('MID'=>$MID,'UID'=>$UID))->find();
        if($exit){
            $this->ajaxReturn(0,"已阅读");
        }
        $data=array(
            'MID'=>$MID,
            'UID'=>$UID,
            'Time'=>date('Y-m-d H:i:s')
        );
        $res=M('readmessage')->add($data);
        if($res){
            $this->ajaxReturn(1,"已读");
        }
    }

    /**
     * 推广分享
     */
    public function share(){
        $id=session('loginfo')['UserID'];
        $url='/Upload/qrcode/'.$id.'/'.$id."__shareqrcode.png";
        $url2="http://".$_SERVER['HTTP_HOST'].'/Upload/qrcode/'.$id.'/'.$id."__shareqrcode.png";
        if(!is_file('./Upload/qrcode/'.$id.'/'.$id."__shareqrcode.png")){
            $return=PrintQrcodeShare($id);
        }
        $shareurl="http://".$_SERVER['HTTP_HOST'].'/Register/index?ui='.$id;
        $this->assign(array(
            'url'=>$url,
            'url2'=>$url2,
            'shareurl'=>$shareurl,
        ));
        $appId = C('Help.Wachet_AppId');
        $appSecret = C('Help.Wachet_AppSecret');;
        $jssdk = new \Extend\JSSDK($appId,$appSecret);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
        $signPackage['appId'] = $appId;
        $this->assign('signPackage',$signPackage);
        //获取推广的数据
        $shuju['ExtensionTitle'] = get_basic_info("ExtensionTitle");
        $shuju['ExtensionDesc'] = get_basic_info("ExtensionDesc");
        $shuju['ExtensionImage'] = "http://".$_SERVER['HTTP_HOST'].get_basic_info("ExtensionImage");
        $this->assign('shuju',$shuju);
        $this->display();
    }

    /**
     * 会员中心 系统设置
     */
    public function setting(){
        $this->assign('title',"设置");
        $this->display();
    }



    /**
     * 修改密码
     */
    public function newpwd(){
        $this->assign('title',"修改密码");
        $this->display();
    }

    /**
     * 保存新密码
     */
    public function savePwd(){
        if(!IS_POST){
            $this->ajaxReturn(0,"数据提交方式不正确");
        }
        $UserID=session('loginfo')['UserID'];
        $oldpwd=I('post.oldpwd','','trim');
        $newpwd=I('post.newpwd','','trim');
        $surepwd=I('post.surepwd','','trim');

        //旧密码验证
        if(!$oldpwd){
            $this->ajaxReturn(0,"请输入原密码");
        }
        if(!is_password($oldpwd)){
            $this->ajaxReturn(0,"原密码格式不正确");
        }
        $right=M('mem_info')->where(array('ID'=>$UserID,'Password'=>md5($oldpwd)))->find();
        if(!$right){
            $this->ajaxReturn(0,"原密码不正确");
        }
        //新密码验证
        if(!$newpwd){
            $this->ajaxReturn(0,"请输入新密码");
        }
        if(!is_password($newpwd)){
            $this->ajaxReturn(0,"密码必须是以英文字母开头，6-16位与数字的组合");
        }
        if($oldpwd==$newpwd){
            $this->ajaxReturn(0,"新密码不能与旧密码相同");
        }
        //确认密码验证
        if(!$surepwd){
            $this->ajaxReturn(0,"请输入确认密码");
        }
        if($surepwd!=$newpwd){
            $this->ajaxReturn(0,"请输入与新密码相同的确认密码");
        }

        //保存
        $data['Password']=md5($newpwd);
        $data['UpdateTime']=date("Y-m-d H:i:s");
        $result=M('mem_info')->where(array('ID'=>$UserID))->save($data);
        if($result){
            member_sms($UserID,$Type=1,"修改密码","您于".$data['UpdateTime']."已成功修改了您的密码，请妥善保管");
            session('loginfo',null);
            $this->ajaxReturn(1,"您已成功修改了您的密码，请重新登录");
        }else{
            $this->ajaxReturn(0,"修改失败");
        }
    }
    //我的钱包
    public function wallet(){
        //统计收入总额
        $income=M('mem_balances')->where(array('Type'=>'0','UserID'=>session('loginfo')['UserID'],'SruType'=>array('neq','5')))->Sum('Amount');
        if(!$income){
            $income='0.00';
        }
        $balances=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Balance');
        $this->assign(array(
            'income'=>$income,
            'balances'=>$balances,
            ));
        $this->display();
    }
    //获取提现数据
    public function walletdata(){
        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        $where['Uid']=array('eq',session('loginfo')['UserID']);
        $where['Type']=array('eq','0');
        $where['IsAduit']=array('eq','2');
        $where['IsDel']=array('eq','0');
        //分页
        $model=M('mem_money');
        $info=$model->field('ID,Money,CurlMoney,AddTime')->where($where)->limit($start_numbs,$per_numbs)->order('ID desc')->select();

        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
    }
    //会员提现 列表
    public function tixian(){
        if(IS_POST){
            //提现数据提交
            $CardID=I('post.CardID','2');
            $HolderName=I('post.HolderName','');
            $BankName=I('post.BankName','');
            $CardNo2=I('post.CardNo2','');
            $CardNo=I('post.CardNo','');
            $Money=I('post.Money','');

            if($CardID=='1'){
                $CardID='银行卡';
                if(!$HolderName){
                    $this->ajaxReturn(0,'请输入持卡人姓名!');
                }
                if(!$CardNo2){
                    $this->ajaxReturn(0,'请输入银行卡号!');
                }
                if(!$BankName){
                    $this->ajaxReturn(0,'请输入开户行名!');
                }
                $CardNo=$CardNo2;
            }elseif($CardID=='2'){
                $CardID='支付宝';
                if(!$CardNo){
                    $this->ajaxReturn(0,'请输入提现账号!');
                }
                //清空银行卡提交的信息
                $HolderName='';
                $BankName='';
                $CardNo2='';
            }
            if(!$Money){
                $this->ajaxReturn(0,'提现金额不能为空!');
            }
            //算出手续费
            $Charge=($GLOBALS['BasicInfo']['Cost']/100)*$Money;
            $Charge=round($Charge,2);
            $dec_money=$Charge+$Money;
            $balances=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Balance');
            if($balances<$dec_money){
                $this->ajaxReturn(0,'余额不足,不能提现!');
            }
            
            //提现操作
            $result=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->setDec('Balance',$dec_money);
            if($result){
                $CurlMoney=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Balance');
                $data=array(
                    'Uid'=>session('loginfo')['UserID'],
                    'Type'=>'0',
                    'CurlMoney'=>$CurlMoney,
                    'Money'=>$Money,
                    'CardID'=>$CardID,
                    'CardNo'=>$CardNo,
                    'AddTime'=>date('Y-m-d H:i:s'),
                    'Charge'=>$Charge,
                    );
                if($HolderName){
                    $data['HolderName']=$HolderName;
                }
                if($BankName){
                    $data['BankName']=$BankName;
                }
                $txid=M('mem_money')->add($data);
                //记录余额变动明细
                $logs=array(
                    'Type'=>'1',
                    'Amount'=>$dec_money,
                    'CurrentBalance'=>$CurlMoney,
                    'Description'=>'提现操作',
                    'UserID'=>session('loginfo')['UserID'],
                    'UpdateTime'=>date('Y-m-d H:i:s'),
                    'TradeCode'=>date("YmdHis").rand(10000,99999),
                    'AddTime'=>time(),
                    );
                M('mem_balances')->add($logs);
                $this->ajaxReturn(1,'提现申请已提交!',$txid);
            }else{
                $this->ajaxReturn(0,'提现失败!');
            }
        }else{
            //提现页面
            $meminfo=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->find();
            $this->assign(array(
                'meminfo'=>$meminfo,
                ));
            $this->display();
        }
    }
    //提现申请提交成功
    public function txsucess(){
        $id=I('get.id');
        $info=M('mem_money')->field('ID,Money,CardID,CardNo')->where(array('ID'=>$id,'Uid'=>session('loginfo')['UserID'],'Type'=>'0','IsDel'=>'0'))->find();
        $this->assign(array(
            'info'=>$info,
            ));
        $this->display();
    }

    /**
     * 查看收入列表
     */
    public function income(){
        $this->display();
    }

    /**
     * 查看收入列表数据加载
     */
    public function incomedata(){
        $UserID=session('loginfo')['UserID'];

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where['a.UserID']=$UserID;
        $where['a.SruType']=1;
        $where['a.Type']=0;

        //分页
        $model=M('mem_balances');
        $info=$model->alias('a')
            ->join('left join xb_mem_info b on b.ID=a.UserID')
            ->field('a.Amount,a.UpdateTime,a.Description as Mobile')->where($where)
            ->limit($start_numbs,$per_numbs)
            ->order('a.ID desc')
            ->select();

        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
    }
    //推广收入  信用卡
    public function xypromot(){
        $Itype=I('get.Itype','2');
        $isdab=I('get.isdab','0');//0 未达标 1达标了
        $this->assign(array(
            'Itype'=>$Itype,
            'isdab'=>$isdab,
            'SEOTitle'=>'推广收入',
            ));
        $this->display();
    }
    //推广收入  网贷
    public function wdpromot(){
        $Itype=I('get.Itype','1');
        $isdab=I('get.isdab','0');//0 未达标 1达标了
        $this->assign(array(
            'Itype'=>$Itype,
            'isdab'=>$isdab,
            'SEOTitle'=>'推广收入',
            ));
        $this->display();
    }
    //获取推广收入 数据
    public function getpromotdata(){
        $Itype=I('post.Itype','1');
        $isdab=I('post.isdab','0');

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 4;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        $where['a.Itype']=array('eq',$Itype);
        $where['a.Status']=array('eq',$isdab);
        $where['a.IsDel']=array('eq','0');
        $where['a.UserID']=array('eq',session('loginfo')['UserID']);

        $info=M('apply_list')->alias('a')
              ->field('a.*,b.Name as goodname,b.Logurl,b.Settletime')
              ->join('left join xb_items b on a.ItemID=b.ID')
              ->order('a.ID desc')
              ->where($where)
              ->limit($start_numbs,$per_numbs)
              ->select();

        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            foreach($info as $k=>&$v){
                $v['Mobile']=substr_replace($v['Mobile'],'****',3,4);
                if (mb_strlen($v['Settletime']) > 8){
                    $v['Settletime'] = mb_substr($v['Settletime'], 0, 8).'...';
                }
            }
            $this->ajaxReturn(1,$info);
        }
    }


    /**
     * 推广收入列表
     */
    public function promot(){
        $this->display();
    }

    /**
     * 推广收入列表数据加载
     */
    public function promotdata(){
        $Num=I('post.num',0,'intval');
        if(!in_array($Num,array(2,3,4))){
            $this->ajaxReturn(0,"操作有误，请选择推广收入的类型");
        }
        $UserID=session('loginfo')['UserID'];
        $model=M('mem_balances');

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where['a.UserID']=$UserID;
        $where['a.Type']=0;

        if($Num==2 || $Num==3){
            $where['a.SruType']=$Num;
            //分页
            $info=$model->alias('a')
                ->join('left join xb_mem_info b on b.ID=a.UserID')
                ->join('left join xb_apply_listresult c on c.ID=a.oid')
                ->field('a.Amount,a.UpdateTime,c.Mobile,c.Name')->where($where)
                ->limit($start_numbs,$per_numbs)
                ->order('a.ID desc')
                ->select();
        }else{
            $where['a.SruType']=4;
            //分页
            $info=$model->alias('a')
                ->join('left join xb_mem_info b on b.ID=a.UserID')
                ->join('left join xb_zenxin_list c on c.ID=a.oid')
                ->field('a.Amount,a.UpdateTime,c.Mobile,c.TrueName as Name')->where($where)
                ->limit($start_numbs,$per_numbs)
                ->order('a.ID desc')
                ->select();
        }
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }

    }

    //借贷产品推广
    public function tgdetail(){
        $id=I('get.id','');
        //获取广告位
        $adds=M('sys_adcontent')->field('Name,Pic,Url')->where(array('AdvertisingID'=>'6','Status'=>'1'))->find();
        $iteminfo=M('items')->field('ID,Itype,Name,GoodsNo,Logurl,PartType,FeeIntro,BaseFee,StepFee,StepBase,StepInc1,StepInc2,StepUnit,StepIntro,AccountType')->where(array('ID'=>$id,'IsDel'=>'0'))->find();
        M('items')->where(array('ID'=>$id))->setInc('AppNumbs');
        if($iteminfo['PartType']){
            $PartTypeArr=unserialize($iteminfo['PartType']);
        }
        if($iteminfo['FeeIntro']){
            $FeeIntroArr=unserialize($iteminfo['FeeIntro']);
        }
        if($iteminfo['StepIntro']){
            $StepIntroArr=unserialize($iteminfo['StepIntro']);
        }
        if($iteminfo['AccountType']){
            $AccountTypeArr=unserialize($iteminfo['AccountType']);
        }
        //排行榜
        $dates=date('Y-m',strtotime('-1 month'));//上个月
        $dateArr=explode('-',$dates);
        $where=array();
        $where['GoodsNo']=array('eq',$iteminfo['GoodsNo']);
        $where["DATE_FORMAT(FROM_UNIXTIME(Opentime),'%Y-%m')"]=array('eq',$dates);
        $lists=M('apply_listresult')->field('ID,Mobile,Money')->where($where)->order('Money desc')->limit(10)->select();
        if($lists){
            foreach($lists as $k=>$v){
                $lists[$k]['Mobile']=substr_replace($lists[$k]['Mobile'],'****',3,4);
            }
        }
        $this->assign(array(
            'adds'=>$adds,
            'iteminfo'=>$iteminfo,
            'PartTypeArr'=>$PartTypeArr,
            'FeeIntroArr'=>$FeeIntroArr,
            'StepIntroArr'=>$StepIntroArr,
            'AccountTypeArr'=>$AccountTypeArr,
            'dateArr'=>$dateArr,
            'lists'=>$lists,
            ));
        $this->display();
    }
    //借贷产品推广 专属海报
    public function tgposter(){
        $id=I('get.id','');
        $iteminfo=M('items')->field('ID,Itype,Name,GoodsNo,Openurl,ZsUrl1,ZsUrl2,ZsUrl3,Keyeords,Describe')->where(array('ID'=>$id,'IsDel'=>'0'))->find();

        $uid=session('loginfo')['UserID'];
        if($iteminfo['ZsUrl1']){
            $iteminfo['ZsUrl1']='/Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl1.png";
            if(!is_file('./Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl1.png")){
                PrintQrcode2($uid,$id,'ZsUrl1');
            }
            $iteminfo['ZsUrl1']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl1'];
        }
        if($iteminfo['ZsUrl2']){
            $iteminfo['ZsUrl2']='/Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl2.png";
            if(!is_file('./Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl2.png")){
                PrintQrcode2($uid,$id,'ZsUrl2');
            }
            $iteminfo['ZsUrl2']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl2'];
        }
        if($iteminfo['ZsUrl3']){
            $iteminfo['ZsUrl3']='/Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl3.png";
            if(!is_file('./Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl3.png")){
                PrintQrcode2($uid,$id,'ZsUrl3');
            }
            $iteminfo['ZsUrl3']="http://".$_SERVER['HTTP_HOST'].$iteminfo['ZsUrl3'];
        }
        if($iteminfo['Itype']=='1'){
            //平台网贷
            $shareurl=get_basic_info('SystemDomain')."Daibeishop/detail?uid=".$uid.'&id='.$id;
        }elseif($iteminfo['Itype']=='2'){
            //信用卡贷
            $shareurl=get_basic_info('SystemDomain')."Daibeishop/cdetail?uid=".$uid.'&id='.$id;
        }

        $this->assign(array(
            'iteminfo'=>$iteminfo,
            'shareurl'=>$shareurl,
            ));
        $appId = C('Help.Wachet_AppId');
        $appSecret = C('Help.Wachet_AppSecret');;
        $jssdk = new \Extend\JSSDK($appId,$appSecret);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
        $signPackage['appId'] = $appId;
        $this->assign('signPackage',$signPackage);
        //获取推广的数据
        $shuju['Keyeords'] = $iteminfo["Keyeords"];
        $shuju['Describe'] = $iteminfo["Describe"];
        $this->assign('shuju',$shuju);
        $this->display();
    }
    //融客店
    public function rongke(){
        $id=session('loginfo')['UserID'];
        $url='/Upload/qrcode/'.$id.'/'.$id."__rongke.png";
        $url2="http://".$_SERVER['HTTP_HOST'].'/Upload/qrcode/'.$id.'/'.$id."__rongke.png";
        if(!is_file('./Upload/qrcode/'.$id.'/'.$id."__rongke.png")){
           $return=PrintQrcode($id);
        }
        $shareurl=get_basic_info('SystemDomain')."/Daibeishop/platweb?uid=".$id;
        $this->assign(array(
            'url'=>$url,
            'url2'=>$url2,
            'shareurl'=>$shareurl,
            ));
        $appId = C('Help.Wachet_AppId');
        $appSecret = C('Help.Wachet_AppSecret');;
        $jssdk = new \Extend\JSSDK($appId,$appSecret);
        //返回签名基本信息
        $signPackage = $jssdk->getSignPackage();
        $signPackage['appId'] = $appId;
        $this->assign('signPackage',$signPackage);
        //获取贷呗店的数据
        $shuju['LoanTitle'] = get_basic_info("LoanTitle");
        $shuju['LoanDesc'] = get_basic_info("LoanDesc");
        $shuju['LoanImage'] = "http://".$_SERVER['HTTP_HOST'].get_basic_info("LoanImage");
        $this->assign('shuju',$shuju);
        $this->display();
    }
    //判断会员是否有分享的权限
    public function checkshares(){
        $Mtype=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mtype');
        $flag=true;//默认能分享
        if($Mtype=='1' && $GLOBALS['BasicInfo']['Isshare']=='2'){
            $flag=false;
        }
        if($flag){
            $url="/Member/share";
            $this->ajaxReturn(1,'校验成功!',$url);
        }else{
            $this->ajaxReturn(0,'仅代理会员才能分享!');
        }
    }
}