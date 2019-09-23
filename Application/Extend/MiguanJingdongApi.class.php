<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 京东万象蜜罐征信接口
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 胡文杰
// +----------------------------------------------------------------------
// | Date: 2018/04/23
// +----------------------------------------------------------------------

namespace Extend;
class MiguanJingdongApi{
    public $appkey;//京东万象蜜罐appkey
    public $name;//姓名
    public $id_card;//身份证号
    public $phone;//手机号

    //架构函数
    public function __construct( $name='',$id_card='', $phone='', $appkey='3045758f4a3be3fd9c033557f0c14fca') {
        $this->appkey=$appkey;
        $this->name=$name;
        $this->id_card=$id_card;
        $this->phone=$phone;
    }
    //查询蜜罐 征信报告
    public function searchs(){
        $url="https://way.jd.com/juxinli/henypot4JD?name=".$this->name."&idCard=".$this->id_card."&phone=".$this->phone."&appkey=".$this->appkey;
        $result = $this->httpGet($url);
        $res = json_decode($result);
        if($res->code == 10000){
            if ($res->result->data){
                //成功
                $data=array(
                    'result'=>'1',
                    'message'=>$res->result->data,
                );
            }else{
                //蜜罐失败
                $data=array(
                    'result'=>'2',
                    'message'=>$res->result->message,
                );
            }
        }else{
            $data=array(
              'result'=>'0',
              'message'=>$res->msg,
            );
        }
    	return $data;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}

