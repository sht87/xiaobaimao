<?php
namespace Home\Controller;
use Think\Controller;

class WachetController extends HomeController {
    //微信支付 购买代理
    public function index(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id','','trim');
        $oid = I('get.oid','','trim');

        $order = M('mem_buydaili')->where(array('ID'=>$id,'OrderSn'=>$oid))->find();
        if(!$order){
            header('Location: /Member/index');
        }

        $dta = array(
            'total_amount' => 1,   //标价金额  $order['OrderAmount']*100
            'out_trade_no' => $order['OrderSn'],    //商户订单号
            'attach'        => $order['OrderSn'],  //附加数据
            'body'          => "订单号:".$order['OrderSn'],    //商品描述
            'notify_url'   => "http://".$_SERVER['HTTP_HOST']."/index.php/wachet/query",    //回调地址
            'product_id'   => $order['ID'],    //商品ID
            'Trade_type'   => "JSAPI",    //交易类型   JSAPI，NATIVE，APP
        );
        vendor('WxpayAPI.Paynative');

        $WxpayAPI = new \wxpay();
        $jsApiParameters = $WxpayAPI->jsapi($dta);

        $this->assign(array(
            'jsApiParameters'=>$jsApiParameters,
            'order'=>$order,
            ));
        $this->display();
    }
    //微信支付 购买代理 h5支付
    public function index2(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id','','trim');
        $oid = I('get.oid','','trim');
        $client = I('get.client','','trim');

        $order = M('mem_buydaili')->where(array('ID'=>$id,'OrderSn'=>$oid))->find();
        if(!$order){
            header('Location: /Member/index');
        }

        $dta = array(
            'total_amount' => 1,   //标价金额  $order['OrderAmount']*100
            'out_trade_no' => $order['OrderSn'],    //商户订单号
            'attach'        => $order['OrderSn'],  //附加数据
            'body'          => "订单号:".$order['OrderSn'],    //商品描述
            'notify_url'   => "http://".$_SERVER['HTTP_HOST']."/index.php/wachet/query",    //回调地址
            'product_id'   => $order['ID'],    //商品ID
            'Trade_type'   => "MWEB",    //交易类型   JSAPI，NATIVE，APP
        );
        vendor('WxpayAPI.Paynative');

        $WxpayAPI = new \wxpay();
        $url = $WxpayAPI->Nativeh5($dta);
        $redirect="http://".$_SERVER['HTTP_HOST']."/index.php/Member/index";
        if($client!='ios' || $client!='android'){
            $url.='&redirect_url='.urlencode($redirect);
        }
        header("Location:$url");
    }
    //微信,购买代理支付回调地址
    public function query(){
        $xml = file_get_contents('php://input');
        $array = setContent($xml);
        if(empty($array)){
            $msg = 'fail';
        }else{
            if($array['result_code']=="SUCCESS" && $array['return_code'] == "SUCCESS"){
                $order = M('mem_buydaili')->where(array('OrderSn'=>$array['out_trade_no']))->find();
                if(!$order){
                    $msg = 'fail2';
                }
                if($order['Status']==1){
                    $result = change_dlorder_data($array['out_trade_no'],$array['transaction_id']);
                    if($result){
                        $msg = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                    }else{
                        $msg = 'fail11';
                    }
                }else{
                    $msg = 'success';
                }
            }else{
                $msg = 'fail1';
            }
        }
        return $msg;
    }

