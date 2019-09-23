<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2017-09-12 09:10
 * 功能说明: 微信自动回复控制器
 */
namespace Home\Controller;
use Think\Controller;
use XBCommon\CacheData;
use XBCommon\XBCache;

class WechathfController extends Controller
{
	const TOKEN='SzMhwjccnRFvsVC2NnuAQmQuMyzuuZic';
	public function index(){
		if (!isset($_GET['echostr'])) {
		  $this->responseMsg();
		}else{
		  $this->valid();
		}
	}
	//验证消息
  public function valid(){
	  	$echoStr = $_GET["echostr"];
	    if($this->checkSignature()){
	      echo $echoStr;
	      exit;
	    }
  }
  //检查签名
  private function checkSignature(){
  	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
	    $token = self::TOKEN;
	    $tmpArr = array($token, $timestamp, $nonce);
	    sort($tmpArr, SORT_STRING);
	    $tmpStr = implode($tmpArr);
	    $tmpStr = sha1($tmpStr);
	    if($tmpStr == $signature){
	      return true;
	    }else{
	      return false;
	    }
  }
  //响应消息
  public function responseMsg(){
  		$postStr = file_get_contents('php://input');
	    if (!empty($postStr)){
	      //$this->logger("R ".$postStr);
	      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	      $RX_TYPE = trim($postObj->MsgType);
	    
	      switch ($RX_TYPE)
	      {
	        case "event":
	          $result = $this->receiveEvent($postObj);
	          break;
	        case "text":
	          $result = $this->receiveText($postObj);
	          break;
	        case "image":
	          $result = $this->receiveImage($postObj);
	          break;
	        case "location":
	          $result = $this->receiveLocation($postObj);
	          break;
	        case "voice":
	          $result = $this->receiveVoice($postObj);
	          break;
	        case "video":
	          $result = $this->receiveVideo($postObj);
	          break;
	        case "link":
	          $result = $this->receiveLink($postObj);
	          break;
	        default:
	          $result = "unknow msg type: ".$RX_TYPE;
	          break;
	      }
	      //$this->logger("T ".$result);
	      echo $result;
	    }else {
	      echo "";
	      exit;
	    }
  }
  //接收事件消息
  private function receiveEvent($object)
  {
      $WXSpeech=M('sys_basicinfo')->where(array('ID'=>'1'))->getField('WXSpeech');
    $content = "";
    switch ($object->Event)
    {
      case "subscribe":
        $content = $WXSpeech;
        $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
        break;
      case "unsubscribe":
        $content = "取消关注";
        break;
      case "SCAN":
        $content = "扫描场景 ".$object->EventKey;
        break;
      case "CLICK":
        switch ($object->EventKey)
        {
          case "COMPANY":
            $content = "融易借提供互联网相关产品与服务。";
            break;
          default:
            $content = "点击菜单：".$object->EventKey;
            break;
        }
        break;
      case "LOCATION":
        $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
        break;
      case "VIEW":
        $content = "跳转链接 ".$object->EventKey;
        break;
      default:
        $content = "receive a new event: ".$object->Event;
        break;
    }
    $result = $this->transmitText($object, $content);
    return $result;
  }
  //接收文本消息
  private function receiveText($object)
  {
  	$content='';
    $infoArr='';
    if($object->Content){
      $infoArr=$this->pipeinews($object->Content);
      if($infoArr){
         //Hftype 回复消息类型:1文本回复 2图文回复 3语音回复
         if($infoArr['Hftype']=='1'){
           //文本回复
           $content = $infoArr['Contents'];
         }elseif($infoArr['Hftype']=='2'){
           //图文回复
           $contArr=unserialize($infoArr['Contents']);
           foreach($contArr as $k=>$v){
              $content[] = array("Title"=>$v['Title'], "Description"=>"", "PicUrl"=>'http://'.$_SERVER['HTTP_HOST'].$v['Pic'], "Url" =>$v['linkurl']);
           }
         }
      }
    }
  	// switch ($object->Content){
   //    case "你好":
   //      $content = "你好啊!我是小融:http://jrr.ahceshi.com";
   //      break;
   //    case "音乐":
   //      $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
   //      break;
   //    default:
   //      $content = "欢迎来到今日融,回复:搜+产品名,来进行搜索产品,如:'搜人人贷',了解更多请点击:http://jrr.ahceshi.com";
   //      break;
   //  }
  	if(!$content){
  		$content = "欢迎来到壹借帮,了解更多请点击:http://t.cn/EKOQGmp";
  	}

    if(is_array($content)){
      if (isset($content[0]['PicUrl'])){
        $result = $this->transmitNews($object, $content);
      }else if (isset($content['MusicUrl'])){
        $result = $this->transmitMusic($object, $content);
      }
    }else{
      $result = $this->transmitText($object, $content);
    }
    return $result;
  }
  private function receiveImage($object)
  {
    $content = array("MediaId"=>$object->MediaId);
    $result = $this->transmitImage($object, $content);
    return $result;
  }
  private function receiveLocation($object)
  {
    $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
    $result = $this->transmitText($object, $content);
    return $result;
  }
  private function receiveVoice($object)
  {
    if (isset($object->Recognition) && !empty($object->Recognition)){
      $content = "你刚才说的是：".$object->Recognition;
      $result = $this->transmitText($object, $content);
    }else{
      $content = array("MediaId"=>$object->MediaId);
      $result = $this->transmitVoice($object, $content);
    }
    return $result;
  }
  private function receiveVideo($object)
  {
    $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
    $result = $this->transmitVideo($object, $content);
    return $result;
  }
  private function receiveLink($object)
  {
    $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
    $result = $this->transmitText($object, $content);
    return $result;
  }
  private function transmitText($object, $content)
  {
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
    return $result;
  }
  private function transmitImage($object, $imageArray)
  {
    $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";
    $item_str = sprintf($itemTpl, $imageArray['MediaId']);
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";
    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }
  private function transmitVoice($object, $voiceArray)
  {
    $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";
    $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";
    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }
  private function transmitVideo($object, $videoArray)
  {
    $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";
    $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";
    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }
  private function transmitNews($object, $newsArray)
  {
    if(!is_array($newsArray)){
      return;
    }
    $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
    $item_str = "";
    foreach ($newsArray as $item){
      $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
    }
    $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";
    $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
    return $result;
  }
  private function transmitMusic($object, $musicArray)
  {
    $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";
    $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";
    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
  }
  private function logger($log_content)
  {
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
      sae_set_display_errors(false);
      sae_debug($log_content);
      sae_set_display_errors(true);
    }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
      $max_size = 10000;
      $log_filename = "log.xml";
      if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
      file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
    }
  }
  //查询回复的文本,跟后台设置的进行匹配
  public function pipeinews($text){
     $lists=M('wx_wechats')->where(array('Status'=>'1','IsDel'=>'0'))->select();
     $retArr=array();//返回值
     if($lists){
         foreach($lists as $k=>$v){
            $wordArr=array();
            if($v['Keystatus']=='1'){
              //完全匹配
              if(strpos('|'.$v['Keywords'].'|','|'.$text.'|')!==false){
                //匹配上了
                $retArr['Hftype']=$v['Hftype'];//回复消息类型:1文本回复 2图文回复 3语音回复
                $retArr['Contents']=$v['Contents'];
                break;
              }
            }elseif($v['Keystatus']=='2'){
              //包含匹配
              $wordArr=explode('|',$v['Keywords']);
              $isflag=false;
              foreach($wordArr as $k2=>$v2){
                 if(strpos($text,$v2)!==false){
                   //匹配上了
                   $isflag=true;break;
                 }
              }
              if($isflag){
                //匹配上了
                $retArr['Hftype']=$v['Hftype'];//回复消息类型:1文本回复 2图文回复 3语音回复
                $retArr['Contents']=$v['Contents'];
                break;
              }
            }
         }
     }
     return $retArr;
  }

}