<?php
/**
 * Created by PhpStorm.
 * User: 胡锐
 * Date: 2018/9/20
 * Time: 9:14
 */

namespace Vendor\XBPayzenxin\Gateways\Chanpay;
use Vendor\XBPayzenxin\AbstractPayer;
use Vendor\XBPayzenxin\HuryUtils;
use Vendor\XBPayzenxin\Request;

class Payer extends AbstractPayer
{
    public $paying = null;
    public $quickpay = null;

    public function __construct($config)
    {
        parent::__construct($config);

        #$this->paying = new Paying();
        $this->quickpay = new QuickPay();
        $this->quickpay->setPartnerId($this->config['app_id']);
        $this->config['app_key'] = $this->quickpay->getPublicKey();
        $this->config['app_secret'] = $this->quickpay->getPrivateKey();
    }

    public function paynow($compact)
    {
        if(!isset($compact['MerUserId'])) return [false, '用户标识不能为空'];
        if(!isset($compact['BankNo'])) return [false, '卡号不能为空'];
        if(!isset($compact['CertNo'])) return [false, '证件号不能为空'];
        if(!isset($compact['CertName'])) return [false, '姓名不能为空'];
        if(!isset($compact['CertPhone'])) return [false, '手机号不能为空'];
        if(!isset($compact['OrderAmt'])) return [false, '订单金额不能为空'];

        $Extension = isset($compact['Extension']) ? $compact['Extension'] : '';

        $pubKey = $this->config['app_key'];

        $this->quickpay->setService('nmg_zft_api_quick_payment');

        $postData = array();
        // 基本参数
        $postData['PartnerId'] = $this->config['app_id'];//商户号
        $postData['ReturnUrl'] = Request::instance()->domain().$this->config['return_url'];// 前台跳转url
        $postData['Memo'] = 'H5直接支付';

        // 4.4.2.8. 直接支付请求接口（畅捷前台） 业务参数
        $postData['TrxId'] = $this->quickpay->withTrxId($this->quickpay->createOrderNo()); //外部流水号
        $postData['SellerId'] =   $this->config['app_id']; //商户编号，调用畅捷子账户开通接口获取的子账户编号;该字段可以传入平台id或者平台id下的子账户号;作为收款方使用；与鉴权请求接口中MerchantNo保持一致
        $postData['SubMerchantNo'] =   $this->config['app_id']; //子商户，在畅捷商户自助平台申请开通的子商户，用于自动结算
        $postData['ExpiredTime'] =   $this->quickpay->getExpiredTime(); //订单有效期，取值范围：1m～48h。单位为分，如1.5h，可转换为90m。用来标识本次鉴权订单有效时间，超过该期限则该笔订单作废
        $postData['MerUserId'] =   $compact['MerUserId']; //用户标识
        $postData['BkAcctTp'] =   '01'; //卡类型（00 –银行贷记账户;01 –银行借记账户;）
        $postData['BkAcctNo'] =   HuryUtils::rsaEncrypt($compact['BankNo'], $pubKey); //卡号
        $postData['IDTp'] =   '01'; //证件类型，01：身份证
        $postData['IDNo'] =   HuryUtils::rsaEncrypt($compact['CertNo'], $pubKey); //证件号
        $postData['CstmrNm'] =   HuryUtils::rsaEncrypt($compact['CertName'], $pubKey); //持卡人姓名
        $postData['MobNo'] =   HuryUtils::rsaEncrypt($compact['CertPhone'], $pubKey); //银行预留手机号
        $postData['CardCvn2'] =   HuryUtils::rsaEncrypt('', $pubKey); //CVV2码，当卡类型为信用卡时必填
        $postData['CardExprDt'] =   HuryUtils::rsaEncrypt('', $pubKey); //有效期，当卡类型为信用卡时必填
        $postData['TradeType'] =   '11'; //交易类型（即时 11 担保 12）
        $postData['TrxAmt'] =   $compact['OrderAmt']; //交易金额
        $postData['EnsureAmount'] =   ''; //担保金额
        $postData['OrdrName'] =   '征信查询费用支付'; //商品名称
        $postData['OrdrDesc'] =   ''; //商品详情
        $postData['RoyaltyParameters'] = '';      //"[{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'},{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'}]"; //退款分润账号集
        $postData['NotifyUrl'] = Request::instance()->domain().$this->config['notify_url'];//异步通知地址
        $postData['AccessChannel'] = 'wap';//用户终端类型；web,wap
        $postData['Extension'] = $Extension;//扩展字段
        $res = $this->requset( $this->quickpay->constructQuery($postData) );
        if($res && isset($res->AcceptStatus) && $res->AcceptStatus == 'F') {
            return [false, $res->RetMsg];
        }

        return $res;
    }

    public function requset($query)
    {
        $order_url = $this->config['api_url'].'?'.$query;

        $rowData = HuryUtils::httpGet($order_url);

        $this->log('requset-1', $order_url);
        $this->log('requset-2', $rowData, true);

        if(is_null(json_decode($rowData))) return $order_url;

        return json_decode($rowData);
    }

