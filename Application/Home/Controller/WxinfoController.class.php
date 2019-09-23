<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作 者:    叶程鹏
 * 修改时间: 2018/05/19 15:50
 * 功能说明: 微信授权获取用户信息功能
 */
namespace Home\Controller;
use Think\Controller;
class WxinfoController extends Controller
{
	public function _initialize(){
		//-------配置
		$this->AppID = 'wx0b660201f26c5e6b';
	    $this->AppSecret='ee807f6c51dfe4b4c4c3f484a3bb3872';
	    $this->callback='http://bjcjct.cn/Wxinfo/backdeal'; //回调地址
	}
    //请求页面  用户同意授权，获取code
    public function index(){
        //-------生成唯一随机串防CSRF攻击
        session('getwxinfo',null);
		$state=md5(uniqid(rand(), TRUE));
		session('wx_state',$state); //存到SESSION
		$callback = urlencode($this->callback);
		$wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->AppID."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
		header("Location: $wxurl");
		//echo $wxurl;return false;
    }
    //回调页面
    public function backdeal(){
    	$code=$_GET['code'];
    	$access_token=$this->getAccessToken($code);//获取access_token
    	//获取会员信息
    	$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token['access_token'].'&openid='.$access_token['openid'].'&lang=zh_CN ';
	    $res = json_decode($this->https_request($url),true);
	    session('getwxinfo',$res);
        //return $res;
        redirect('/Login/index?wx=1');
        /*
			{    "openid":" OPENID",
			" nickname": NICKNAME,
			"sex":"1",
			"province":"PROVINCE"
			"city":"CITY",
			"country":"COUNTRY",
			"headimgurl":    "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
			"privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
			"unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
			}
        */
    }
    //获取access_token
    public function getAccessToken($code){
    	$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->AppID.'&secret='.$this->AppSecret.'&code='.$code.'&grant_type=authorization_code';

        $res = json_decode($this->https_request($url),true);
        return $res;
    }
    //
    //https请求
    public function https_request($url,$data=null){
    	$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	      curl_setopt($curl, CURLOPT_POST, 1);
	      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
    }

}