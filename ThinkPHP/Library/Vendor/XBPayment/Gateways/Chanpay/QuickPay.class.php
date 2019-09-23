<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-09-11 10:22
 * @功能说明：  畅捷快捷支付
 */

namespace Vendor\XBPayment\Gateways\Chanpay;

use Think\Request;
use Vendor\XBPayment\HuryUtils;

class QuickPay
{
    /**
     * 请求公共报文头 (除了`Memo` 其余不可空)
     */
    protected $Service = '';#接口名称
    protected $Version = '1.0';#接口版本
    protected $PartnerId = '';#合作者ID
    protected $InputCharset = 'UTF-8';#参数编码字符集
    protected $TradeDate = '';#格式yyyyMMdd
    protected $TradeTime = '';#格式HH:mm:ss
    protected $Sign = '';#签名
    protected $SignType = 'RSA';#签名方式
    protected $Memo = '';#String(10) 备注-可空

    protected $TrxId;#商户网站唯一订单号

    /**
     * 业务参数 公用的配置值
     */
    protected $ExpiredTime = '15m';#订单有效期，取值范围：1m～48h。单位为分，如1.5h，可转换为90m。用来标识本次鉴权订单有效时间，超过该期限则该笔订单作废

    protected $private_key;#私钥
    protected $public_key;#公钥

    /**
     * @var string api地址
     */
    protected $apiUrl = 'https://pay.chanpay.com/mag-unify/gateway/receiveOrder.do';

    public $sandbox = false;
    protected $sandbox_url = 'https://pay.chanpay.com/mag-unify/gateway/receiveOrder.do';

    static $auth_status = [
        'SUCCESS' => 1,
        'FAIL' => 2,
        'PROCESS' => 3,
        'AUTH_SUCCESS' => 1,
        'AUTH_FAIL' => 2,
        'AUTH_PROCESS' => 3,
        'BIND_SUCCESS' => 4,
        'BIND_FAIL' => 5,
        'BIND_PROCESS' => 6,
        'PAY_SUCCESS' => 7,
        'PAY_FAIL' => 8,
        'PAY_PROCESS' => 9,
    ];
    static $order_type = [
        'TYPE_AUTH' => 1,
        'TYPE_PAY_HK' => 2,#还款
        'TYPE_PAY_XJ' => 3,#续借
        'TYPE_PAYING' => 4,#代付
    ];
    static $order_table = [
        '2' => 'loans_hklist',#还款
        '3' => 'loans_xjapplylist',#续借
    ];
    static $order_table_status = [
        'S' => '1',
        'F' => '2',
        'P' => '3',
    ];
    /**
     * 表xb_applylist
     * 字段`LoanStatus`
     * @var array
     */
    static $applylist_table_status = [
        'S' => '3',#3已完成
        'F' => '4',#4已取消
        'P' => '6',#6还款处理中
    ];
    /**
     * 表loans_hklist || loans_xjapplylist
     * 字段`PayStatus`
     * @var array
     */
    static $business_table_paystatus = [
        'S' => '1',#1已支付
        'F' => '2',#2支付失败
        'P' => '3',#3支付处理中
    ];
    /**
     * 表applylist
     * 字段`ReplaymentType`
     * @var array
     */
    static $applylist_ReplaymentType = [
        'alipay' => '1',#支付宝
        'wxpay' => '2',#微信
        'ylpay' => '3',#银联
        'dfpay' => '4',#代付
    ];


    public function __construct($PartnerId = null, $config = null)
    {
        $this->private_key = file_get_contents(__DIR__.'/private_key.pem');
        $this->public_key = file_get_contents(__DIR__.'/public_key.pem');

        if($this->sandbox) $this->apiUrl = $this->sandbox_url;
    }

