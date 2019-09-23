<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 联系方式：18363857597
 * 作    者：李志修
 * 修改时间: 2017-06-08 14:30
 * 功能说明:公共函数库
 */

/**
 * 自定义函数库
 */

/**
 * ############################# 短信发送函数 ################################
 */

/**
 * @功能说明:发送短信或自定消息
 * @param $name 手机号 或邮箱地址
 * @param $type 发送类型 0 短信 1 邮件 默认短信
 * @param $content 自定义消息  短信中 模板编号和自定义消息仅用一个不可同时使用  邮件内需要配合 title 邮件标题
 * @param $title 邮件标题
 * @return array|string  $message 错误信息 $res 接口返回结果
 */
 
require_once(dirname(dirname(__FILE__)) . '/../Extend/ChuanglanSmsApi.php');
function sendmessage($name,$content,$type)
{
    $message='';
    //判断是短信还是邮件
    if((int)$type==1) {
        if (empty($name)) {
            $message .= '邮件地址不能为空,';
        }
        if (!empty($title) && !empty($content)) {
            $mes = new XBCommon\XBMessage($name, $content,$title, $type);
            $res = $mes->send_message();
        } else {
            $message .= '邮件内容和邮件标题，不能为空';
        }
    }else{
        if (empty($name)) {
            $message .= '手机号不能为空,';
        }
        if (!empty($content)) {
            $clapi  = new ChuanglanSmsApi();
			$res = $clapi->sendSMS($name,$content,'');
        } else {
            $message .= '短信内容，不能为空';
        }
    }
    if(!empty($message)){
        return $message;
    }
    return  $res;
}


/**
 * ############################# 常用验证函数 ################################
 */

/**
 * @功能说明: 判断是否是数字
 * @param  int  $val  判断的数据
 * @return bool
 */
function is_num($val=0){
    return  is_numeric($val);
}

/**
 *@功能说明:判断是否是手机
 * @param string $val 判断的数据
 * @return bool
 */
function is_mobile($val){
    return preg_match("/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/",$val);
}

/**
 * @功能说明:判断是否是固话
 * @param  string  $val 判断的数据
 * @return bool
 */
function is_tel($val){
    return preg_match("/^0\d{2,3}-?\d{7,8}$/",$val);
}

/**
 * @功能说明:判断是否是电话号码 手机/固话
 * @param  string  $val 判断的数据
 * @return bool
 */
function is_phone($val){
    return preg_match("/(^0\d{2,3}-?\d{7,8})|(^0?1[3|4|5|7|8][0-9]\d{8})$/",$val);
}

/**
 * @功能说明:检测变量是否是邮件地址
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_email($val) {
    return preg_match('/^[\w-]+(\.[\w-]+)*\@[A-Za-z0-9]+((\.|-|_)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/', $val);
}

/**
 * @功能说明:检测变量是否是qq
 * @param   string $val 判断的数据
 * @return  bool
*/
function is_qq($val) {
    return preg_match('/^[1-9][0-9]{4,10}$/', $val);
}

/**
 * @功能说明:检测变量是否是邮编号码
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_zip($val) {
    return preg_match('/^[1-9]\d{5}$/', $val);
}

/**
 * @功能说明:检测变量是否符合用户名的规则 英文开头，允许数字下划线组合
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_username($val) {
    return preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $val);
}

/** 身份证验证
 * @param $idcard
 * @return bool
 */
function checkIdCard($idcard)
{
    if (empty($idcard)) {
        return false;
    }
    $City = array(11 => "北京", 12 => "天津", 13 => "河北", 14 => "山西", 15 => "内蒙古", 21 => "辽宁", 22 => "吉林", 23 => "黑龙江", 31 => "上海", 32 => "江苏", 33 => "浙江", 34 => "安徽", 35 => "福建", 36 => "江西", 37 => "山东", 41 => "河南", 42 => "湖北", 43 => "湖南", 44 => "广东", 45 => "广西", 46 => "海南", 50 => "重庆", 51 => "四川", 52 => "贵州", 53 => "云南", 54 => "西藏", 61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏", 65 => "新疆", 71 => "台湾", 81 => "香港", 82 => "澳门", 91 => "国外");
    $iSum = 0;
    $idCardLength = strlen($idcard);
    //长度验证
    if (!preg_match('/^\d{17}(\d|x)$/i', $idcard) and !preg_match('/^\d{15}$/i', $idcard)) {
        return false;
    }
    //地区验证
    if (!array_key_exists(intval(substr($idcard, 0, 2)), $City)) {
        return false;
    }
    // 15位身份证验证生日，转换为18位
    if ($idCardLength == 15) {
        $sBirthday = '19' . substr($idcard, 6, 2) . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
        echo $sBirthday;
        die;
        $d = new \DateTime($sBirthday);
        $dd = $d->format('Y-m-d');
        if ($sBirthday != $dd) {
            return false;
        }
        $idcard = substr($idcard, 0, 6) . "19" . substr($idcard, 6, 9);//15to18
        $Bit18 = getVerifyBit($idcard);//算出第18位校验码
        $idcard = $idcard . $Bit18;
    }
    // 判断是否大于2078年，小于1900年
    $year = substr($idcard, 6, 4);
    if ($year < 1900 || $year > 2078) {
        return false;
    }

    //18位身份证处理
    $sBirthday = substr($idcard, 6, 4) . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
    $d = new \DateTime($sBirthday);
    $dd = $d->format('Y-m-d');
    if ($sBirthday != $dd) {
        return false;
    }
    //身份证编码规范验证
    $idcard_base = substr($idcard, 0, 17);
    if (strtoupper(substr($idcard, 17, 1)) != getVerifyBit($idcard_base)) {
        return false;
    }
    return true;
}



// 计算身份证校验码，根据国家标准GB 11643-1999
function getVerifyBit($idcard_base){
    if(strlen($idcard_base) != 17)
    {
        return false;
    }
    //加权因子
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码对应值
    $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
    $checksum = 0;
    for ($i = 0; $i < strlen($idcard_base); $i++)
    {
        $checksum += substr($idcard_base, $i, 1) * $factor[$i];
    }
    $mod = $checksum % 11;
    $verify_number = $verify_number_list[$mod];
    return $verify_number;
}

/**
 * @功能说明:检测变量是否符密码的规则 英文+数字组合 6-20位
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_password($val){
    return preg_match('/^[a-zA-Z]\w{5,15}$/i',$val);
}

/**
 * @功能说明:检测变量是否全是英文
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_english($val){
    return preg_match('/^[A-Za-z]+$/',$val);
}

/**
 * @功能说明:检测变量是否为汉字
 * @param   string $val 判断的数据
 * @return  bool
 */
function is_chs($val) {
    return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $val);
}