    //微信支付 征信查询支付
    public function payzenxin(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id','','trim');
        $oid = I('get.oid','','trim');

        $order = M('zenxin_list')->where(array('ID'=>$id,'OrderSn'=>$oid))->find();
        if(!$order){
            if($order['Type']==1){
                header('Location: /index.php/Daizenxin/zdetail?id='.$id);
            }else{
                header('Location: /Member/index');
            }
        }

        $dta = array(
            'total_amount' => 1,   //标价金额  $order['OrderAmount']*100
            'out_trade_no' => $order['OrderSn'],    //商户订单号
            'attach'        => $order['OrderSn'],  //附加数据
            'body'          => "订单号:".$order['OrderSn'],    //商品描述
            'notify_url'   => "http://".$_SERVER['HTTP_HOST']."/index.php/wachet/queryzx",    //回调地址
            'product_id'   => $order['ID'],    //商品ID
            'Trade_type'   => "JSAPI",    //交易类型   JSAPI，NATIVE，APP
        );
        vendor('WxpayAPI.Paynative');

        $WxpayAPI = new \wxpay();
        $jsApiParameters = $WxpayAPI->jsapi($dta);

        $this->assign(array(
            'jsApiParameters'=>$jsApiParameters,
            'order'=>$order,
            ));
        $this->display();
    }
    //微信支付 征信查询支付  h5支付
    public function payzenxin2(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id','','trim');
        $oid = I('get.oid','','trim');
        $client = I('get.client','0','trim');

        $order = M('zenxin_list')->where(array('ID'=>$id,'OrderSn'=>$oid))->find();
        if(!$order){
            if($order['Type']==1){
                header('Location: /index.php/Daizenxin/zdetail?id='.$id);
            }else{
                header('Location: /Member/index');
            }
        }

        $dta = array(
            'total_amount' => 1,   //标价金额  $order['OrderAmount']*100
            'out_trade_no' => $order['OrderSn'],    //商户订单号
            'attach'        => $order['OrderSn'],  //附加数据
            'body'          => "订单号:".$order['OrderSn'],    //商品描述
            'notify_url'   => "http://".$_SERVER['HTTP_HOST']."/index.php/wachet/queryzx",    //回调地址
            'product_id'   => $order['ID'],    //商品ID
            'Trade_type'   => "MWEB",    //交易类型   JSAPI，NATIVE，APP
        );
        vendor('WxpayAPI.Paynative');

        $WxpayAPI = new \wxpay();
        $url = $WxpayAPI->Nativeh5($dta);
        if($order['Type']==1){
            $redirect="http://".$_SERVER['HTTP_HOST']."/index.php/Daizenxin/zdetail?id=".$id."&uid=".$order['UserID'];
            if($client=='0'){
                $url.='&redirect_url='.urlencode($redirect);
            }
        }else{
            $redirect="http://".$_SERVER['HTTP_HOST']."/index.php/Zenxin/zxlist";
            if($client=='0'){
                $url.='&redirect_url='.urlencode($redirect);
            }
        }
        header("Location:$url");
    }
    //微信,征信查询支付回调地址
    public function queryzx(){
        $xml = file_get_contents('php://input');
        $array = setContent($xml);
        if(empty($array)){
            $msg = 'fail';
        }else{
            if($array['result_code']=="SUCCESS" && $array['return_code'] == "SUCCESS"){
                $order = M('zenxin_list')->where(array('OrderSn'=>$array['out_trade_no']))->find();
                if(!$order){
                    $msg = 'fail2';
                }
                if($order['Status']==1){
                    $result = change_zxorder_data($array['out_trade_no'],$array['transaction_id']);
                    $this->lookzenxin($array['out_trade_no']);
                    if($result){
                        $msg = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                    }else{
                        $msg = 'fail11';
                    }
                }else{
                    $msg = 'success';
                }
            }else{
                $msg = 'fail1';
            }
        }
        return $msg;
    }
    //查征信
    public function lookzenxin($OrderSn){
        $orderinfo=M('zenxin_list')->where(array('OrderSn'=>$OrderSn,'IsDel'=>'0'))->find();
        if(!$orderinfo){
            return false;
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
                M('zenxin_list')->where(array('OrderSn'=>$OrderSn))->save($updata);
                return false;
            }elseif($mrest['result']=='1'){
                //查询成功
                $searchinfo=serialize($mrest['message']);
                $updata=array(
                    'LoanInfos'=>$searchinfo,
                    'Checktime'=>date('Y-m-d H:i:s'),
                    'Status'=>'4',
                    );
                M('zenxin_list')->where(array('OrderSn'=>$OrderSn))->save($updata);
                return true;
            }
        }
    }

}