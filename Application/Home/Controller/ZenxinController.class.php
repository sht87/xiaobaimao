<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/12 9:56
 * 功能说明: 征信查询接口
 */
namespace Home\Controller;

class ZenxinController extends UserController
{	
	//征信列表
	public function zxlist(){
		$this->display();
	}
	//获取查询的征信列表
    public function getdata(){
    	sleep(1);
        //搜索条件
        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        $where['UserID']=array('eq',session('loginfo')['UserID']);
        $where['IsDel']=array('eq','0');
        //分页
        $model=M('zenxin_list');
        $info=$model->field('ID,TrueName,Mobile,Checktime,Status')->where($where)->limit($start_numbs,$per_numbs)->order('ID desc')->select();
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
    }

	//征信查询页面
	public function index(){
		$this->display();
	}
	//数据提交 添加征信查询记录
	public function adddata(){
		if(IS_POST){
			$TrueName=I('post.TrueName','');
			$IDCard=I('post.IDCard','');
			$Mobile=I('post.Mobile','');
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
				'UserID'=>session('loginfo')['UserID'],
				'OrderSn'=>date(ymd).rand(1,9).date(His).rand(111,999),
//				'OrderAmount'=>$GLOBALS['BasicInfo']['ZxPay'],
                'OrderAmount'=>0.01,
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
			));
		$this->display();
	}
	//支付征信订单
	public function orderpay(){
		if(IS_POST){
			$id=I('post.id','');
			$PayType=I('post.PayType','');
		    $orderinfo=M('zenxin_list')->where(array('ID'=>$id,'UserID'=>session('loginfo')['UserID'],'Status'=>'1','IsDel'=>'0'))->find();
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
			if($PayType=='3'){
				//余额支付
				//校验
				$Balance=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Balance');
				if($Balance<$paymoney){
					$this->ajaxReturn(0,'余额不足,请选择其他支付方式!');
				}
				$model=M();
				$model->startTrans();
				$result=$model->table('xb_mem_info')->where(array('ID'=>session('loginfo')['UserID']))->setDec('Balance',$paymoney);
				if($result){
					//修改状态
					$sadata=array(
						'Status'=>'2',
						'PayType'=>'3',
						'PayTime'=>date('Y-m-d H:i:s'),
						);
					$rest=$model->table('xb_zenxin_list')->where(array('ID'=>$id))->save($sadata);
					if($rest){
						$model->commit();
						//支付成功,记录余额消费明细
						$desc='征信查询支付,订单号:'.$orderinfo['OrderSn'];
						balancerecord('1',$paymoney1,session('loginfo')['UserID'],'','',$desc,'',$id);
						//三级分销 分佣
						$Intro=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mobile');
						shareOrderMoneyRecord($paymoney1,session('loginfo')['UserID'],'4',$desc,$Intro,$id);

						//查询征信
				        //$MiguanApi = new \Extend\MiguanApi($GLOBALS['BasicInfo']['Mgaccount'],$GLOBALS['BasicInfo']['Mgsecret'],$orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
                        //京东蜜罐查询
                        $MiguanApi = new \Extend\MiguanJingdongApi($orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
                        $mrest=$MiguanApi->searchs();
				        if($mrest['result']=='0'){
				        	//查询失败
				        	$updata=array(
				        		'Errtxt'=>$mrest['message'],
				        		'Checktime'=>date('Y-m-d H:i:s'),
				        		'Status'=>'3',
				        		);
				        	M('zenxin_list')->where(array('ID'=>$id))->save($updata);
				        	$this->ajaxReturn(0,"征信查询失败,请联系客服");
				        }elseif($mrest['result']=='1'){
				        	//查询成功
				        	$searchinfo=serialize($mrest['message']);
				        	$updata=array(
				        		'LoanInfos'=>$searchinfo,
				        		'Checktime'=>date('Y-m-d H:i:s'),
				        		'Status'=>'4',
				        		);
				        	M('zenxin_list')->where(array('ID'=>$id))->save($updata);
				        	$pay_result = 1;
	                        $pay_mess = "征信查询成功";
	                        $href ="/Zenxin/zdetail?id=".$id;
	                        $dialog = '';
				        }
					}else{
                        $model->rollback();
                        $this->ajaxReturn(0,"余额支付失败");
                    }
				}
			}elseif($PayType=='1'){
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
                $href = "/Payzenxin/bankCard?order_no=".$orderinfo['OrderSn'];
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
		$infos=M('zenxin_list')->where(array('ID'=>$id,'UserID'=>session('loginfo')['UserID'],'IsDel'=>'0'))->find();
		if($infos['LoanInfos']){
			$infos['LoanInfos']=unserialize($infos['LoanInfos']);
			$infos['LoanInfos']=json_encode($infos['LoanInfos']);
			$infos['LoanInfos']=json_decode($infos['LoanInfos'],true);
		}
		$this->assign(array(
			'infos'=>$infos,
			));
		$this->display();
	}
	//再次查询征信
	public function againcheck(){
		if(IS_POST){
		 	$id=I('post.id','');
		 	$orderinfo=M('zenxin_list')->where(array('ID'=>$id,'UserID'=>session('loginfo')['UserID'],'IsDel'=>'0'))->find();
		 	if(!$orderinfo){
		 		$this->ajaxReturn(0,'无此查询记录!');
		 	}
		 	if($orderinfo['Status']=='2' || $orderinfo['Status']=='3'){
		 		//查询征信
		        //$MiguanApi = new \Extend\MiguanApi($GLOBALS['BasicInfo']['Mgaccount'],$GLOBALS['BasicInfo']['Mgsecret'],$orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
                //京东蜜罐查询
                $MiguanApi = new \Extend\MiguanJingdongApi($orderinfo['TrueName'],$orderinfo['IDCard'],$orderinfo['Mobile']);
                $mrest=$MiguanApi->searchs();
		        if($mrest['result']=='0'){
		        	//查询失败
		        	$updata=array(
		        		'Errtxt'=>$mrest['message'],
		        		'Checktime'=>date('Y-m-d H:i:s'),
		        		'Status'=>'3',
		        		);
		        	M('zenxin_list')->where(array('ID'=>$id))->save($updata);
		        	$this->ajaxReturn(0,"征信查询失败,请联系客服");
		        }elseif($mrest['result']=='1'){
		        	//查询成功
		        	$searchinfo=serialize($mrest['message']);
		        	$updata=array(
		        		'LoanInfos'=>$searchinfo,
		        		'Checktime'=>date('Y-m-d H:i:s'),
		        		'Status'=>'4',
		        		);
		        	M('zenxin_list')->where(array('ID'=>$id))->save($updata);
		        	$this->ajaxReturn(1,'征信查询成功');
		        }
		 	}else{
		 		$this->ajaxReturn(0,'记录状态不正确!');
		 	}
		}else{
			$this->ajaxReturn(0,'提交方式不正确!');
		}
	}
}