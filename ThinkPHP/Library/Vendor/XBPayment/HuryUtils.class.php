<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-09-11 10:22
 * @功能说明：  常用函数包
 */
namespace Vendor\XBPayment;

class HuryUtils
{

    /**
     * 格式yyyyMMdd 20140724
     * 格式HH:mm:ss 125900
     * @return array|list(date, time)
     */
    static function trade_date_time()
    {
        $time = time();

        $date = date('Ymd-His', $time);

        return explode('-', $date);

    }

    /**
     * 生成唯一订单号
     */
    static function create_uuid($prefix = "")
    {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8);
        $uuid .= substr($str, 8, 4);
        $uuid .= substr($str, 12, 4);
        $uuid .= substr($str, 16, 4);
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    /**
     * 返回毫秒级 时间戳
     * @return string
     */
    static function getMicroTimestamp()
    {
        return time() . substr(microtime(), 2, 3);
    }

    static function getTimestamp()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     *@功能说明:判断是否是手机
     * @param string $val 判断的数据
     * @return bool
     */
    static function is_mobile($val)
    {
        return preg_match("/^1[3|4|5|6|7|8|9][0-9]\d{8}$/",$val);
    }

    /**
     * RSA 加密
     * @param $content
     * @param $pubKey 一般为公钥
     * @return string
     */
    static function rsaEncrypt($content, $pubKey)
    {
        $res = openssl_get_publickey($pubKey);
        //把需要加密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_public_encrypt ($data, $encrypt, $res);
            $result .= $encrypt;
        }
        $result = base64_encode($result);
        openssl_free_key($res);
        return $result;
    }

    /**
     * 生成签名
     * @param $params
     * @param $priKey 一般为私钥
     * @return string
     */
    static function rsaSign($args, $priKey)
    {
        #$args = array_filter($args);//过滤掉空值
        ksort($args);
        $query = '';
        foreach ($args as $k => $v) {

            if(static::checkEmpty($v)) continue;

            if($k=='SignType'){
                continue;
            }
            if($query){
                $query  .=  '&'.$k.'='.$v;
            }else{
                $query  =  $k.'='.$v;
            }
        }

        $pkeyid = openssl_get_privatekey($priKey);
        openssl_sign($query, $sign, $pkeyid);
        openssl_free_key($pkeyid);
        $sign = base64_encode($sign);
        return $sign;
    }


    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    static function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * @param $order_url
     * @return mixed 请求结果
     */
    static function  httpGet($order_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $order_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        return $json;
    }

    #记录回调的每一步
    static function recordNotifyStep($filename, $stepName, $putContents, $flur = false)
    {
        static $logStack=[];

        $putContents = is_array($putContents) || is_object($putContents) ? json_encode($putContents, \JSON_UNESCAPED_UNICODE) : $putContents;

        $curYmd = date('Ymd');
        $curDate = date('Y-m-d H:i:s');

        $logStack[] = [$stepName, $putContents];

        $txt = '';

        if($flur) {
            foreach ($logStack as $index => $item) {
                list($stepName, $putContents) = $item;
                $txt .= '     '.$stepName.PHP_EOL.'            '.$putContents.PHP_EOL;

            }
            return file_put_contents(
                $filename.'_'.$curYmd.'.txt',
                '['.$curDate.']'.PHP_EOL.'>>>>>>>>>'.PHP_EOL.$txt.PHP_EOL.'>>>>>>>>>'.PHP_EOL,
                \FILE_APPEND
            );
        }
    }


    /**
     * [CURLSend 使用curl向服务器传输数据]
     * @param $url  [请求的地址]
     * @param string $method [请求方式GET,POST]
     * @param array $data [数据]
     * @param bool $doJson [json格式]
     * @return mixed
     */
    static function httpRequest($url, $method='get', $data=array(), $doJson = false, $head = [])
    {


        $ch = curl_init();//初始化

        if($doJson) {

            $headers = array('Accept-Charset: utf-8', 'Content-Type: application/json');
            $head && $headers = $head;
            $data = json_encode($data);
        } else {
            $data = is_array($data) ? http_build_query($data) : $data;
            $headers = array('Accept-Charset: utf-8');
            $head && $headers = $head;
        }

        //设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);//指定请求的URL
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));//提交方式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//不验证SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//不验证SSL
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置HTTP头字段的数组

        // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible;MSIE 5.01;Windows NT 5.0)');//头的字符串

        #curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies

        #curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);//自动设置header中的Referer:信息
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//提交数值
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//是否输出到屏幕上,true不直接输出
        $temp = curl_exec($ch);//执行并获取结果
        curl_close($ch);
        return $temp;//return 返回值
    }

}