/**
 * @功能说明:判断是否为网址,以http://开头
 * @param string $str 判断的数据
 * @return bool
 */
function is_url($str){
    return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $str);
}

 /**
  * @功能说明:检测变量长度区间
  * @param   string  $val  判断的数据
  * @param   int $min 最小长度
  * @param   int $max 最大长度
  * @return  bool
  */
function strlenth($val,$min,$max) {
    if(strlen($val) >= $min && strlen($val)<= $max) {
            return true;
    }else{
        return false;
    }
}


/**
 * ############################# 检测状态函数 ################################
 */


/**
 * @功能说明：判断数据库链接,5秒钟未正常连接则返回false
 * @return bool
 */
function check_mysql(){
    $server=C('DB_HOST');
    $username=C('DB_USER');
    $password=C('DB_PWD');
    $db_name   =C('DB_NAME');
    $port = C('DB_PORT');
    //检测数据库是否能正常连接
    $mysqli = mysqli_init();
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    $conn=$mysqli->real_connect($server,$username,$password,$db_name,$port);
    if($conn){
        return true;
    }else{
        return false;
    }
}

/**
 * ############################# 目录或文件操作 ################################
 */

/**
 * @功能说明:文件流下载,防盗链下载
 * @param string $path 文件路径，不包含文件名
 * @param string $name 文件名称
 * @return bool  下载失败返回 false
 */
function download($path,$name){
    $FilePath=$path.$name; //拼接文件物理地址
    if(!file_exists($FilePath)){
        //文件不存在
        return false;
    }else{
        $http=new Org\Net\Http();
        $http->download($FilePath,$name);
    }
}

/**
 * @功能说明: 获取文件真实扩展名，需php环境的finfo功能的支持
 * @param  string $file 文件路径
 * @return array  返回可能的扩展名组合 [多数情况下不同扩展名的MIME是相同的]
 */
function get_file_ext($file){
    $handle=finfo_open(FILEINFO_MIME_TYPE);
    $FileMime=finfo_file($handle,$file);  //获得文件的真实MIME_TYPE
    finfo_close($handle);

    //定义支持判断的扩展名和MIME对照表
    static $mime_types = array(
        //常见视频类型
        'flv'     => 'video/x-flv',
        '3gp'     => 'video/3gpp',
        'avi'     => 'video/x-msvideo',
        'mp3'     => 'audio/mpeg',
        'mp4'     => 'video/mp4',
        'mpeg'    => 'video/mpeg',
        'mpg'     => 'video/mpeg',
        'mpga'    => 'audio/mpeg',
        'rm'      => 'application/vnd.rn-realmedia',
        'swf'     => 'application/x-shockwave-flash',
        'wmv'     => 'video/x-ms-wmv',
        //常见文件类型
        'txt'     =>'text/plain',
        'ppt'     => 'application/vnd.ms-powerpoint',
        'pptx'    =>'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'doc'     => 'application/msword',
        'docx'    =>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'     => 'application/vnd.ms-excel',
        'xlsx'    =>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'dot'     =>'application/msword',
        'exe'     => 'application/octet-stream',
        'html'    =>'text/html',
        'dmg'     => 'application/octet-stream',
        'apk'     => 'application/vnd.android.package-archive',
        'ipa'     =>'application/octet-stream.ipa',
        'js'      => 'application/x-javascript',
        'pdf'     => 'application/pdf',
        'svg'     => 'image/svg+xml',
        'xml'     =>'text/xml',
        'zip'     => 'application/zip',
        'rar'     =>'application/octet-stream',
        'rar'     =>'application/x-rar',
        'tar'     =>'application/x-tar',
        'gz'      => 'application/x-gzip',
        '7z'      =>'application/octet-stream',
        //常见图片类型
        'png'     => 'image/png',
        'ico'     => 'image/x-icon',
        'bmp'     => 'image/bmp',
        'gif'     => 'image/gif',
        'jpeg'    => 'image/jpeg',
        'jpg'     => 'image/jpeg'
    );
    //根据键值，找到符合条件的第一个键名并转化为字符串返回
    $arr=array_keys($mime_types,$FileMime,true);
    return $arr;
}

/**
 * ############################# 数据转换函数 ################################
 */

/**
 * @功能说明:根据IP地址，转换为IP所在城市 [省.市.区.网络服务商]
 * @param   string $ip  客户端ip
 * @return  json数据
 */
function ip_to_address($ip){
    $IpInfo=new \XBCommon\IpAddress();
    $data=$IpInfo->GetAreas($ip);
    if($data['data']['CountryID']!='CN'){
        $address=$data['data']['Country'];   //不等于CN时，可能是内网或国外IP
    }else{
        $address=$data['data']['Region'].$data['data']['City'].$data['data']['County'].$data['data']['Isp'];
    }
    return  $address;
}

/**
 * @功能说明:根据IP地址，获取城市信息
 * @param   string $ip  客户端ip
 * @return  json数据
 */
function ip_to_address2($ip){
    $IpInfo=new \XBCommon\IpAddress();
    $data=$IpInfo->GetAreas($ip);
    // if($data['data']['CountryID']!='CN'){
    //     $address=$data['data']['Country'];   //不等于CN时，可能是内网或国外IP
    // }else{
    //     $address=$data['data']['Region'].$data['data']['City'].$data['data']['County'].$data['data']['Isp'];
    // }
    return  $data;
}

/**
 * @功能说明:讲文本或字符串中的所有图片路径批量更换为指定路径
 * @param $type  替换类型: image 原图 large 大图 small 小图
 * @param $data  要转换的数据
 * @return   返回string
 */
function convert_pic($type,$data){
    $str=null;
    switch ($type){
        case 'image':
            $data=str_replace('/upload/large','/upload/image',$data);
            $str=str_replace('/upload/small','/upload/image',$data);
            break;
        case 'small':
            $data=str_replace('/upload/image','/upload/small',$data);
            $str=str_replace('/upload/large','/upload/small',$data);
            break;
        default:
            $data=str_replace('/upload/image','/upload/large',$data);
            $str=str_replace('/upload/small','/upload/large',$data);
    }

    return $str;
}

/**
 * ############################# 调用缓存函数 ################################
 */

/**
 * @功能说明:调用后台的基本设置中的缓存信息
 * @param string @name 缓存名称
 * @return string $value 返回缓存信息
 */
function get_basic_info($name){
    if(!empty($name)){
        $cache=new \XBCommon\CacheData();
        $info=$cache->BasicInfo();
        return $info[$name];
    }else{
        return '参数错误！';
    }
}

/**
 * ############################# 字符串处理 ################################
 */

/**
 * @功能说明:过滤敏感词汇
 * @param string @value 需要过滤的内容
 * @return string $val 过滤之后的内容
 */
