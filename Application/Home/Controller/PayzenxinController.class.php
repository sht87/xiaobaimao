<?php
namespace Home\Controller;


use Vendor\XBPayzenxin\XBPayment;
use Vendor\XBPayzenxin\XBPaymentFactory;

class PayzenxinController extends HomeController
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * @request_url Payment/brandCard?order_no=1808106205943400
     */
    public function bankCard()
    {
        $order_no = I('get.order_no');
        $UserID = I('get.uid',0,'intval');
        $Type = I('get.type',0,'intval');

        $orderEntity = XBPaymentFactory::order();

        $zenxinOrder=$orderEntity->find($order_no);
        if(!$zenxinOrder){
            if($Type==0){
                $this->error("订单不存在!",'/member/index');
            }else{
                $this->error("订单不存在!",'/daizenxin/index?uid='.$UserID);
            }
        }


        $this->assign([
            'pageName' => '银行卡支付',
            'Type' => $Type,
            'UserID' => $UserID,
            'orderAmount' => $orderEntity->getAmount(),
            'orderNo' => $orderEntity->getOrderNo(),
            'SendSmsActionUrl' => '/Payzenxin/NowPay',
            'NowPayActionUrl' => '/Payzenxin/NowPayConfirm',
        ]);

        $this->display();
    }

    /**
     * 提交银行卡四要素 成功是会发送短信
     */
    public function NowPay()
    {
        $XBPayment = new XBPayment(XBPayment::SCENE_H5);

        $XBPayment->NowPay(I('post.'));
    }
    /**
     * 直接支付 短信验证
     */
    public function NowPayConfirm()
    {
        $XBPayment = new XBPayment(XBPayment::SCENE_H5);

        $UserID = $_SESSION['loginfo']['UserID'];
        $OrderNo = I('post.OrderNo');
        $SmsCode = I('post.Smscode');
        $XBPayment->NowPayConfirm($UserID, $OrderNo, $SmsCode);
    }


}