<?php
/* *
 * 功能：支付宝电脑支付调试入口页面
 * 修改日期：2017-08-22
 * 说明：
 */

class Alipay{
    public function go_pay($total_fee,$out_trade_no,$subject,$body){

        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/service/AlipayTradeService.php';
        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';


        //付款金额，必填
        $total_amount = trim($total_fee);;//trim($total_fee); 0.01
        //超时时间
        $timeout_express="1m";

        //构造参数
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        return $response;exit;
    }


    public function pay_zenxin($total_fee,$out_trade_no,$subject,$body){

        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/service/AlipayTradeService.php';
        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';


        //付款金额，必填
        $total_amount = trim($total_fee);;//trim($total_fee); 0.01
        //超时时间
        $timeout_express="1m";

        //构造参数
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->wapPay($payRequestBuilder,$config['zenxin_return_url'],$config['zenxin_notify_url']);

        //输出表单
        return $response;exit;
    }

    public function pay_sharezenxin($total_fee,$out_trade_no,$subject,$body,$id,$uid){

        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/service/AlipayTradeService.php';
        require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';


        //付款金额，必填
        $total_amount = trim($total_fee);;//trim($total_fee); 0.01
        //超时时间
        $timeout_express="1m";

        //构造参数
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->wapPay($payRequestBuilder,$config['zenxin_return_url']."?id=".$id."&uid=".$uid,$config['zenxin_notify_url']);

        //输出表单
        return $response;exit;
    }

    //对回调的数据进行校验
    public function check_sign($data){
        require_once dirname(__FILE__).'/config.php';
        require_once dirname(__FILE__).'/wappay/service/AlipayTradeService.php';


        $aop = new \AlipayTradeService($config);
        $result = $aop->check($data);

        return $result;
    }
}

