<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 蜜罐征信接口
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 叶程鹏
// +----------------------------------------------------------------------
// | Date: 2018/04/23
// +----------------------------------------------------------------------

namespace Extend;
class MiguanApi{
    public $account;//机构账号
    public $client_secret;//机构标识码
    public $name;//姓名
    public $id_card;//身份证号
    public $phone;//手机号

    //架构函数
    public function __construct( $account='', $client_secret ='',$name='',$id_card='',$phone='') {
        $this->account=$account;
        $this->client_secret=$client_secret;
        $this->name=$name;
        $this->id_card=$id_card;
        $this->phone=$phone;
    }
    //查询蜜罐 征信报告
    public function searchs(){
    	$data=array();
    	$token=$this->getAccessToken();
    	if($token){
    		//$url="https://mi.juxinli.com/api/search?client_secret=".$this->client_secret."&access_token=".$token."&name=".$this->name."&id_card=".$this->id_card."&phone=".$this->phone."&version=v3";//蜜罐最新版本
            $url="https://mi.juxinli.com/api/search?client_secret=".$this->client_secret."&access_token=".$token."&name=".$this->name."&id_card=".$this->id_card."&phone=".$this->phone;//蜜罐v2.0.1版本
    		$res = json_decode($this->httpGet($url));
    		if($res->data){
    			//成功
    			$data=array(
				  'result'=>'1',
				  'message'=>$res->data,
				);
    		}else{
    			$data=array(
				  'result'=>'0',
				  'message'=>$res->message,
				);
    		}
    	}else{
    		$data=array(
			  'result'=>'0',
			  'message'=>'访问令牌获取失败',
			);
    	}
    	return $data;
    }
    //获取 蜜罐访问令牌
    public function getAccessToken() {
        $data = json_decode(file_get_contents("./data/mgtoken.json"));
        if ($data->expire_time < time()) {
            // access_token
            $url = "https://mi.juxinli.com/api/access_token?client_secret=".$this->client_secret."&account=".$this->account;
            $res = json_decode($this->httpGet($url));

            $access_token = $res->data->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("./data/mgtoken.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
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