    public function payurl($compact)
    {
        if(!isset($compact['MerUserId'])) return [false, '用户标识不能为空'];
        if(!isset($compact['BankNo'])) return [false, '卡号不能为空'];
        if(!isset($compact['CertNo'])) return [false, '证件号不能为空'];
        if(!isset($compact['CertName'])) return [false, '姓名不能为空'];
        if(!isset($compact['CertPhone'])) return [false, '手机号不能为空'];
        if(!isset($compact['OrderAmt'])) return [false, '订单金额不能为空'];

        $Extension = isset($compact['Extension']) ? $compact['Extension'] : '';

        $pubKey = $this->config['app_key'];

        $this->quickpay->setService('nmg_quick_onekeypay');

        $postData = array();
        // 基本参数
        $postData['PartnerId'] = $this->config['app_id'];//商户号
        $postData['ReturnUrl'] = Request::instance()->domain().$this->config['return_url'];// 前台跳转url
        $postData['Memo'] = 'H5直接支付';

        // 4.4.2.8. 直接支付请求接口（畅捷前台） 业务参数
        $postData['TrxId'] = $this->quickpay->withTrxId($this->quickpay->createOrderNo()); //外部流水号
        $postData['SellerId'] =   $this->config['app_id']; //商户编号，调用畅捷子账户开通接口获取的子账户编号;该字段可以传入平台id或者平台id下的子账户号;作为收款方使用；与鉴权请求接口中MerchantNo保持一致
        $postData['SubMerchantNo'] =   $this->config['app_id']; //子商户，在畅捷商户自助平台申请开通的子商户，用于自动结算
        $postData['ExpiredTime'] =   $this->quickpay->getExpiredTime(); //订单有效期，取值范围：1m～48h。单位为分，如1.5h，可转换为90m。用来标识本次鉴权订单有效时间，超过该期限则该笔订单作废
        $postData['MerUserId'] =   $compact['MerUserId']; //用户标识
        $postData['BkAcctTp'] =   '01'; //卡类型（00 –银行贷记账户;01 –银行借记账户;）
        $postData['BkAcctNo'] =   HuryUtils::rsaEncrypt($compact['BankNo'], $pubKey); //卡号
        $postData['IDTp'] =   '01'; //证件类型，01：身份证
        $postData['IDNo'] =   HuryUtils::rsaEncrypt($compact['CertNo'], $pubKey); //证件号
        $postData['CstmrNm'] =   HuryUtils::rsaEncrypt($compact['CertName'], $pubKey); //持卡人姓名
        $postData['MobNo'] =   HuryUtils::rsaEncrypt($compact['CertPhone'], $pubKey); //银行预留手机号
        $postData['CardCvn2'] =   HuryUtils::rsaEncrypt('', $pubKey); //CVV2码，当卡类型为信用卡时必填
        $postData['CardExprDt'] =   HuryUtils::rsaEncrypt('', $pubKey); //有效期，当卡类型为信用卡时必填
        $postData['TradeType'] =   '11'; //交易类型（即时 11 担保 12）
        $postData['TrxAmt'] =   $compact['OrderAmt']; //交易金额
        $postData['EnsureAmount'] =   ''; //担保金额
        $postData['OrdrName'] =   '征信查询费用支付'; //商品名称
        $postData['OrdrDesc'] =   ''; //商品详情
        $postData['RoyaltyParameters'] = '';      //"[{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'},{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'}]"; //退款分润账号集
        $postData['NotifyUrl'] = Request::instance()->domain().$this->config['notify_url'];//异步通知地址
        $postData['AccessChannel'] = 'wap';//用户终端类型；web,wap
        $postData['Extension'] = $Extension;//扩展字段

        $res = $this->requset( $this->quickpay->constructQuery($postData) );

        if($res && isset($res->AcceptStatus) && $res->AcceptStatus == 'F') {
            return [false, $res->RetMsg];
        }

        return $res;
    }

    public function notifyReady()
    {

        $requestData = $_POST;

        $this->log('1.0Notify', file_get_contents('php://input'), true);

        if(!isset($requestData['outer_trade_no'])) {
            $this->log('2.0Notify', '非法请求', true);

            throw new \Exception('非法请求');
        }

        return array(
            'OrderNo' => null,//商户的订单号
            'TradeNo' => $requestData['outer_trade_no'],//支付方流水号
            'OrderAmt' => null,//订单金额
            'TradeAmt' => $requestData['trade_amount'],//支付金额
            'PayStatus' => $requestData['trade_status'] == 'TRADE_SUCCESS' || $requestData['trade_status'] == 'TRADE_FINISHED' ? 'S' : 'F',//支付状态 必须 S或者F或者P
        );
    }

    public function mockNotify($data)
    {
        $url = Request::instance()->domain().$this->config['notify_url'];

        $res = HuryUtils::httpRequest($url, 'POST', $data, false, '');

        return $res;
    }

    public function log($stepName, $putContents, $flur = false)
    {
        return HuryUtils::recordNotifyStep(__DIR__.'/log/log', $stepName, $putContents, $flur);
    }
}