function  filter_word ($value){
    //调用快速缓存信息
    $cache= new XBCommon\CacheData();
    $info=$cache->BasicInfo();
    $words=explode(',',$info['StopWord']);
    $word=array();
    foreach($words as $k=>$v){
        $key=array("Word"=>$v);
        array_push($word,$key);
    }
    $val = $value;
    //替换敏感词汇
    foreach($word as $item){
        $count = mb_strlen($item['Word'],'utf8');
        $val = str_replace($item['Word'],str_repeat('*',$count),$val);   //将非法词汇，提出成星号*
    }
    return $val;
}

/**
 * 功能说明：字符串去掉HTML标签
 * @param $str 需要过滤的内容
 * @param string $tags 需要保留的html标签  参数传递例如:'<a><span><img>'
 * @return mixed|string 过滤后的内容
 */
function filter_html($str,$tags){
    $search = array(
        '@<script[^>]*?>.*?</script>@si',  // 过滤js脚本
        '@<style[^>]*?>.*?</style>@siU',    // 过滤标签样式
        '@<![\s\S]*?--[ \t\n\r]*>@'         // 过滤多行注释，包括CDATA
    );
    $str = preg_replace($search, '', $str); //过滤非法字符 html标签  css样式  js脚本程序
    $str = strip_tags($str,$tags); //脱掉html标签 除保留html标签外
    return $str;
}


/**
 * ############################# 富文本编辑器 ################################
 */


/**
 * 功能说明:加载编辑器js等引用文件
 * @param $type 编辑器名称
 * @return string
 */
function load_editor_js($type){
    $str='';
    switch ($type){
        case 'kindeditor':
            $str.='<script src="/Editor/kindeditor/kindeditor-min.js"></script>'."\r\n";
            $str.='<script src="/Editor/kindeditor/lang/zh_CN.js"></script>'."\r\n";
            break;
        case 'ueditor':
            $str.='<script type="text/javascript" src="/Editor/ueditor/ueditor.config.js"></script>'."\r\n";
            $str.='<script type="text/javascript" src="/Editor/ueditor/ueditor.all.js"></script>'."\r\n";
            $str.='<script src="/Editor/ueditor/lang/zh-cn/zh-cn.js"></script>'."\r\n";
            break;
        default:
            $str='加载编辑器js文件的参数传递错误！';
            break;
    }
    return $str;
}

/**
 * 功能说明:富文本编辑器
 * @param $type 编辑器的名称: 例如ueditor,kindeditor
 * @param  $mode 编辑器的类型: 0 默认模式 1 简单模式
 * @param  $name  页面中被加载的name名称,多个使用逗号分隔
 * @param $width 富文本编辑框的宽度，默认740px
 * @param $height 富文本编辑器的高度，默认300px
 * @return string
 */
function editor($type='kindeditor',$mode=0,$name,$width=740,$height=300){
    $editor=new \XBCommon\Editor();
    $result=$editor->editor($type,$mode,$name,$width,$height);
    return $result;
}

/**
 * 功能说明:ke编辑器上传图片报错提示
 */
function alert($msg) {
    header('Content-type: text/html; charset=UTF-8');
    $json = new Services_JSON();
    echo $json->encode(array('error' => 1, 'message' => $msg));
    exit;
}

/**
 * ############################# 对称加密算法 ################################
 */

/**
 * @功能说明:对称加密算法
 * @param $str 要加密的字符串
 * @param $key 随机密钥
 * @return string
 */
// function encrypt($str,$key){
//     $encrypt_key = md5(rand(0, 32000));
//     $ctr = 0;
//     $result = '';
//     for($i = 0;$i < strlen($str); $i++) {
//         $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
//         $result .= $encrypt_key[$ctr].($str[$i] ^ $encrypt_key[$ctr++]);
//     }
//     $encrypt=new \XBCommon\Encryption();
//     return base64_encode($encrypt->key_encrypt($result, $key));
// }

/*
 * @功能说明:对称解密算法
 * @param $str 要解密的字符串
 * @param $key 随机密钥
 * @return string
 */
// function decrypt($str,$key){
//     $decrypt=new \XBCommon\Encryption();
//     $str = $decrypt->key_encrypt(base64_decode($str), $key);
//     $result = '';
//     for($i = 0;$i < strlen($str); $i++) {
//         $md5 = $str[$i];
//         $result .= $str[++$i] ^ $md5;
//     }
//     return $result;
// }

/**
 * ############################# 导出数据的方法 ################################
 */

/**
 * @功能说明:导出常规的excel表格
 * @param $filename  文件名称
 * @param $header  表格头部
 * @param $data  要导出的数据源
 * @return string 文件流的方式生成文件。
 */
function export_excel($title,$header,$data){
    Vendor('Excel/PHPExcel');
    $objPHPExcel = new PHPExcel();
    $filename=$title.'_'.date("Y_m_d_H_i_s", time()).'.xls'; //拼接文件名

    // 设置表头
    $key = ord("A");
    foreach ($header as $v) {
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    // print_r($data);exit;
    foreach ($data as $key => $rows) { // 行写入
        $span =ord("A");
        foreach ($rows as $keyName => $value) { // 列写入
            $j = chr($span);
            $objActSheet->setCellValue($j . $column, $value);
            $span ++;
        }
        $column ++;
    }

    $filename = iconv("utf-8", "gb2312", $filename);
    // 重命名表
    // 设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:Z1')->getFont()->setBold(true);;
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); // 文件通过浏览器下载
}


/**
 * @功能说明 : 生成缩略图，包含中图小图
 * @param $image
 * @param $spath
 * @param int $width
 * @param int $height
 * @return mixed
 */
function image_thumbs($image,$spath,$width=300,$height=300){ //传入图片
    $Images = new Think\Image();
    $Images->open($image);
    // 生成一个等比的缩略图并保存
    $Images->thumb($width,$height,\Think\Image::IMAGE_THUMB_SCALE)->save($spath);
    return  $spath; //时间戳加后缀
}

function get_up_token(){

    //获取配置信息
    $configInfo = M('sys_inteparameter')->where('IntegrateID=6 ')->select();
    $config = array();
    foreach ($configInfo as $k => $v) {
        $config[$v['ParaName']] = $v['ParaValue'];
    }
//var_dump($config);exit;
    $accessKey =$config['AccessKey'];  //AccessKey 必要条件
    $secretKey =$config['SecretKey'];  //SecretKey 必要条件
    $bucket    =$config['Bucket'];     //Bucket 必要条件

    if(is_null($accessKey)){
        return json_encode(array('result'=>0,'message'=>'缺少必要的AccessKey'));
    }
    if(is_null($secretKey)){
        return json_encode(array('result'=>0,'message'=>'缺少必要的SecretKey'));
    }

    vendor('Qiniu.autoload');
    $auth = new Qiniu\Auth($accessKey,$secretKey);
    $upToken = $auth->uploadToken($bucket);
    return $upToken;
}


