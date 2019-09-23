<?php

class wxpay{

	//模式二
	/**
	 * 流程：
	 * 1、调用统一下单，取得code_url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、支付完成之后，微信服务器会通知支付成功
	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
	 */
	public function Native($data){

        require_once dirname(__FILE__)."/lib/WxPay.Api.php";
        require_once dirname(__FILE__)."/WxPay.NativePay.php";

        $data['total_amount'] = trim($data['total_amount']);//trim($data['total_amount']); 1

		//$data = array(
		//		'total_amount'=>991,   //标价金额
		//		'out_trade_no'=> "170821718121311",    //商户订单号
		//		'attach'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",  //附加数据
		//		'body'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",    //商品描述
		//      'notify_url'=>"http://ldb.ahceshi.com/WxpayAPI_php_v3/example/notify.php",    //回调地址
		//      'product_id'=>"1111",    //商品ID
		//      'Trade_type'=>"NATIVE",    //交易类型   JSAPI，NATIVE，APP
		//);
		$notify = new \NativePay();

		$input = new \WxPayUnifiedOrder();

		$input->SetBody($data['body']);
		$input->SetAttach($data['attach']);
		$input->SetOut_trade_no($data['out_trade_no']);
		$input->SetTotal_fee($data['total_amount']);

		//$input->SetTime_start(date("YmdHis"));
		//$input->SetTime_expire(date("YmdHis", time() + 600));

		$input->SetNotify_url($data['notify_url']);
		$input->SetTrade_type($data['Trade_type']);
		$input->SetProduct_id($data['product_id']);
		$result = $notify->GetPayUrl($input);
		return $result["code_url"];
	}

    //模式二
    /**
     * 流程：
     * 1、调用统一下单，取得code_url，生成二维码
     * 2、用户扫描二维码，进行支付
     * 3、支付完成之后，微信服务器会通知支付成功
     * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
     */
    public function Nativeh5($data){

        require_once dirname(__FILE__)."/lib/WxPay.Api.php";
        require_once dirname(__FILE__)."/WxPay.NativePay.php";

        $data['total_amount'] = trim($data['total_amount']);;//trim($data['total_amount']); 1

        //$data = array(
        //      'total_amount'=>991,   //标价金额
        //      'out_trade_no'=> "170821718121311",    //商户订单号
        //      'attach'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",  //附加数据
        //      'body'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",    //商品描述
        //      'notify_url'=>"http://ldb.ahceshi.com/WxpayAPI_php_v3/example/notify.php",    //回调地址
        //      'product_id'=>"1111",    //商品ID
        //      'Trade_type'=>"NATIVE",    //交易类型   JSAPI，NATIVE，APP
        //);
        $notify = new \NativePay();

        $input = new \WxPayUnifiedOrder();

        $input->SetBody($data['body']);
        $input->SetAttach($data['attach']);
        $input->SetOut_trade_no($data['out_trade_no']);
        $input->SetTotal_fee($data['total_amount']);

        //$input->SetTime_start(date("YmdHis"));
        //$input->SetTime_expire(date("YmdHis", time() + 600));

        $input->SetNotify_url($data['notify_url']);
        $input->SetTrade_type($data['Trade_type']);
        $input->SetProduct_id($data['product_id']);
        $result = $notify->GetPayUrl2($input);
        return $result["mweb_url"];
    }

    public function jsapi($data){

        //$data = array(
        //		'total_amount'=>991,   //标价金额
        //		'out_trade_no'=> "170821718121311",    //商户订单号
        //		'attach'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",  //附加数据
        //		'body'=>"新鲜雪梨皇冠梨砀山水晶玉露香梨 100KG",    //商品描述
        //      'notify_url'=>"http://ldb.ahceshi.com/WxpayAPI_php_v3/example/notify.php",    //回调地址
        //      'product_id'=>"111",    //商品ID
        //      'Trade_type'=>"NATIVE",    //交易类型   JSAPI，NATIVE，APP
        //);

        require_once dirname(__FILE__)."/lib/WxPay.Api.php";
        require_once dirname(__FILE__)."/WxPay.JsApiPay.php";

        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();

        $data['total_amount'] = trim($data['total_amount']);;//trim($data['total_amount']); 1

       // if(!file_exists("Openid.txt")){ $fp = fopen("Openid.txt","wb"); fclose($fp);  }
       // $str = file_get_contents('Openid.txt');
       // $str .= " - trade_no:".$openId." -  - ".date("Y-m-d H:i:s")."\r\n";
       // $fp = fopen("Openid.txt","wb");
       // fwrite($fp,$str);
       // fclose($fp);

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($data['body']);
        $input->SetAttach($data['attach']);
        $input->SetOut_trade_no($data['out_trade_no']);
        $input->SetTotal_fee($data['total_amount']);

        $input->SetNotify_url($data['notify_url']);
        $input->SetTrade_type($data['Trade_type']);
        $input->SetProduct_id($data['product_id']);
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);

//        if(!file_exists("Opid.txt")){ $fp = fopen("Opid.txt","wb"); fclose($fp);  }
//        $str = file_get_contents('Opid.txt');
//        $str .= " - trade_no:".serialize($order)." -  - ".date("Y-m-d H:i:s")."\r\n";
//        $fp = fopen("Opid.txt","wb");
//        fwrite($fp,$str);
//        fclose($fp);

        $jsApiParameters = $tools->GetJsApiParameters($order);




        return $jsApiParameters;
    }

}
?>