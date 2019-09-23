<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-08-08 10:51
 * @功能说明：  支付流程 接口
 */

namespace Api\Controller\Center;

use Api\Controller\Core\BaseController;
use Vendor\XBPayment\HuryUtils;
use Vendor\XBPayment\XBPayment;
use Vendor\XBPayment\XBPaymentFactory;


class PaymentController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * 银行卡信息提交
     * post
     *

     */
    /**
     * @功能说明: 发送支付短信
     * @传输方式: post
     * @提交网址: /Center/Payment/NowPay
     * @提交方式: {"token":"bce2675771dc92aa4d1818cf3c5e6c6fe7d9ca5e8b3d9044e1b1b57ddc11","client":"android","package":"android.ceshi","ver":"v1.1"}
     * @提交参数:
     *     OrderNo 订单号不能为空
     *     BankNo 卡号不能为空
     *     CertNo 证件号不能为空
     *     CertName 姓名不能为空
     *     CertPhone 手机号不能为空
     * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function NowPay()
    {
        parent::_initialize();

        $param = get_json_data();

        $XBPayment = new XBPayment(XBPayment::SCENE_API);

        $XBPayment->NowPay($param['dynamic']);
    }

    /**
     * @功能说明: 支付短信确认
     * @传输方式: post
     * @提交网址: /Center/Payment/NowPayConfirm
     * @提交方式: {"token":"bce2675771dc92aa4d1818cf3c5e6c6fe7d9ca5e8b3d9044e1b1b57ddc11","client":"android","package":"android.ceshi","ver":"v1.1"}
     * @提交参数:
     *     OrderNo 订单号不能为空
     *     Smscode 短信验证码
     * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function NowPayConfirm()
    {
        parent::_initialize();

        $param = get_json_data();
        $data = $param['dynamic'];

        $XBPayment = new XBPayment(XBPayment::SCENE_API);

        $UserID = get_login_info('ID');
        $OrderNo = $data['OrderNo'];
        $SmsCode = $data['Smscode'];

        $XBPayment->NowPayConfirm($UserID, $OrderNo, $SmsCode);
    }

    public function chanpayNotify()
    {
        $result = XBPaymentFactory::Chanpay()->payNotify();

        if ($result['PayStatus'] == 'S') {

            //根据订单号查询充值记录
            $OrderInfo = XBPaymentFactory::order()->findByTradeNo($result['TradeNo']);
            //该订单不存在
            if ($OrderInfo) {
                //根据订单号修改订单表数据
                if ($OrderInfo['Status'] == 2) {

                    die('success');

                } else {
                    $result = $this->change_dlorder_data($OrderInfo['OrderSn'], $result['TradeNo']);

                    if ($result == true) {
                        die('success');
                    }
                }
            }
        } elseif($result['PayStatus'] == 'F') {
            $sr = M('mem_buydaili')->where(array('OrderSn' => $result['OrderNo']))->save([
                'Status' => 3,
                'Batch' => $result['TradeNo'],
                'PayTime' => HuryUtils::getTimestamp(),
            ]);//修改订单状态

            die('success');
        }

        die("{'ret_code':'wtf','ret_msg':'传的啥啊'}");
    }



    /**
     * 模拟支付短信回调
     */
    public function DoMockNotify()
    {
        $payer = XBPaymentFactory::Chanpay();
        $TrxId = 'dcd0bbafcc8c572f42309ba266d664b8';
        #$mockData = 'notify_time=20180919122357&sign_type=RSA&notify_type=trade_status_sync&trade_status=TRADE_SUCCESS&gmt_payment=20180918102506&version=1.0&sign=AF2Ba7RpvWI36LsCLu9K6Ptv3n8w%2B9gdRy93C5ikNvis3zwuJ2fEZ%2FpYDtHLVZTROKQtU9uiqvJq1736p42YQeBZq7pQ8I7yikiuruDp9ch55DJOG3TjBRtkmJjxz9VXjjTWLNsnrL%2BnJVwoZPrxP%2FYruDm23Nm7BBq10Ue%2B4D0%3D&gmt_create=20180919122357&_input_charset=UTF-8&outer_trade_no='.$TrxId.'&trade_amount=0.01&notify_id=73761c6fa62a4e3a9ebd2519a16169df&inner_trade_no=101153723748535817706';
        #$mockData .= '&extension={"order_no":"1809217090423890","apiResultMsg":"交易成功","apiResultcode":"S","channelTransTime":"359829380117","instPayNo":"NI101364501091810002646","settlementId":"20180918221955PP58613607","unityResultMessage":"交易成功"}';
        $mockData = 'notify_time=20180921155555&sign_type=RSA&notify_type=trade_status_sync&trade_status=TRADE_SUCCESS&gmt_payment=20180921155555&version=1.0&sign=d3klL0jnz4Nk4RQA9Dv16%2FyWguxoMOElYuraU0%2FVXglBRwlzMsJBCTtr5Ls%2B%2FRwuuRxGCCJSZPqCmldSJvI5as30rfQf1nbbOUqKZU940cFv1I3cZ%2BSTtg20EPjebyVMmcdOzWeyg41nrWZhuoO5XSdyFGYii6%2FJZXzvf%2F7fWcs%3D&extension=%7B%22apiResultMsg%22%3A%22%E4%BA%A4%E6%98%93%E6%88%90%E5%8A%9F%22%2C%22apiResultcode%22%3A%22S%22%2C%22channelTransTime%22%3A%22389165410414%22%2C%22instPayNo%22%3A%22NI101364501092110264595%22%2C%22settlementId%22%3A%2220180921155528PP61191641%22%2C%22unityResultMessage%22%3A%22%E4%BA%A4%E6%98%93%E6%88%90%E5%8A%9F%22%7D&gmt_create=20180921155555&_input_charset=UTF-8&outer_trade_no=d3e439a6a904f9cf275afc17509c2899&trade_amount=0.01&notify_id=eb8992eeb4c848b19188b2d9bbabad2d&inner_trade_no=101153751652852729119';

        $res = $payer->mockNotify($mockData);
        var_dump($res);
    }

    //根据订单号修改购买代理记录信息
    public function change_dlorder_data($out_trade_no, $trade_no)
    {
        $dailinfos = M('mem_buydaili')->where(array('OrderSn' => $out_trade_no))->find();
        $mem_info = M('mem_info')->where(array('ID' => $dailinfos['UserID']))->find();

        $Trans = M();
        $Trans->startTrans();
        $dl_data['Status'] = '2';
        $dl_data['Batch'] = $trade_no;
        $dl_data['PayTime'] = date('Y-m-d H:i:s');
        $flag1 = $Trans->table('xb_mem_buydaili')->where(array('OrderSn' => $out_trade_no))->save($dl_data);//修改订单状态
        if ($flag1) {
            //修改会员表信息
            $mtype = '';
            if ($dailinfos['LevelID'] == '1') {
                $mtype = '2';
            } elseif ($dailinfos['LevelID'] == '2') {
                $mtype = '3';
            } elseif ($dailinfos['LevelID'] == '3') {
                $mtype = '4';
            }
            $Trans->table('xb_mem_info')->where(array('ID' => $dailinfos['UserID']))->save(array('Mtype' => $mtype));

            $smsdata = array(
                'Uid' => $dailinfos['UserID'],
                'Remarks' => '购买代理,订单号 ' . $out_trade_no . '，支付价格￥' . $dailinfos['OrderAmount'] . '元',
                'Addtime' => date('Y-m-d H:i:s'),
            );
            $Trans->table('xb_mem_sms')->add($smsdata);

            $data = array("Type" => '1', "Amount" => $dailinfos['OrderAmount'], "CurrentBalance" => $mem_info['Balance'], "Description" => '购买代理,订单号 ' . $out_trade_no . '，支付价格￥' . $dailinfos['OrderAmount'], "UserID" => $dailinfos['UserID'], "UpdateTime" => date('Y-m-d H:i:s'), "TradeCode" => date("YmdHis") . rand(10000, 99999), "Remarks" => '订单号' . $out_trade_no);
            //三级分销 分佣
            shareOrderMoneyRecord($dailinfos['OrderAmount'], $dailinfos['UserID'], '1', $mem_info['Mobile'], '', 0);
            $flag2 = $Trans->table('xb_mem_balances')->add($data);

            if ($flag2) {
                $Trans->commit();
                return true;
            } else {
                $Trans->rollback();
                return false;
            }

        } else {
            $Trans->rollback();
            return false;
        }
    }

}