//处理会员的消息
function member_sms($UserID=0,$Type=1,$Title='',$Contents=''){
    if($UserID){
        $data = array(
            'UserID'=> $UserID,
            'Type'=> $Type,
            'Title'=> $Title,
            'Contents'=> $Contents,
            'SendTime'=> date('Y-m-d H:i:s')
        );
        M('mem_message')->add($data);
    }
}

/*
 * 根据专业ID 查找其父类ID
 */

function major_cate($table,$where,$array){
    $arr = M($table)->where($where)->find();
    if($arr){
        array_unshift($array,array('ID'=>$arr['ID'],'ParentID'=>$arr['ParentID'],'Name'=>$arr['Name']));
        $arr['ParentID']==0 ? '' : $array=major_cate($table,array('ID'=>$arr['ParentID']),$array);
        return $array;
    }else{
        return false;
    }
}

/*
 * 根据专业ID 查找其父类ID 返回一维数组
 */
function major_cate_str($table,$where,$array){
    $arr = M($table)->where($where)->find();
    if($arr){
        array_unshift($array,$arr['Name']);
        $arr['ParentID']==0 ? '' : $array=major_cate_str($table,array('ID'=>$arr['ParentID']),$array);
        return $array;
    }else{
        return false;
    }
}
/**
 * ############################# 与APP项目相关的 ################################
 */

/**
 * @功能说明:获取前端传递的json数据，并转化为数组形式
 * @return array
 */
function get_json_data(){
    $data=file_get_contents("php://input");
    if(!empty($data)){
        return json_decode($data,true);
    }else{
        return null;
    }
}

/**
 * @功能说明: 传递json数据，post提交信息
 * @param $url
 * @param $jsonStr
 * @return array
 */
function http_post_json($url, $jsonStr)
{
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/json',
            'content' => $jsonStr,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    if($response!=false){
        $httpCode=200;
    }else{
        $httpCode=0;
    }

    return array($httpCode, $response);
}

/**
 * @功能说明:AES CBC模式PKCS7 128位加密算法
 * @param $data 要加密的数据
 * @param $key 随机私有密钥
 * @param $iv 随机向量
 * @return string
 */
function encrypt_pkcs7($data,$key,$iv){
    //补码处理
    $blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $len = strlen($data);
    $pad = $blocksize - ($len % $blocksize);
    $data .= str_repeat(chr($pad), $pad);
    //开始加密
    $encrypted=mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$data,MCRYPT_MODE_CBC,$iv);
    return base64_encode($encrypted);
}

/*
 * @功能说明:AES CBC模式PKCS7 128位解密算法
 * @param $data 要解密的数据
 * @param $key 随机私有密钥
 * @param $iv 随机向量
 * @return string
 */
function decrypt_pkcs7($data,$key,$iv){
    $encryptedData=base64_decode($data);
    $decrypted=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$encryptedData,MCRYPT_MODE_CBC,$iv);

    //php的pkcs7去码处理
    $length = strlen($decrypted);
    $unpadding = ord($decrypted[$length - 1]);
    return substr($decrypted, 0, $length - $unpadding);
}

/**
 * 将数组转换为xml
 * @param array $arr:数组
 * @param object $dom:Document对象，默认null即可
 * @param object $node:节点对象，默认null即可
 * @param string $root:根节点名称
 * @param string $cdata:是否加入CDATA标签，默认为false
 * @return string
 */
function array_to_xml($arr,$dom=null,$node=null,$root='xml',$cdata=false){
    if (!$dom){
        $dom = new DOMDocument('1.0','utf-8');
    }
    if(!$node){
        $node = $dom->createElement($root);
        $dom->appendChild($node);
    }
    foreach ($arr as $key=>$value){
        $child_node = $dom->createElement(is_string($key) ? $key : 'node');
        $node->appendChild($child_node);
        if (!is_array($value)){
            if (!$cdata) {
                $data = $dom->createTextNode($value);
            }else{
                $data = $dom->createCDATASection($value);
            }
            $child_node->appendChild($data);
        }else {
            array_to_xml($value,$dom,$child_node,$root,$cdata);
        }
    }
    return $dom->saveXML();
}

/**
 * @功能说明：将xml转为array（微信）
 * @return array
 */
