<?php
namespace Home\Controller;
use Think\Controller;

class AlipayController extends HomeController {
    //购买代理支付
    public function index(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id','','trim');
        $oid = I('get.oid','','trim');

        $order = M('mem_buydaili')->where(array('ID'=>$id,'OrderSn'=>$oid))->find();
        if(!$order){
            header('Location: /Member/index');
        }

        $OrderAmount = $order['OrderAmount'];
        $OrderID = $order['OrderSn'];
        $subject = $order['OrderSn'];
        $body = $order['OrderSn'];

        vendor('Alipay.AlipayWap.AlipayWap');
        $Alipay = new \Alipay();
        $alipay = $Alipay::go_pay($OrderAmount,$OrderID,$subject,$body);

        echo json_encode($alipay);exit;
    }
    //该方法为接受支付宝回传参数以及修改对应订单状态的方法
    //购买代理支付回调地址
    public function query(){
        $data = $_POST;//支付宝回传的数据为json格式，再获取其中的数据的时候，无需解析该json数据然后获取，可直接通过$_POST['参数名']获取到。
        $total_amount = I('post.total_amount'); //金额
        $trade_status = I('post.trade_status'); //状态
        $trade_no = I('post.trade_no'); //交易号
        $out_trade_no = I('post.out_trade_no'); //订单号
        $subject = I('post.subject'); //商品描述

        //此处和第44行一样
        vendor('Alipay.AlipayWap.AlipayWap');

        $Alipay = new \Alipay();

        //该方法为我进行的二次封装，实际还是调用支付宝官方demo的验签功能
        $result = $Alipay::check_sign($data);
        //校验成功
        if($result){
            //根据订单号查询充值记录
            $order_info = M('mem_buydaili')->where(array('OrderSn'=>$out_trade_no))->find();
            //该订单不存在
            if(!$order_info){
                $msg = "fail";
                //回传的金额不存在或者和查询到的订单的金额不一致
            }else if(!$total_amount){
                $msg = "fail2";
            //回传状态不正确                
            }else if($trade_status != "TRADE_SUCCESS"){
                $msg = "fail3";
            }else{
                //根据订单号修改订单表数据
                if($order_info['Status']==1){
                    $result = change_dlorder_data($out_trade_no,$trade_no);
                    $t = 1;
                    if($result == true){
                        $msg = "success";
                    }else{
                        $msg = "fail4";
                    }

                }else{
                    $msg = "fail6";
                }
            }
        }else {
            //验证失败
            $msg = "fail5";
        }
    }
    //征信查询支付
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

        $OrderAmount = $order['OrderAmount'];
        $OrderID = $order['OrderSn'];
        $subject = $order['OrderSn'];
        $body = $order['OrderSn'];

        vendor('Alipay.AlipayWap.AlipayWap');
        $Alipay = new \Alipay();
        if($order['Type']==1){
            $alipay = $Alipay::pay_sharezenxin($OrderAmount,$OrderID,$subject,$body,$id,$order['UserID']);
        }else{
            $alipay = $Alipay::pay_zenxin($OrderAmount,$OrderID,$subject,$body);
        }

        echo json_encode($alipay);exit;
    }
    //征信支付回调地址
    public function zenxinquery(){
        $data = $_POST;//支付宝回传的数据为json格式，再获取其中的数据的时候，无需解析该json数据然后获取，可直接通过$_POST['参数名']获取到。
        $total_amount = I('post.total_amount'); //金额
        $trade_status = I('post.trade_status'); //状态
        $trade_no = I('post.trade_no'); //交易号
        $out_trade_no = I('post.out_trade_no'); //订单号
        $subject = I('post.subject'); //商品描述

        //此处和第44行一样
        vendor('Alipay.AlipayWap.AlipayWap');

        $Alipay = new \Alipay();

        //该方法为我进行的二次封装，实际还是调用支付宝官方demo的验签功能
        $result = $Alipay::check_sign($data);
        //校验成功
        if($result){
            //根据订单号查询充值记录
            $order_info = M('zenxin_list')->where(array('OrderSn'=>$out_trade_no))->find();
            //该订单不存在
            if(!$order_info){
                $msg = "fail";
                //回传的金额不存在或者和查询到的订单的金额不一致
            }else if(!$total_amount){
                $msg = "fail2";
            //回传状态不正确                
            }else if($trade_status != "TRADE_SUCCESS"){
                $msg = "fail3";
            }else{
                //根据订单号修改订单表数据
                if($order_info['Status']==1){
                    $result = change_zxorder_data($out_trade_no,$trade_no,2);
                    $this->lookzenxin($out_trade_no);

                    $t = 1;
                    if($result == true){
                        $msg = "success";
                    }else{
                        $msg = "fail4";
                    }

                }else{
                    $msg = "fail6";
                }
            }
        }else {
            //验证失败
            $msg = "fail5";
        }
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