    /**
     * @param $bankNo 卡号
     * @param $certNo 证件号
     * @param $certName 持卡人姓名
     * @param $certPhone 银行预留手机号
     * @param $NotifyUrl 回调处理地址
     * @param $SmsFlag 是否下发短信
     * @return mixed
            TrxId	商户网站唯一订单号	String(32)	商户网站唯一订单号	不可空	6741334835157966
            OrderTrxid	畅捷流水号	String(32)		不可空	101148826689730959160
            InstUrl	跳转地址	String(300)	跳转地址（渠道侧返回）	不可空	当请求接口类型为银行采集方式时返回该字段
            Status	鉴权状态	String(2)	S成功 F失败 P 处理中	不可空	例：S
            RetCode	业务返回码	String(64)	参见附录	可空	例：PARTNER_ID_NOT_EXIST
            RetMsg	返回描述	String (200)		可空	例：合作方Id不存在
            AppRetcode	应用返回码	String(8)	参见附录5.1.4	可空	QT000000
            AppRetMsg	应用返回描述	String (200)	参见附录5.1.4	可空	交易成功
            Extension	扩展字段	String(4000)	响应基本参数扩展字段	可空	json格式：[{'key1':'value','key2':'value2'}]
     */
    public function auth_bind_request($MerUserId, $bankNo, $certNo, $certName, $certPhone, $NotifyUrl, $SmsFlag = '1')
    {
        $pubKey = $this->getPublicKey();

        $this->setService('nmg_biz_api_auth_req');

        $postData = array();

        $postData['TrxId'] = $this->withTrxId($this->createOrderNo());
        $postData['MerchantNo'] = $this->getPartnerId();
        $postData['ExpiredTime'] = $this->ExpiredTime;

        $postData['MerUserId'] =   $MerUserId; //用户标识
        $postData['BkAcctTp'] =   '01'; //卡类型（00 –银行贷记账户;01 –银行借记账户;）
        $postData['BkAcctNo'] =   HuryUtils::rsaEncrypt($bankNo, $pubKey); //卡号
        $postData['IDTp'] =   '01'; //证件类型，01：身份证
        $postData['IDNo'] =   HuryUtils::rsaEncrypt($certNo, $pubKey); //证件号
        $postData['CstmrNm'] =   HuryUtils::rsaEncrypt($certName, $pubKey); //持卡人姓名
        $postData['MobNo'] =   HuryUtils::rsaEncrypt($certPhone, $pubKey); //银行预留手机号
        $postData['CardCvn2'] =   HuryUtils::rsaEncrypt('', $pubKey); //CVV2码，当卡类型为信用卡时必填
        $postData['CardExprDt'] =   HuryUtils::rsaEncrypt('', $pubKey); //有效期，当卡类型为信用卡时必填
        $postData['SmsFlag'] =   $SmsFlag; //短信发送标识，0：不发送短信1：发送短信
        $postData['NotifyUrl'] = $NotifyUrl;//异步通知地址
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        return $jsonObject;
    }