function xml_to_array($xml) {
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

//通过api地址处理
function https_request($url,$data = null)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    if (!empty($data))
    {
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $output = trim($output, "\xEF\xBB\xBF");//php去除bom头

    //return $output;
    return json_decode($output,true);
}

//清除空格
function trimHtml($str)
{
    $str = trim($str);
    $str = strip_tags($str,"");
    $str = str_replace("\t","",$str);
    $str = str_replace("\r\n","",$str);
    $str = str_replace("\r","",$str);
    $str = str_replace("\n","",$str);
    $str = str_replace(" ","",$str);
    $str = str_replace("&nbsp;","",$str);
    return trim($str);
}
/**
 * 功能说明：字符串去掉HTML标签
 * @param $str 需要过滤的内容
 * @param string $tags 需要保留的html标签
 * @return mixed|string 过滤后的内容
 */
function FilterHtml($str,$tags='<img><a>'){
    //过滤时默认保留html中的<a><img>标签
    $search = array(
        '@<script[^>]*?>.*?</script>@si',  // 过滤js脚本
        '@<style[^>]*?>.*?</style>@siU',    // 过滤标签样式
        '@<![\s\S]*?--[ \t\n\r]*>@'         // 过滤多行注释，包括CDATA
    );
    $str = preg_replace($search, '', $str); //过滤非法字符 html标签  css样式  js脚本程序
    $str = strip_tags($str,$tags); //脱掉html标签 除保留html标签外
    return $str;
}

//中英文字符串截取
function cur_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
        for($i=0; $i<$strlen; $i++)
        {
            if($i>=$start && $i<($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
        return $tmpstr;
    }
}
/**
 * 功能说明：判断今天能否发布转让信息了
 * @return boole  true能发  false不能发了
 */
function ispush_zr(){
    $Zrnumbs=M('sys_basicinfo')->field('Zrnumbs')->find();
    if(!$Zrnumbs['Zrnumbs']){
        return false;
    }
    $ip=$_SERVER['REMOTE_ADDR'];//浏览当前页面的用户的 IP 地址
    $startTime=date('Y-m-d')." 00:00:00";
    $endTime=date('Y-m-d')." 23:59:59";
    $where=array();
    $where['Ip']=array('eq',$ip);
    $where['AddTime']=array('BETWEEN',array($startTime,$endTime));
    $count=M('comp_transfer')->where($where)->count();
    if($count>=$Zrnumbs['Zrnumbs']){
        return false;
    }else{
        return true;
    }
}
/**
 * 功能说明：判断今天能否留言购买了
 * @return boole  true能发  false不能发了
 */
function isbuy_ly(){
    $Lynumbs=M('sys_basicinfo')->field('Lynumbs')->find();
    if(!$Lynumbs['Lynumbs']){
        return false;
    }
    $ip=$_SERVER['REMOTE_ADDR'];//浏览当前页面的用户的 IP 地址
    $startTime=date('Y-m-d')." 00:00:00";
    $endTime=date('Y-m-d')." 23:59:59";
    $where=array();
    $where['Ip']=array('eq',$ip);
    $where['AddTime']=array('BETWEEN',array($startTime,$endTime));
    $count=M('comp_buymsg')->where($where)->count();
    if($count>=$Lynumbs['Lynumbs']){
        return false;
    }else{
        return true;
    }
}

/**
 * 功能说明：判断今天能否对需求发布进行留言
 * @return boole  true能发  false不能发了
 */
function isbuy_needs(){
    $Lynumbs=M('sys_basicinfo')->field('Lynumbs')->find();
    if(!$Lynumbs['Lynumbs']){
        return false;
    }
    $ip=$_SERVER['REMOTE_ADDR'];//浏览当前页面的用户的 IP 地址
    $startTime=date('Y-m-d')." 00:00:00";
    $endTime=date('Y-m-d')." 23:59:59";
    $where=array();
    $where['Ip']=array('eq',$ip);
    $where['AddTime']=array('BETWEEN',array($startTime,$endTime));
    $count=M('comp_needsmsg')->where($where)->count();
    if($count>=$Lynumbs['Lynumbs']){
        return false;
    }else{
        return true;
    }
}
/*
 * $type 会员类别
 * $uid  访问者id
 * $cid  公司id
 * $name 公司名称
 */


function strReplace($type,$uid,$cid,$name){
    if(  ($type==3 && $uid==$cid) || $type==2 ){
        $newName=$name;
    }else{
        if(mb_strlen($name,'utf-8')>=8){
            $endstr=mb_strlen($name)-4;
            $newName = mb_substr($name,0,2,'UTF-8').'****'.mb_substr($name,$endstr,25,'UTF-8');
        }else{
            $newName = mb_substr($name,0,2,'UTF-8').'****';
        }

        //$newName=mb_substr($name,0,2).'****'.mb_substr($name,7);
    }

    return $newName;
}


/*
 * $type 会员类别
 * $uid  访问者id
 * $cid  人才id
 * $name 人才姓名
 */
function nameReplace($type,$uid,$cid,$name){
    if(  ($type==2 && $uid==$cid) || $type==3 ){
        $newName=$name;
    }else{
        $newName='*'.mb_substr($name,1);
    }

    return $newName;
}

/*
 * 过滤省市地址 兼职
 * $pid 省id
 * $cid 市id
 * $did 县id
 */
function addrLink($pid,$cid,$did){
    $addInfo =M('sys_areas')->field('Name')->where(array('ID'=>array('in',"$pid,$cid,$did")))->select();
    $addStr=array_column($addInfo,'Name');
    if($pid==2 || $pid==23 || $pid==886 || $pid==2477 || $pid==10000){
       return  $addStr[0];
    }else{
        return  $addStr[0].'-'.$addStr[1];;
    }

}


/*
 * 过滤省市地址 兼职,招聘
 * $pid 省id
 * $cid 市id
 * $did 县id
 */
function addrArea($pid,$cid,$did){
    $addInfo =M('sys_areas')->field('Name')->where(array('ID'=>array('in',"$pid,$cid,$did")))->select();
    $addStr=array_column($addInfo,'Name');
    if($pid==2 || $pid==23 || $pid==886 || $pid==2477){
        //return  array($addStr[0],$addStr[0].'-'.$addStr[2]);
        return  $addStr[0].'-'.$addStr[2];
    }else{
       // return  array($addStr['1'],$addStr[0].'-'.$addStr[1].'-'.$addStr[2]);
       return  $addStr[0].'-'.$addStr[1].'-'.$addStr[2];
    }
}

/*
 * 过滤省市地址 招聘
 * $pid 省id
 * $cid 市id
 * $did 县id
 */
function addr($pid,$cid,$did){
    $addInfo =M('sys_areas')->field('Name')->where(array('ID'=>array('in',"$pid,$cid,$did")))->select();
    $addStr=array_column($addInfo,'Name');
    if($pid==2 || $pid==23 || $pid==886 || $pid==2477){
        //return  array($addStr[0],$addStr[0].'-'.$addStr[2]);
        return  $addStr[0];
    }else{
       // return  array($addStr['1'],$addStr[0].'-'.$addStr[1].'-'.$addStr[2]);
       return  $addStr[1];
    }
}
//-------------------------------今日融函数封装--------------------------
/**
 * 用户余额变动记录
 * @param $Type       int   交易类型  0：收入  1：支出
 * @param $Amount        float   变动金额
 * @param $MemberID       int   操作的会员ID
 * @param $SruType       int  收入类型:1推荐会员 2网贷 3信用卡 4查征信
 * @param $Mtype       int  会员类型:1一级会员 2二级会员(推荐会员+)
 * @param $Description  String    变更描述
 * @param $Intro       String  描述2
 * @param $oid       int  结果表的id
 */
function balancerecord($Type,$Amount,$MemberID,$SruType,$Mtype,$Description,$Intro,$oid){
    $data=array();
    $data['Type']=$Type;
    if($SruType){
        $data['SruType']=$SruType;
    }
    if($Mtype){
        $data['Mtype']=$Mtype;
    }
    if($oid){
        $data['oid']=$oid;
    }
    $data['Amount']=$Amount;
    $CurrentBalance=M('mem_info')->where(array('ID'=>$MemberID))->getField('Balance');
    $data['CurrentBalance']=$CurrentBalance;
    if($Description){
        $data['Description']=$Description;
    }
    if($Intro){
        $data['Intro']=$Intro;
    }
    $data['UserID']=$MemberID;
    $data['UpdateTime']=date('Y-m-d H:i:s');
    $data['TradeCode']=date("YmdHis").rand(10000,99999);
    $result=M('mem_balances')->add($data);
    if($result){
        //短信和微信推送信息
        $Message = new \Extend\Message($result,4);
        $Message->sms();
    }

}
//订单分佣记录  目前只针对余额
function shareOrderMoneyRecord($OrderAmount,$MID,$SruType,$Description,$Intro,$oid){
    if($MID){  
        $Pid=M("mem_info")->where("ID=".intval($MID))->getfield("Referee");
    }
    $Mtype='0';//会员类型
    if($Pid){ //一级
        $oneUser=M("mem_info")->where("ID=".intval($Pid)." and IsDel=0 and Status=1 ")->find();
        //4查征信,查征信只给上一级分佣,且按照基本信息中'征信查询收益设置'来做
        if($SruType=='4' && $oneUser){
            $bacinfo=M('sys_basicinfo')->field('Zenxinsy1,Zenxinsy2,Zenxinsy3,Zenxinsy4')->find();
            $zenxinsy='';
            if($oneUser['Mtype']=='1'){
                $zenxinsy=$bacinfo['Zenxinsy1'];
            }elseif($oneUser['Mtype']=='2'){
                $zenxinsy=$bacinfo['Zenxinsy2'];
            }elseif($oneUser['Mtype']=='3'){
                $zenxinsy=$bacinfo['Zenxinsy3'];
            }elseif($oneUser['Mtype']=='4'){
                $zenxinsy=$bacinfo['Zenxinsy4'];
            }
            $Amount=$OrderAmount*$zenxinsy/100;                                 //一级分的佣金
            M("mem_info")->where('ID='.$oneUser['ID'])->setInc('Balance',$Amount);
            if($SruType=='1'){
                $Mtype='1';
            }
            balancerecord(0,$Amount,$oneUser['ID'],$SruType,$Mtype,$Description,$Intro,$oid); 
        }else{
            if($oneUser){  //只有正常用户才能得到分佣
                $Charge=M("mem_charge")->where("ID=1")->getfield("Charge");   //一级分的佣金比例
                $Amount=$OrderAmount*$Charge/100;                                 //一级分的佣金
                M("mem_info")->where('ID='.$oneUser['ID'])->setInc('Balance',$Amount);
                if($SruType=='1'){
                    $Mtype='1';
                }
                balancerecord(0,$Amount,$oneUser['ID'],$SruType,$Mtype,$Description,$Intro,$oid); 
            }
            if($oneUser['Referee']){  //二级
                $twoUser=M("mem_info")->where("ID=".intval($oneUser['Referee'])." and IsDel=0 and Status=1 ")->find();
                if($twoUser){
                    $Charge2=M("mem_charge")->where("ID=2")->getfield("Charge"); //二级分的佣金比例
                    $Amount1=$OrderAmount*$Charge2/100;                              //二级分的佣金
                    
                    M("mem_info")->where('ID='.$twoUser['ID'])->setInc('Balance',$Amount1);
                    if($SruType=='1'){
                        $Mtype='2';
                    }
                    balancerecord(0,$Amount1,$twoUser['ID'],$SruType,$Mtype,$Description,$Intro,$oid);
                }
            }
        }
    }
}
//生成带有二维码融客图片
function PrintQrcode($id){
    // $Crypt = new \Extend\Crypt();
    // $str = $Crypt->encrypt($id);
    $str =get_basic_info("SystemDomain")."/Daibeishop/platweb?uid=".$id;
    vendor("phpqrcode.qrcode");
    $QRcode = new \Qcode();
    $SmallPaths = THINK_PATH.'../Upload/qrcode/'.$id.'/';
    if(!is_dir($SmallPaths)){
        mkdir($SmallPaths,0777,true);
    }
    $QRcode->code($str,$SmallPaths.$id."_0.png");

    $timage ="./Upload/qrcode/".$id.'/'.$id."_0.png";
    $image = new \Think\Image();
    $image->open($timage);
    $size = $image->size();

    $file = './Upload/qrcode/'.$id.'/'.$id.".png";
    if((int)$size[0]>200) {
        thumbs($timage, $file, 360, 360);  //压缩
    }else{
        thumbs($timage, $file,0,0);
    }
//    $parent = './Upload/parent.png';
    $parent=M('sys_basicinfo')->where(array('ID'=>'1'))->getField('RongkeImg');
    $parent='.'.$parent;
    if(is_file($file)){
        $image_1 = imagecreatefrompng($parent);
        $image_2 = imagecreatefrompng($file);
        imagecopymerge($image_1, $image_2, 400, 1200, 0, 0, imagesx($image_2), imagesy($image_2), 100);
        $fal = imagepng($image_1, THINK_PATH.'../Upload/qrcode/'.$id.'/'.$id."__rongke.png");
        $file1 = './Upload/qrcode/'.$id.'/'.$id."__rongke.png";

        if(is_file($file1)){
            $data['result'] = 1;
            $data['images'] = $file1;
        }else{
            $data['result'] = 2;
            $data['images'] = '';
        }
    }else{
        $data['result'] = 3;
        $data['images'] = '';
    }
    //删除二维码无用图片
    unlink($timage);
    unlink($file);
    return $data;
}

//生成带有二维码 我的专属海报
function PrintQrcode2($uid,$id,$field){
    ini_set("memory_limit", "1024M");
    // $Crypt = new \Extend\Crypt();
    // $str = $Crypt->encrypt($id);
    //'/Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'."ZsUrl1.png"
    $goodtype=M('items')->where(array('ID'=>$id))->getField('Itype');
    $TrueName=M('mem_info')->where(array('ID'=>$uid))->getField('TrueName');
    $str ='';//二维码内容
    if($goodtype=='1'){
        //平台网贷
        $str=get_basic_info("SystemDomain")."/Daibeishop/detail?uid=".$uid.'&id='.$id;
    }elseif($goodtype=='2'){
        //信用卡贷
        $str=get_basic_info("SystemDomain")."/Daibeishop/cdetail?uid=".$uid.'&id='.$id;
    }
    vendor("phpqrcode.qrcode");
    $QRcode = new \Qcode();
    $SmallPaths = THINK_PATH.'../Upload/qrcode/'.$uid.'/'.'item_'.$id.'/';
    if(!is_dir($SmallPaths)){
        mkdir($SmallPaths,0777,true);
    }

    $QRcode->code($str,$SmallPaths.$field."_0.png");
    $timage ="./Upload/qrcode/".$uid.'/'.'item_'.$id.'/'.$field."_0.png";
    $image = new \Think\Image();
    $image->open($timage);
    $size = $image->size();

    $file = './Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'.$field."_1.png";
    if((int)$size[0]>190) {
        thumbs($timage, $file, 190, 190);  //压缩
    }else{
        thumbs($timage, $file,0,0);
    }
    //查询模板图片
    $urls=M('items')->where(array('ID'=>$id))->getField($field);
    $xcfonts=M('sys_basicinfo')->where(array('ID'=>'1'))->getField('Xcfonts');
    $parent = '.'.$urls;
    if(is_file($file)){
        $image_1 = imagecreatefrompng($parent);
        $image_2 = imagecreatefrompng($file);
        //写字
        $white = ImageColorAllocate($image_1, 5,5,5);
        $red = ImageColorAllocate($image_1, 248,11,11);
        ImageTTFText($image_1, 40, 0, 20, 980, $white, "./simsun.ttf", "我是");
        ImageTTFText($image_1, 40, 0, 140, 980, $red, "./simsun.ttf", $TrueName);

        ImageTTFText($image_1, 23, 0, 32, 1030, $white, "./simsun.ttf",$xcfonts);

        ImageTTFText($image_1, 33, 0, 50, 1100, $white, "./simsun.ttf",'长按识别,立即申请>>>');

        imagecopymerge($image_1, $image_2, 510, 925, 0, 0, imagesx($image_2), imagesy($image_2), 100);
        $fal = imagepng($image_1, THINK_PATH.'../Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'.$field.".png");
        $file1 = './Upload/qrcode/'.$uid.'/'.'item_'.$id.'/'.$field.".png";

        if(is_file($file1)){
            $data['result'] = 1;
            $data['images'] = $file1;
        }else{
            $data['result'] = 2;
            $data['images'] = '';
        }
    }else{
        $data['result'] = 3;
        $data['images'] = '';
    }
    //删除二维码无用图片
    unlink($timage);
    unlink($file);
    return $data;
}
//生成分享二维码图片
function PrintShareQrcode($shareurl,$id){
    // $Crypt = new \Extend\Crypt();
    // $str = $Crypt->encrypt($id);
    $str =$shareurl;
    vendor("phpqrcode.qrcode");
    $QRcode = new \Qcode();
    $SmallPaths = THINK_PATH.'../Upload/qrcode/'.$id.'/';
    if(!is_dir($SmallPaths)){
        mkdir($SmallPaths,0777,true);
    }

    $QRcode->code($str,$SmallPaths.$id."__shareqrcode.png");
}
//生成带有二维码的分享图片
function PrintQrcodeShare($id){

    //分享的链接
    $str =get_basic_info("SystemDomain").'/Register/index?ui='.$id;
    vendor("phpqrcode.qrcode");
    $QRcode = new \Qcode();
    $SmallPaths = THINK_PATH.'../Upload/qrcode/'.$id.'/';
    if(!is_dir($SmallPaths)){
        mkdir($SmallPaths,0777,true);
    }

    $QRcode->code($str,$SmallPaths.$id."__qrcode.png");
    $timage ="./Upload/qrcode/".$id.'/'.$id."__qrcode.png";
    $image = new \Think\Image();
    $image->open($timage);
    $size = $image->size();

    $file = './Upload/qrcode/'.$id.'/'.$id."_share.png";
    if((int)$size[0]>200) {
        thumbs($timage, $file, 360, 360);  //压缩
    }else{
        thumbs($timage, $file,0,0);
    }
//    $parent = './Upload/parent.png';
    $parent=M('sys_basicinfo')->where(array('ID'=>'1'))->getField('ShareImg');
    $parent='.'.$parent;
    if(is_file($file)){
        $image_1 = imagecreatefrompng($parent);
        $image_2 = imagecreatefrompng($file);
        imagecopymerge($image_1, $image_2, 220, 1030, 0, 0, imagesx($image_2), imagesy($image_2), 100);
        $fal = imagepng($image_1, THINK_PATH.'../Upload/qrcode/'.$id.'/'.$id."__shareqrcode.png");
        $file1 = './Upload/qrcode/'.$id.'/'.$id."__shareqrcode.png";

        if(is_file($file1)){
            $data['result'] = 1;
            $data['images'] = $file1;
        }else{
            $data['result'] = 2;
            $data['images'] = '';
        }
    }else{
        $data['result'] = 3;
        $data['images'] = '';
    }
    //删除二维码无用图片
    unlink($timage);
    unlink($file);
    return $data;
}
function thumbs($image,$spath,$width=300,$height=300){ //传入图片
    $Images = new \Think\Image(); // 给avator.jpg 图片添加logo水印
    $Images->open($image);
    // 生成一个等比的缩略图并保存
    $Images->thumb($width,$height,\Think\Image::IMAGE_THUMB_SCALE)->save($spath);
    return  $spath; //时间戳加后缀
}

//不同环境下获取真实的IP
function get_ip(){
    //判断服务器是否允许$_SERVER
    if(isset($_SERVER)){    
        if(isset($_SERVER[HTTP_X_FORWARDED_FOR])){
            $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
        }elseif(isset($_SERVER[HTTP_CLIENT_IP])) {
            $realip = $_SERVER[HTTP_CLIENT_IP];
        }else{
            $realip = $_SERVER[REMOTE_ADDR];
        }
    }else{
        //不允许就使用getenv获取  
        if(getenv("HTTP_X_FORWARDED_FOR")){
              $realip = getenv( "HTTP_X_FORWARDED_FOR");
        }elseif(getenv("HTTP_CLIENT_IP")) {
              $realip = getenv("HTTP_CLIENT_IP");
        }else{
              $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}  
/**
 * @功能说明:根据ip地址,获取城市名称和行政编号
 * @return  array
 */
function get_cityinfo(){
    $ip=get_ip();//获取真实ip地址
    $rest=ip_to_address2($ip);//获取城市信息
    return array(
        'cityname'=>$rest['data']['City'],
        'code'=>$rest['data']['CityID'],
        );
}
//根据行政编号,返回地区id值
function getareaid($code){
    $id=M('sys_areas')->where(array('Code'=>$code))->getField('ID');
    return $id;
}
//友盟消息推送
function upush($Contents,$client='',$device_tokens=''){
    vendor('UmengSDKV15.Demo');

    if($device_tokens && $Contents){
        if($client == 'ios'){
            $demo = new \Demo();
            $demo->sendIOSUnicast($device_tokens,$Contents);
        }else{
            $demo = new \Demo('5b2dbb44b27b0a5e99000034','lwprehgf6zikmcpbrhzo61ufziaudkbd');
            $demo->sendAndroidUnicast($device_tokens,$Contents);
        }
    }
}

/**
 * ############################# 网站授权方法 ################################
 */
//校验网站授权
function checkshouquan(){
    $urls='http://auth.ahceshi.com/sqapi.php/';
    $client='shouquan';
    $username='jrr';//用户名
    $password='jrr123456';//密码

    //请求登录 数据
    $logdata=array(
        'client'=>$client,
        'username'=>$username,
        );
    //请求校验  数据
    $checkdata=array(
        'Webs'=>$_SERVER['HTTP_HOST'],//当前域名
        'server'=>array(
            'wangka'=>'123456wk',//网卡信息
            'cpuinfo'=>'123456cpu',//cpu信息
            'yingpan'=>'123456yingpan',//硬盘信息
            'xitong'=>php_uname(),//系统类型及版本号信息
            )
        );
    //获取sqtoken
    $XBCache=new \XBCommon\XBCache();
    //$XBCache->Remove('sqtoken');//清空缓存,不注册,则是实时
    $sqtoken=$XBCache->GetCache('sqtoken');//获取 校验数据

    $retArr=array();
    if(!$sqtoken){
        $logresult=checklogins($urls,$client,$username,$password);//登录
        if($logresult['result']=='1'){
            $sqtoken=$logresult['data'];
        }else{
            $retArr=array(
              'result'=>'0',
              'message'=>$logresult['message'],
            );
        }
    }
   
    if($sqtoken){
        //获取校验信息
        $url2=$urls.'/core/tool/checkquanxian';
        $querydata=array(
            'token'=>$sqtoken['token'],
            'dynamic'=>encrypt_pkcs7(json_encode($checkdata),$sqtoken['key'],$sqtoken['iv']),
            );
        $querydata=json_encode($querydata);
        $getdatas=https_request($url2,$querydata);
        if($getdatas['result']=='-1'){
            //表示登录已失效,重新登录再试一次
            $logresult=checklogins($urls,$client,$username,$password);//登录
            if($logresult['result']=='1'){
                $sqtoken=$logresult['data'];
            }else{
                $retArr=array(
                  'result'=>'0',
                  'message'=>$logresult['message'],
                );
            }
            //再次 获取校验信息
            $querydata=array(
                'token'=>$sqtoken['token'],
                'dynamic'=>encrypt_pkcs7(json_encode($checkdata),$sqtoken['key'],$sqtoken['iv']),
                );
            $querydata=json_encode($querydata);
            $getdatas=https_request($url2,$querydata);
        }
        if($getdatas['result']=='1'){
            //密文解密
           $json_data=json_decode(decrypt_pkcs7($getdatas['data'],$sqtoken['key'],$sqtoken['iv']),true);
           if($json_data){
             //查看是否校验成功
             if($json_data['checkflag']==1){
                $retArr=array(
                  'result'=>'1',
                  'message'=>'授权成功',
                );
             }
           }else{
              $retArr=array(
                'result'=>'0',
                'message'=>'解密失败',
                );
           }
        }else{
            $retArr=array(
                'result'=>'0',
                'message'=>$getdatas['message'],
                );
        }
    }else{
        $retArr=array(
            'result'=>'0',
            'message'=>'生成token秘钥失败',
            );
    }
    return $retArr;
}
//请求授权登录
function checklogins($urls,$client,$username,$password){
    //请求登录
    $logdata=array(
        'client'=>$client,
        'username'=>$username,
        );
    $urls1=$urls.'/core/tool/timestamp?'.http_build_query($logdata);
    $retdata=httpGet2($urls1);//授权登录
    $retArr=array();
    if($retdata['result']=='1'){
        //获取数据成功,生成对应的token
        $client = $client;
        $ticksid = $retdata['data']['ID'];
        $psd = md5($password);
        $Val=$retdata['data']['Val'];
        $name = substr(md5(md5($username)),2,30);
        $key = 'XB'.substr(md5($name.$Val),2,30);
        $iv = 'XB'.substr(md5($psd.$ticksid),2,14);
        $token = substr(md5($name.$Val),0,30).substr(md5($client.$psd.$ticksid),2,30);
        if($token){
            $sqtoken=array(
                'token'=>$token,
                'key'=>$key,
                'iv'=>$iv,
                );
            //获取验证信息
            $XBCache=new \XBCommon\XBCache();
            $XBCache->Remove('sqtoken');//清空缓存,不注册,则是实时的
            //保存到缓存中
            $XBCache->Insert('sqtoken',$sqtoken);
            $retArr=array(
                'result'=>'1',
                'message'=>'成功!',
                'data'=>$sqtoken,
                );
        }else{
            $retArr=array(
                'result'=>'0',
                'message'=>'生成token秘钥失败',
                );
        }
    }else{
        $retArr=array(
            'result'=>'0',
            'message'=>$retdata['message'],
            );
    }
    return $retArr;
}
function httpGet2($url){
    $oCurl = curl_init();//实例化
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );//是否返回值，1时给字符串，0输出到屏幕
    $sContent = curl_exec($oCurl);//获得页面数据
    $aStatus = curl_getinfo($oCurl);//获取CURL连接数据的信息
    curl_close($oCurl);//关闭资源
    //获取成功
    $output_array = json_decode($sContent,true);//转换json格式
    return $output_array;
}

/**
 * 分销查征信分佣记录
 */
function shareZenxinRecord($OrderAmount,$MID,$SruType,$Description,$Intro,$oid){

        $oneUser=M("mem_info")->where("ID=".intval($MID)." and IsDel=0 and Status=1 ")->find();
        //4查征信,别人付钱查征信给自己分佣,且按照基本信息中'征信查询收益设置'来做
        if($SruType=='4' && $oneUser){
            $bacinfo=M('sys_basicinfo')->field('Zenxinsy1,Zenxinsy2,Zenxinsy3,Zenxinsy4')->find();
            $zenxinsy='';
            if($oneUser['Mtype']=='1'){
                $zenxinsy=$bacinfo['Zenxinsy1'];
            }elseif($oneUser['Mtype']=='2'){
                $zenxinsy=$bacinfo['Zenxinsy2'];
            }elseif($oneUser['Mtype']=='3'){
                $zenxinsy=$bacinfo['Zenxinsy3'];
            }elseif($oneUser['Mtype']=='4'){
                $zenxinsy=$bacinfo['Zenxinsy4'];
            }
            $Amount=$OrderAmount*$zenxinsy/100;                                 //一级分的佣金
            M("mem_info")->where('ID='.$MID)->setInc('Balance',$Amount);
            if($SruType=='1'){
                $Mtype='1';
            }
            balancerecord(0,$Amount,$MID,$SruType,$Mtype,$Description,$Intro,$oid);
    }
}

function getTodayIp($time,$ip){
	$IPARR=\XBCommon\XBCache::GetCache('IPARR');
	if($IPARR){
		$tm = $IPARR['time'];
		if($time==$tm){
			$ipArr = $IPARR['ipArr'];
			if(in_array($ip,$ipArr)){
				return true;
			}else{
				$arr = array();
				$arr['time'] = $time; 
				array_push($ipArr,$ip);
				$arr['ipArr'] = $ipArr;
				\XBCommon\XBCache::Remove('IPARR');
				\XBCommon\XBCache::Insert('IPARR',$arr);
				return false;
			}
		}else{
			\XBCommon\XBCache::Remove('IPARR');
			$arr = array();
			$arr['time'] = $time;
			$ipArr = array();
			array_push($ipArr,$ip);
			$arr['ipArr'] = $ipArr;
			\XBCommon\XBCache::Insert('IPARR',$arr);
			return false;
		}
	 
	}else{
		$arr = array();
		$arr['time'] = $time;
		$ipArr = array();
		array_push($ipArr,$ip);
		$arr['ipArr'] = $ipArr;
		\XBCommon\XBCache::Insert('IPARR',$arr);
		return false;
	}
  }