<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 吴
// +----------------------------------------------------------------------
// | Date: 2017/10/10
// +----------------------------------------------------------------------

namespace Api\Controller\Home;
use Think\Controller;

class TController extends Controller
{

    public function t(){
        $t = common_token();
        echo $t;
    }

    //  58776b3fd446fa2fd98080a51038266ed8cf9a1d1f8cdd765672fedac7e0
    public function g()
    {
        header('Content-Type:application/json; charset=utf-8');

        $orderBody = "test商品";
        $tade_no = "abc_" . time();
        $total_fee = 1;

        $WxPayHelper = new \Extend\Wachet();
        $response = $WxPayHelper->getPrePayOrder($orderBody, $tade_no, $total_fee);

        if($response['return_code']){
            print_r($response['return_msg']);
        }

        print_r($response);

        $x = $WxPayHelper->getOrder($response['prepay_id']);
        print_r($x);

    }

    public function d(){
            $d='lD+1m0TLoHyWJHxAe0N2JTKiMgZLjpCysyz0KDvHdndt9xakxusP579frINsyHGN3qMqW3y9q0Op7AR9mo4\/B2JnlhrkbxhlHBmp55WDKHEFVX58a6Z80NqD8So8E+vmn9jZmknpbKEzqo2kZHZ32\/ksiSd9b6yOUis9qjVu8HfT5MxeUFW\/6oiZDLUtZTsLXh+CMQuEA5Ga4EQEhZ4NBw==';
            $k='XB7bd99853e95cf400b4c1f5fcda364d';
            $i='XB952c2bbdbba3cb';
            $data=decrypt_pkcs7($d,$k,$i);
            print_r($data);
        }


    // 图形验证码 //
    public function getImgCode(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $Verify =     new \Think\ImgCode($config);
        $Verify->codeSet = '0123456789';
        ob_end_clean();
        $Verify->entry();
    }

}