    /**
     * 绑卡查询接口
     * @param $MerUserId
     * @param $BankNo
     * @return mixed
     *
     */
    public function auth_bind_query($MerUserId, $BankNo, $TrxId)
    {

        $this->setService('nmg_api_auth_info_qry');

        $postData = array();

        // 4.4.2.10	绑卡查询接口 业务参数
        #$postData['TrxId'] = $TrxId; //外部流水号
        $postData['TrxId'] = $this->withTrxId($this->createOrderNo());//外部流水号
        $postData['MerUserId'] =   $MerUserId; //用户标识
        $postData['CardBegin'] = substr($BankNo, 0, 6); //卡号前6位
        $postData['CardEnd'] = substr($BankNo, -4);//卡号后4位
        $postData['BkAcctTp'] =   '01'; //卡类型（00 –银行贷记账户;01 –银行借记账户;）
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        return $jsonObject;
    }
    /**
     * 鉴权解绑
     * @param $MerUserId
     * @param $BankNo
     * @return mixed
     *
     */
    public function auth_unbind($MerUserId, $BankNo)
    {

        $this->setService('nmg_api_auth_unbind');

        $postData = array();

        // 4.4.2.10	绑卡查询接口 业务参数
        $postData['TrxId'] = $this->createOrderNo(); //外部流水号
        $postData['MerUserId'] =   $MerUserId; //用户标识
        $postData['CardBegin'] = substr($BankNo, 0, 6); //卡号前6位
        $postData['CardEnd'] = substr($BankNo, -4);//卡号后4位
        $postData['UnbindType'] =   '1'; //0 为物理删除，1 为逻辑删除
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        return $jsonObject;
    }
    /**
     * 绑卡查询接口
     * @param $MerUserId
     * @param $BankNo
     * @return mixed
     *
     */
    public function auth_bind_sms_confirm($TrxId, $SmsCode)
    {

        $this->setService('nmg_api_auth_sms');

        $postData = array();

        // 4.4.2.3. 鉴权绑卡确认接口（API） 业务参数
        $postData['TrxId'] = $this->withTrxId($this->createOrderNo()); //外部流水号
        $postData['OriAuthTrxId'] =   $TrxId; //原鉴权绑卡功能商户
        $postData['SmsCode'] =   $SmsCode; //鉴权短信验证码
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        #$jsonObject = json_decode('{"AcceptStatus":"F","AppRetMsg":"此卡已绑定，请勿重复绑卡","AppRetcode":"QT300008","InputCharset":"UTF-8","PartnerId":"200001280051","RetCode":"CARD_IS_BIND","RetMsg":"此卡已绑定，请勿重复绑卡","Sign":"KctR36hVuAE\/zJYCsKbUerDpAGGY82D16P85FBR69Cas\/mVMDZpMvSACDzHUZTnn2RPvA4T9F2RLDP0ThUgdX7\/03TmJBK3ETeCoFDR710RhVSGzBTlovcZwdjthrvJeeSX4\/BtMBYgTwyQ3p55DuzKll7Ey0EnTH2RR4X+euTk=","SignType":"RSA","Status":"F","TradeDate":"20180913","TradeTime":"152657","TrxId":"531744dc23f729dbcb6f9a4e78c0798b"}');

        return $jsonObject;
    }

    /**
     * 支付请求接口
     * @param $MerUserId
     * @param $BankNo
     * @return mixed
     */
    public function payment($MerUserId, $BankNo, $OrderAmount, $OrderName, $OrderDesc, $NotifyUrl)
    {

        $this->setService('nmg_biz_api_quick_payment');

        $postData = array();

        // 4.4.2.5. 支付请求接口（API） 业务参数
        $postData['TrxId'] = $this->withTrxId( $this->createOrderNo() ); //外部流水号
        $postData['SellerId'] =   $this->getPartnerId(); //商户编号，调用畅捷子账户开通接口获取的子账户编号;该字段可以传入平台id或者平台id下的子账户号;作为收款方使用；与鉴权请求接口中MerchantNo保持一致
        $postData['SubMerchantNo'] =   $this->getPartnerId(); //子商户，在畅捷商户自助平台申请开通的子商户，用于自动结算
        $postData['ExpiredTime'] =   $this->ExpiredTime; //订单有效期，取值范围：1m～48h。单位为分，如1.5h，可转换为90m。用来标识本次鉴权订单有效时间，超过该期限则该笔订单作废
        $postData['MerUserId'] =   $MerUserId; //用户标识
        $postData['TradeType'] =   '11'; //交易类型（即时 11 担保 12）
        $postData['CardBegin'] = substr($BankNo, 0, 6); //卡号前6位
        $postData['CardEnd'] = substr($BankNo, -4);//卡号后4位
        $postData['TrxAmt'] =   $OrderAmount; //交易金额
        $postData['EnsureAmount'] =   ''; //担保金额
        $postData['SmsFlag'] =   '1'; //短信发送标识,0：不发送短信 1：发送短信
        $postData['OrdrName'] =   $OrderName; //商品名称
        $postData['OrdrDesc'] =   $OrderDesc; //商品详情
        $postData['RoyaltyParameters'] = '';      //"[{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'},{'userId':'13890009900','PID':'2','account_type':'101','amount':'100.00'}]"; //退款分润账号集
        $postData['NotifyUrl'] = $NotifyUrl;//异步通知地址
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        #$jsonObject = json_decode('{"TrxId":"95306893fd34e1025d151185cf2994d7","AcceptStatus":"S","AppRetMsg":"测试"}');

        return $jsonObject;
    }

