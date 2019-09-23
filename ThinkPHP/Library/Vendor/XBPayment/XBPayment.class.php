<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-08-08 10:51
 * @功能说明：  支付流程
 * @使用说明：  $entity 必须实现error方法
 */


namespace Vendor\XBPayment;


class XBPayment
{
    Const SCENE_H5 = 1;
    Const SCENE_API = 2;
    private $scene;
    private $entity;

    public $factory;

    /**
     * XBPayment constructor.
     * @param $scenes h5|api
     */
    public function __construct($scene)
    {
        $this->scene = $scene;
        $this->factory = new XBPaymentFactory;
    }

    /**
     * 银行卡信息提交
     */
    public function NowPay($requestData)
    {
        #$XBPayment = new XBPayment(XBPayment::SCENE_H5);

        $compact = $requestData;

        if(!isset($compact['OrderNo']) && empty($compact['OrderNo'])) return $this->ajaxReturn(0,'订单号不存在');
        #if(!isset($compact['MerUserId']) && empty($compact['MerUserId'])) return $this->ajaxReturn(0,'用户标识不能为空');
        if(!isset($compact['BankNo']) && empty($compact['BankNo'])) return $this->ajaxReturn(0,'卡号不能为空');
        if(!isset($compact['CertNo']) && empty($compact['CertNo'])) return $this->ajaxReturn(0,'证件号不能为空');
        if(!isset($compact['CertName']) && empty($compact['CertName'])) return $this->ajaxReturn(0,'姓名不能为空');
        if(!isset($compact['CertPhone']) && empty($compact['CertPhone'])) return $this->ajaxReturn(0,'手机号不能为空');
        #if(!isset($compact['OrderAmt']) && empty($compact['OrderAmt'])) return $this->ajaxReturn(0,'订单金额不能为空');


        $orderEntity = XBPaymentFactory::order();

        $OrderInfo = $orderEntity->find($compact['OrderNo']);

        if(!$OrderInfo) return $this->ajaxReturn('订单消失了');

        #$orderEntity->setOrderNo($compact['OrderNo']);
        $orderEntity->setBankCardNumber($compact['BankNo']);
        $orderEntity->setHolderName($compact['CertName']);
        $orderEntity->setHolderId($compact['CertNo']);
        $orderEntity->setHolderPhone($compact['CertPhone']);

        $compact['MerUserId'] = $orderEntity->getMemID();
        $compact['OrderAmt'] = $orderEntity->getAmount();
        $compact['Extension'] = '[{"order_no":"'.$compact['OrderNo'].'"}]';

        //调用支付
        $payer = XBPaymentFactory::Chanpay();

        $getedRes = $payer->paynow($compact);

        if(is_array($getedRes)) {
            list($getedStatus, $msg) = $getedRes;
            if(! $getedStatus) return $this->ajaxReturn(0, $msg);
        }

        $orderEntity->setTradeNo($getedRes->TrxId);

        $saveRes = $orderEntity->save();
        if(!$saveRes ) return $this->ajaxReturn('订单信息存储失败');

        return $this->ajaxReturn(1,'下发短信成功', ['payurl' => $getedRes]);
    }

    /**
     * @功能说明: 畅捷支付-支付确认接口
     * @传输方式: get
     * @提交网址: /Center/Chanpay/NowPayConfirm
     * @提交方式: {"token":"bce2675771dc92aa4d1818cf3c5e6c6fe7d9ca5e8b3d9044e1b1b57ddc11","client":"android","package":"android.ceshi","ver":"v1.1"}
     * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function NowPayConfirm($UserID, $OrderNo, $SmsCode)
    {

        if(empty($UserID)) return $this->ajaxReturn(0, 'UserId字段必填');
        if(empty($OrderNo)) return $this->ajaxReturn(0, 'OrderNo字段必填');
        if(empty($SmsCode)) return $this->ajaxReturn(0, 'SmsCode字段必填');

        /*$UserID = 8;
        $TrxId = '54c5de821340c912dd9a01a0d55ed606';
        $SmsCode = '494783';*/

        //初始化
        $payer = XBPaymentFactory::Chanpay();
        $order = XBPaymentFactory::order();

        if(!$OrderInfo = $order->find($OrderNo)) return $this->ajaxReturn(0, '订单消失了');

        $ores = $payer->quickpay->payment_sms_confirm($order->getTradeNo(), $SmsCode, '[{"order_no":"'.$OrderNo.'"}]');

        $payer->log('支付确认接口', $ores, true);

        if($ores->Status == 'S') {

            $res = $order->setStatusSuccess()->save();

            return $this->good('支付成功!');

        } elseif($ores->Status == 'P') {

            $res = $order->setStatusProcess()->save();

            return $this->good('支付成功, 交易处理中');
        }

        return $this->bad($ores->RetMsg);
    }

    public function bad($msg, $data = [], $extras = '')
    {
        if($this->isH5()) {
            $this->ajaxReturn(0, $msg, $data, $extras);
        } elseif($this->isApi()) {
            $json = json_encode([
                'result' => 0,
                'message' => $msg,
                'data' => $data,
                'extras' => $extras,
            ], \JSON_UNESCAPED_UNICODE);
            die($json);
        }
    }

    public function good($msg, $data = [], $extras = '')
    {
        if($this->isH5()) {
            $this->ajaxReturn(1, $msg, $data, $extras);
        } elseif($this->isApi()) {

            $json = json_encode([
                'result' => 1,
                'message' => $msg,
                'data' => $data,
                'extras' => $extras,
            ], \JSON_UNESCAPED_UNICODE);
            die($json);
        }
    }

    /**
     * AJAX返回数据标准
     * @param int $status  状态
     * @param string $msg  内容
     * @param mixed $data  数据
     * @param string $dialog  弹出方式
     */
    protected function ajaxReturn($status = 0, $msg = '成功', $data = '', $dialog = '')
    {
        $return_arr = array();
        if (is_array($status)) {
            $return_arr = $status;
        } else {
            $return_arr = array(
                'result' => $status,
                'message' => $msg,
                'des' => $data,
                'dialog' => $dialog
            );
        }
        ob_clean();
        echo json_encode($return_arr, \JSON_UNESCAPED_UNICODE);
        exit;
    }


    private function isH5()
    {
        return $this->scene == self::SCENE_H5;
    }
    private function isApi()
    {
        return $this->scene == self::SCENE_API;
    }

}
