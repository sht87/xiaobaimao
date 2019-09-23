<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-09-11 10:22
 * @功能说明：  畅捷代付
 */

namespace Vendor\XBPayment\Gateways\Chanpay;

use Think\Request;

class Paying extends QuickPay
{
    protected $PartnerId = '200002180100';#合作者ID
    protected $CorpAcctNo = '1078760547@qq.com';#企业账号

    public function __construct($PartnerId = null)
    {
        $this->private_key = file_get_contents(__DIR__.'/private_key.pem');
        $this->public_key = file_get_contents(__DIR__.'/public_key.pem');

        if($this->sandbox) $this->apiUrl = $this->sandbox_url;
    }

    /**
     * 商户余额查询
     */
    public function queryBalance($AcctNo = '', $AcctName = '')
    {
        list($TradeDate, $TradeTime) = HuryUtils::trade_date_time();

        $postData = $needEncryptData = array();
        $needEncryptData['AcctNo'] = HuryUtils::rsaEncrypt($AcctNo, $this->getPublicKey()); //待查账号
        $needEncryptData['AcctName'] = HuryUtils::rsaEncrypt($AcctName, $this->getPublicKey());//待查户名

        $postData['Service'] = 'cjt_dsf';
        $postData['Version'] = '1.0';
        $postData['PartnerId'] = $this->getPartnerId();//商户号
        $postData['TradeDate'] = $TradeDate;
        $postData['TradeTime'] =  $TradeTime;
        $postData['InputCharset']= 'UTF-8';
        $postData['TransCode'] =  "C00005"; //功能码
        $postData['OutTradeNo'] = $this->createOrderNo(); //外部流水号

        $postData = array_merge($postData, $needEncryptData);
        $postData['Sign']= HuryUtils::rsaSign($postData, $this->getPrivateKey());
        $postData['SignType'] = $this->getSignType(); //签名类型

        $query = http_build_query($postData);

        $jsonObject = $this->requset( $query );
        #$jsonObject = json_decode('{"AcceptStatus":"S","FlowNo":"129H1FV95N1C4872","InputCharset":"UTF-8","OriginalErrorMessage":"查询成功","OriginalRetCode":"000000","OutTradeNo":"c9278ec9aad3c9005a1d75e0eff9f8e5","PartnerId":"200001140111","PayBalance":"10000.00","PlatformErrorMessage":"交易受理成功","PlatformRetCode":"0000","RecBalance":"0.00","Sign":"dHh23hZXChYh+TFFNO5ph/mrWecKNuFdcm9kxA8/aakbL2+2hvVctZG1BKUzk3M7zO/w25RbwW1uJQVnmyxTa4YvWS+BcSG9Cvv2unPSDkrMKwaanwk/JFULhRKyuu+7FprmHL85cGHWQLVZ31OlEvWVOEF0CpDXTolJur5UnY0=","SignType":"RSA","TimeStamp":"20180917135828","TradeDate":"20180917","TradeTime":"135828","TransCode":"C00005"}');
        /*
        (
            [AcceptStatus] => S
            [FlowNo] => 129H17611AFB0935
            [InputCharset] => UTF-8
            [OriginalErrorMessage] => 查询成功
            [OriginalRetCode] => 000000
            [OutTradeNo] => 1537154720
            [PartnerId] => 200001160102
            [PayBalance] => 0.00
            [PlatformErrorMessage] => 交易受理成功
            [PlatformRetCode] => 0000
            [RecBalance] => 94.11
            [Sign] => vIJO09HD4yth5ipcYVT/DCH5FXgu+jXxr2me9qwfl4MZYYxs46WFo5c4y2PUPbNg/kIF3eglJP+YSObUCQl4wBkNeaToMXBSXmt9SGuf0sXZlnEEpFsN/Lhxsy3CYbZKDf4LY1krqCGF0tw7xC2phdq8yxRopLZc4SQiW4R37RQ=
            [SignType] => RSA
            [TimeStamp] => 20180917112520
            [TradeDate] => 20180917
            [TradeTime] => 112452
            [TransCode] => C00005
        )*/

        return $jsonObject;
    }