    /**
     * 支付 短信确认接口
     * @param $MerUserId
     * @param $BankNo
     * @return mixed
     *
     */
    public function payment_sms_confirm($TrxId, $SmsCode, $Extension = '')
    {

        $this->setService('nmg_api_quick_payment_smsconfirm');

        $postData = array();

        // 4.4.2.3. 鉴权绑卡确认接口（API） 业务参数
        $postData['TrxId'] = $this->withTrxId($this->createOrderNo()); //外部流水号
        $postData['OriPayTrxId'] = $TrxId; //原鉴权绑卡功能商户
        $postData['SmsCode'] =   $SmsCode; //鉴权短信验证码
        $postData['Extension'] = $Extension;//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        #$jsonObject = json_decode('{"AcceptStatus":"S","AppRetMsg":"交易处理中","AppRetcode":"QT100000","InputCharset":"UTF-8","OrderTrxid":"101153690853742783760","PartnerId":"200001280051","PayTrxId":"301153690853741861806","RetCode":"S0001","RetMsg":"处理中","Sign":"HFZuNZmdeHMs5o15gmfislo4UlKWl09Rb2n+LzPIc7\/HApe\/TufFOjMgKKeTAXbyx03KWfDoB\/iwR2hjwXECanuFLXf\/Z4tQ13OJlGcdwqfRDtdpEl4Z2pUloWEjLmZoqUbDstoS63BrzS1mX07LjqoSDR3Xq3vmkzDffVlzKEo=","SignType":"RSA","Status":"P","TradeDate":"20180914","TradeTime":"150233","TrxId":"'.$TrxId.'"}');

        return $jsonObject;
    }


    /**
     * 短信验证码重发接口
     * @param $TrxId
     * @param $OriTrxId
     * @param $TradeType 鉴权订单：auth_order    支付订单；pay _order
     * @param string $Extension
     * @return mixed
        TrxId	商户网站唯一订单号	String(32)	商户网站唯一订单号	不可空	6741334835157966
        OrderTrxId	畅捷流水号	String(32)	畅捷支付支付系统内部流水号	不可空	101148826689730959160
        Status	业务状态	String(2)	S成功 F失败 P 处理中	不可空	例：S
        RetCode	业务返回码	String(64)	参见附录	可空	例：PARTNER_ID_NOT_EXIST
        RetMsg	返回描述	String (200)		可空	例：合作方Id不存在
        AppRetcode	应用返回码	String(8)	参见附录5.1.4	可空	QT000000
        AppRetMsg	应用返回描述	String (200)	参见附录5.1.4	可空	交易成功
        Extension	扩展字段	String(4000)	响应基本参数扩展字段	可空	json格式：[{'key1':'value','key2':'value2'}]
     */
    public function sms_resend($TrxId, $OriTrxId, $TradeType, $Extension = '')
    {

        $this->setService('nmg_biz_api_quick_payment');

        $postData = array();

        // 4.4.2.5. 支付请求接口（API） 业务参数
        $postData['TrxId'] = $TrxId; //外部流水号
        $postData['OriTrxId'] =   $OriTrxId; //填写原交易的订单号
        $postData['TradeType'] =   $TradeType; //原业务订单类型，鉴权订单：auth_order，支付订单；pay
        $postData['Extension'] = $Extension;//扩展字段

        $jsonObject = $this->requset( $this->constructQuery($postData) );

        return $jsonObject;
    }



