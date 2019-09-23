<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/12 9:56
 * 功能说明: 征信查询接口
 */
namespace Home\Controller;

class DaizenxinController extends HomeController
{
	//征信查询页面
	public function index(){
        $uid=I('get.uid',0,'intval');//推荐人的会员id
        $this->assign(array(
            'uid'=>$uid,
        ));
		$this->display();
	}
	//数据提交 添加征信查询记录
	public function adddata(){
		if(IS_POST){
			$TrueName=I('post.TrueName','');
			$IDCard=I('post.IDCard','');
			$Mobile=I('post.Mobile','');
			$uid=I('post.uid',0,'intval');
			//校验
			if(!$TrueName){
				$this->ajaxReturn(0,'真实姓名不能为空!');
			}
			if(!$IDCard){
				$this->ajaxReturn(0,'请输入身份证号!');
			}
			if(!$Mobile){
				$this->ajaxReturn(0,'请输入手机号码!');
			}
			if(!checkIdCard($IDCard)){
				$this->ajaxReturn(0,'身份证号码不正确!');
			}
			if(!is_mobile($Mobile)){
				$this->ajaxReturn(0,'手机号码不正确!');
			}
			$data=array(
				'UserID'=>$uid,
				'Type'=>1,
				'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
				'OrderAmount'=>$GLOBALS['BasicInfo']['ZxPay'],
				'TrueName'=>$TrueName,
				'IDCard'=>$IDCard,
				'Mobile'=>$Mobile,
				'Status'=>'1',
				);
			$result=M('zenxin_list')->add($data);
			if($result){
				$this->ajaxReturn(1,'信息提交成功!',$result);
			}else{
				$this->ajaxReturn(0,'数据提交失败!');
			}
		}else{
			$this->ajaxReturn(0,'数据提交失败!');
		}
	}
	//支付查询征信订单
	public function zxpay(){
		$id=I('get.id','');
        $uid=I('get.uid',0,'intval');//推荐人的会员id
		//判断是什么访问
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            $is_wx = 1;
        }else{
            $is_wx = 0;
        }
        $paymoney=M('zenxin_list')->where(array('ID'=>$id))->getField('OrderAmount');
		$this->assign(array(
			'id'=>$id,
			'is_wx'=>$is_wx,
			'paymoney'=>$paymoney,
            'uid'=>$uid
			));
		$this->display();
	}
	//支付征信订单
	public function orderpay(){
		if(IS_POST){
			$id=I('post.id','');
			$PayType=I('post.PayType','');
            $uid=I('post.uid',0,'intval');//推荐人的会员id

            $orderinfo=M('zenxin_list')->where(array('ID'=>$id,'UserID'=>$uid,'Status'=>'1','IsDel'=>'0'))->find();
            if(!$orderinfo){
				$this->ajaxReturn(0,'查询订单不存在!');
			}
			if(!$PayType){
				$this->ajaxReturn(0,'请选择支付方式!');
			}
			if(!in_array($PayType,array('1','2','3','4','chanpay'))){
				$this->ajaxReturn(0,'支付方式不正确!');
			}
			$paymoney1=$paymoney=$orderinfo['OrderAmount'];
			if(!$paymoney){
				$this->ajaxReturn(0,'征信支付金额异常,请联系管理员!');
			}
			//支付
			if($PayType=='1'){
				//微信支付
				$pay_result = 1;
                $pay_mess = "确定要使用微信支付?";
                $href = "/Wachet/payzenxin?id=".$orderinfo['ID']."&oid=".$orderinfo['OrderSn'];
                $dialog = '';
			}elseif($PayType=='2'){
				//支付宝支付
				$pay_result = 1;
                $pay_mess = "确定要使用支付宝支付?";
                $href = "/Alipay/payzenxin?id=".$orderinfo['ID']."&oid=".$orderinfo['OrderSn'];
                $dialog = '';
			}elseif($PayType=='4'){
				//微信支付
				$pay_result = 1;
                $pay_mess = "确定要使用微信支付?";
                $href = "/Wachet/payzenxin2?id=".$orderinfo['ID']."&oid=".$orderinfo['OrderSn'];
                $dialog = '';
			}elseif($PayType=='chanpay') {
                $pay_result = 1;
                $pay_mess = "确定要使用银行卡支付";
                $href = "/Payzenxin/bankCard?order_no=".$orderinfo['OrderSn']."&uid=".$uid."&type=1";
                $dialog = '';
            }
			$this->ajaxReturn($pay_result,$pay_mess,$href,$dialog);
		}else{
			$this->ajaxReturn(0,'提交方式不对');
		}
	}
	//征信详情页面
	public function zdetail(){
		$id=I('get.id','');
        $uid=I('get.uid',0,'intval');//推荐人的会员id

        sleep(3);
        $infos=M('zenxin_list')->where(array('ID'=>$id,'UserID'=>$uid,'IsDel'=>'0'))->find();
        if($infos['LoanInfos']){
			$infos['LoanInfos']=unserialize($infos['LoanInfos']);
			$infos['LoanInfos']=json_encode($infos['LoanInfos']);
			$infos['LoanInfos']=json_decode($infos['LoanInfos'],true);
		}
		$this->assign(array(
			'infos'=>$infos,
            'uid'=>$uid,
        ));
		$this->display();
	}
}