    /**
     * 功能：异步单笔代付T10100
     * @param $AcctNo
     * @param $AcctName
     * @param $TransAmt
     * @param $BankCommonName
     * @param $NotirfyUrl
     * @param string $BusinessType 业务类型 0私人 1公司 (默认私人,对公收税)
     * @return string
     */
    public function asynSinglePaying($AcctNo, $AcctName,  $TransAmt, $BankCommonName, $NotirfyUrl, $BusinessType = '0')
    {
        list($TradeDate, $TradeTime) = HuryUtils::trade_date_time();

        $postData = $needEncryptData = array();
        $needEncryptData['AcctNo'] = HuryUtils::rsaEncrypt($AcctNo, $this->getPublicKey()); //对手人账号
        $needEncryptData['AcctName'] = HuryUtils::rsaEncrypt($AcctName, $this->getPublicKey());
        #$needEncryptData =

        $postData['Service'] = 'cjt_dsf';
        $postData['Version'] = '1.0';
        $postData['CorpAcctNo'] = $this->getCorpAcctNo();//企业账号
        $postData['PartnerId'] = $this->getPartnerId();//商户号
        $postData['TradeDate'] = $TradeDate;
        $postData['TradeTime'] =  $TradeTime;
        $postData['InputCharset']= 'UTF-8';
        $postData['TransCode'] =  "T10100"; //功能码
        $postData['OutTradeNo'] = $this->createOrderNo(); //外部流水号
        $postData['BusinessType'] = $BusinessType;//业务类型 0私人 1公司
        $postData['BankCommonName'] = $BankCommonName;// 通用银行名称
        $postData['AccountType'] = '00';//账户类型 00借记卡 01贷记卡
        $postData['Currency'] = 'CNY';
        $postData['TransAmt'] = $TransAmt;//交易金额
        $postData['CorpPushUrl'] = $NotirfyUrl;//商户推送的URL地址
        $postData['PostScript'] = '代付';//用途

        $postData = array_merge($postData, $needEncryptData);
        $postData['Sign']= HuryUtils::rsaSign($postData, $this->getPrivateKey());
        $postData['SignType'] = $this->getSignType(); //签名类型

        $query = http_build_query($postData);

        $jsonObject = $this->requset( $query );
        #$jsonObject = json_decode('{"AcceptStatus":"S","FlowNo":"129H1GAJCA19560D","InputCharset":"UTF-8","OutTradeNo":"af909c77fc03881e241bd1f9b76b202a","PartnerId":"200001140111","PlatformErrorMessage":"交易受理成功","PlatformRetCode":"0000","Sign":"UHgAOHu1K7nVyRyJlFrAsFPLbIZ1aJgkaMPUJvLPK2pnpw1M8gjOcxrbtPCCqd+QFJ8xjXi2kTzQ75DXRbOca/A93YeFQMwUwAnwAdPaamwhvylz6w0CxOtBGyMntEHresi4OnUbeFOeufAM7zNaTfeZUrueqFFEmuMeTZV4CXY=","SignType":"RSA","TimeStamp":"20180917140439","TradeDate":"20180917","TradeTime":"140439","TransCode":"T10100"}');

        /*stdClass Object
        (
            [AcceptStatus] => S
            [FlowNo] => 129H148IV9196517
            [InputCharset] => UTF-8
            [OutTradeNo] => 41d7edfa5837c8328abea4ed5ee46262
            [PartnerId] => 200001140111
            [PlatformErrorMessage] => 交易受理成功
            [PlatformRetCode] => 0000
            [Sign] => pO9hQg0wMTVZhtHL4Z+PmUOEtP9EyoVRX9autahXuLzmDkjeNUxkucUOjB37U6Iy0f9kHQiJmpLk3t2VdsDFhWyRucuNYTZtvzhAGaKZtuEzWRy/Vx/uyS0sQyKTDIq1SjtO4fg4oqA7RK+K756RriRA2S/ppv4EWl5zWjg3MO0=
            [SignType] => RSA
            [TimeStamp] => 20180917103350
            [TradeDate] => 20180917
            [TradeTime] => 103350
            [TransCode] => T10100
        )
        */

        return $jsonObject;
    }

    /**
     * markdown-异步单笔回调 几种回调参数
     */
    static function NotifyAsynSinglePaying()
    {
        #@余额不足
        $data = [
            'uid' => '¬ify_time=20180917105533',
            'withdrawal_status' => 'WITHDRAWAL_SUCCESS',
                /*WITHDRAWAL_SUBMITTED  已提交银行
                WITHDRAWAL_SUCCESS  提现成功
                WITHDRAWAL_FAIL  提现失败
                RETURN_TICKET  提现退票*/
            'sign_type' => 'RSA¬ify_type=withdrawal_status_sync',
            'fail_reason' => '余额不足200100100220000114011100001',
            'version' => '1.0',
            'sign' => 'j5Hl1BdxRCGzw8r4bNoIDpc2pgZjQX0LHQPCH3ZV6gtMqe4Mssb1DVdhnFjexe0GR7IQ nXaGlaSsjU7nAZnTxwar/b56XsiFrn2cc /NI2yd8Q XE6NyPJ7CtsGkf5b29fgZWsFXRTrJ8wOgkxuoAS1T0Eg6yzTHNqR0/NUoeY=',
            '_input_charset' => 'UTF-8',
            'outer_trade_no' => 'af909c77fc03881e241bd1f9b76b202a',
            'withdrawal_amount' => '0.01¬ify_id=cd53cc1d9f7a4e6d829ceea306995813',
            'inner_trade_no' => '102153715291523554715',
            'gmt_withdrawal' => '20180917105515',
            'return_code' => 'S0001',
        ];
        return $data;
    }


    /**
     * @return string
     */
    public function getCorpAcctNo()
    {
        return $this->CorpAcctNo;
    }

    #记录回调的每一步
    public static function recordNotifyStep($stepName, $putContents, $flur = false)
    {
        HuryUtils::recordNotifyStep('Chanpay/paying_notify', $stepName, $putContents, $flur);
    }

}