    #绑卡查询接口
    public function requset($query)
    {
        $rowData = HuryUtils::httpGet($this->apiUrl.'?'.$query);

        return json_decode($rowData);
    }

    /**
     * 生成外部流水号
     */
    public function createOrderNo()
    {
        return HuryUtils::create_uuid();
    }

    /**
     * @return bool/array
     * bool  : 出现必需字段为空是返回false 并记录该字段
     * array : 存储必需字段的键值数组
     */
    public function constructQuery($merD)
    {
        list($this->TradeDate, $this->TradeTime) = HuryUtils::trade_date_time();

        $data = array(
            'Service' => $this->getService(),
            'Version' => $this->getVersion(),
            'PartnerId' => $this->getPartnerId(),
            'InputCharset' => $this->getInputCharset(),
            'TradeDate' => $this->TradeDate,
            'TradeTime' => $this->TradeTime,
            'SignType' => $this->getSignType(),
            'Memo' => '',
        );

        $params = array_merge($data, $merD);

        $params['Sign'] = HuryUtils::rsaSign($params, $this->getPrivateKey());
        $query = http_build_query($params);

        return $query;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->Service;
    }


    /**
     * @param mixed $Service
     */
    public function setService($Service)
    {
        $this->Service = $Service;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->Version;
    }

    /**
     * @return string
     */
    public function getPartnerId()
    {
        return $this->PartnerId;
    }

    /**
     * @return string
     */
    public function getInputCharset()
    {
        return $this->InputCharset;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->Sign;
    }

    /**
     * @param string $Sign
     */
    public function setSign($Sign)
    {
        $this->Sign = $Sign;
    }

    /**
     * @return string
     */
    public function getSignType()
    {
        return $this->SignType;
    }

    /**
     * @return bool|string
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * @return bool|string
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * @return mixed
     */
    public function getTrxId()
    {
        return $this->TrxId;
    }

    /**
     * @param mixed $TrxId
     */
    public function setTrxId($TrxId)
    {
        $this->TrxId = $TrxId;
    }

    /**
     * set and get
     * @param $TrxId
     * @return mixed
     */
    public function withTrxId($TrxId)
    {
        return $this->TrxId = $TrxId;
    }

    #记录回调的每一步
    public static function recordNotifyStep($stepName, $putContents, $flur = false)
    {
        HuryUtils::recordNotifyStep('Chanpay/quickPay_notify', $stepName, $putContents, $flur);
    }

    public function acccessChanpayH5($MerUserId, $OrderNo, $NotifyUrl)
    {
        $this->setService('nmg_page_api_auth_req');

        $postData = array();

        // 4.4.2.2. 鉴权绑卡请求（畅捷前台） 业务参数
        $postData['TrxId'] = $OrderNo; //外部流水号
        $postData['MerchantNo'] =   $this->getPartnerId(); //商户编号，与支付请求接口中SellerId保持一致
        $postData['ExpiredTime'] =   $this->ExpiredTime; //订单有效期，取值范围：1m～48h。单位为分，如1.5h，可转换为90m。用来标识本次鉴权订单有效时间，超过该期限则该笔订单作废
        $postData['MerUserId'] =   $MerUserId; //用户标识
        $postData['NotifyUrl'] = $NotifyUrl;//异步通知地址
        $postData['Extension'] = '';//扩展字段

        $jsonObject = $this->apiUrl.'?'.$this->constructQuery($postData);

        return $jsonObject;
    }

    /**
     * @return mixed
     */
    public function getExpiredTime()
    {
        return $this->ExpiredTime;
    }

    /**
     * @param string $PartnerId
     */
    public function setPartnerId($PartnerId)
    {
        $this->PartnerId = $PartnerId;
    }

}
