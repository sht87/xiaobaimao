<?php
function httpGet($url){
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
    if(intval($output_array["status"])==1){
        return $output_array;
    }else{
        return false;
    }
}
//-----------------------------------------------------------------------------------------------------------
//根据订单号修改购买代理记录信息
function change_dlorder_data($out_trade_no,$trade_no){
    $dailinfos=M('mem_buydaili')->where(array('OrderSn'=>$out_trade_no))->find();
    $mem_info=M('mem_info')->where(array('ID'=>$dailinfos['UserID']))->find();

    $Trans = M();
    $Trans->startTrans();
    $dl_data['Status'] = '2';
    $dl_data['Batch'] = $trade_no;
    $dl_data['PayTime'] = date('Y-m-d H:i:s');
    $flag1 = $Trans->table('xb_mem_buydaili')->where(array('OrderSn'=>$out_trade_no))->save($dl_data);//修改订单状态
    if($flag1){
        //修改会员表信息
        $mtype='';
        if($dailinfos['LevelID']=='1'){
            $mtype='2';
        }elseif($dailinfos['LevelID']=='2'){
            $mtype='3';
        }elseif($dailinfos['LevelID']=='3'){
            $mtype='4';
        }
        $Trans->table('xb_mem_info')->where(array('ID'=>$dailinfos['UserID']))->save(array('Mtype'=>$mtype));

        $smsdata = array(
            'Uid'=> $dailinfos['UserID'],
            'Remarks'=> '购买代理,订单号 '.$out_trade_no.'，支付价格￥'.$dailinfos['OrderAmount'].'元',
            'Addtime'=> date('Y-m-d H:i:s'),
        );
        $Trans->table('xb_mem_sms')->add($smsdata);

        $data=array("Type"=>'1',"Amount"=>$dailinfos['OrderAmount'],"CurrentBalance"=>$mem_info['Balance'], "Description"=>'购买代理,订单号 '.$out_trade_no.'，支付价格￥'.$dailinfos['OrderAmount'],"UserID"=>$dailinfos['UserID'],"UpdateTime"=>date('Y-m-d H:i:s'),"TradeCode"=>date("YmdHis").rand(10000,99999),"Remarks"=>'订单号'.$out_trade_no);
        //三级分销 分佣
        shareOrderMoneyRecord($dailinfos['OrderAmount'],$dailinfos['UserID'],'1',$mem_info['Mobile'],'',0);
        $flag2 =  $Trans->table('xb_mem_balances')->add($data);

        if($flag2){
            $Trans->commit();
            return true;
        }else{
            $Trans->rollback();
            return false;
        }

    }else{
        $Trans->rollback();
        return false;
    }
}
//根据订单号修改征信查询记录信息
function change_zxorder_data($out_trade_no,$trade_no,$paytype='1'){
    $zxinfos=M('zenxin_list')->where(array('OrderSn'=>$out_trade_no))->find();
    $mem_info=M('mem_info')->where(array('ID'=>$zxinfos['UserID']))->find();

    $Trans = M();
    $Trans->startTrans();
    $zx_data['Status'] = '2';
    $zx_data['PayType'] = $paytype;
    $zx_data['Batch'] = $trade_no;
    $zx_data['PayTime'] = date('Y-m-d H:i:s');
    $flag1 = $Trans->table('xb_zenxin_list')->where(array('OrderSn'=>$out_trade_no))->save($zx_data);//修改订单状态
    if($flag1){
        $smsdata = array(
            'Uid'=> $zxinfos['UserID'],
            'Remarks'=> '查询征信,订单号 '.$out_trade_no.'，支付价格￥'.$zxinfos['OrderAmount'].'元',
            'Addtime'=> date('Y-m-d H:i:s'),
        );
        $Trans->table('xb_mem_sms')->add($smsdata);

        if($zxinfos['Type']==0){
            $data=array("Type"=>'1',"oid"=>$zxinfos['ID'],"Amount"=>$zxinfos['OrderAmount'],"CurrentBalance"=>$mem_info['Balance'], "Description"=>'查询征信,订单号 '.$out_trade_no.'，支付价格￥'.$zxinfos['OrderAmount'],"UserID"=>$zxinfos['UserID'],"UpdateTime"=>date('Y-m-d H:i:s'),"TradeCode"=>date("YmdHis").rand(10000,99999),"Remarks"=>'订单号'.$out_trade_no);
            $flag2 =  $Trans->table('xb_mem_balances')->add($data);
        }else{
            $flag2=1;
        }

        if($flag2){
            $Trans->commit();
            //三级分销 分佣
            if($zxinfos['Type']==1){  //贷备店征信查询方式
                shareZenxinRecord($zxinfos['OrderAmount'],$zxinfos['UserID'],'4','征信查询',$mem_info['Mobile'],$zxinfos['ID']);
            }else{   //个人中心征信查询方式
                shareOrderMoneyRecord($zxinfos['OrderAmount'],$zxinfos['UserID'],'4','征信查询',$mem_info['Mobile'],$zxinfos['ID']);
            }
            return true;
        }else{
            $Trans->rollback();
            return false;
        }

    }else{
        $Trans->rollback();
        return false;
    }
}

//xml转为数组  第三方支付
function setContent($content) {
    //$this->content = $content;
    $result = array();

    $xml = simplexml_load_string($content);
    $encode = getXmlEncode($content);

    if($xml && $xml->children()) {
        foreach ($xml->children() as $node){
            //有子节点
            if($node->children()) {
                $k = $node->getName();
                $nodeXml = $node->asXML();
                $v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);

            } else {
                $k = $node->getName();
                $v = (string)$node;
            }

            if($encode!="" && $encode != "UTF-8") {
                $k = iconv("UTF-8", $encode, $k);
                $v = iconv("UTF-8", $encode, $v);
            }

            $result[$k] = $v;
        }

    }

    return $result;
}

//获取xml编码
function getXmlEncode($xml) {
    $ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
    if($ret) {
        return strtoupper ( $arr[1] );
    } else {
        return "";
    }
}

/**
 * 获取子分类
 */
function get_cate($table,$where,$array){
    $arr=M($table)->where($where)->select();
    if($arr) {
        foreach ($arr as $key => $val) {
            if(!in_array($val['ID'],$array)){
                $array[]=$val['ID'];
            }
            $hasChild=get_cate($table, array('ParentID'=>$val['ID']),$array);
            $hasChild ? $array=$hasChild : '';
        }
        return $array;
    }else{
        return false;
    }
}

/** 获取父分类
 * @param $table
 * @param $where
 * @param $array
 * @return bool
 */
function get_cate_str($table,$where,$array){
    $arr = M($table)->where($where)->find();
    if($arr){
        array_unshift($array,array('ID'=>$arr['ID'],'ParentID'=>$arr['ParentID'],'Name'=>$arr['Name']));
        $arr['ParentID']? $array=get_cate_str($table,array('ID'=>$arr['ParentID']),$array):'';
        return $array;
    }else{
        return false;
    }
}
/** 判断会员是否有未读的通知消息
 * @param $uid 会员id
 * @return array
 */
function ishavenoread($uid){
    $where['UserID']=array('in',array(0,$uid));
    $where['Status']=array('eq','1');
    $List1=M('mem_message')->where(array($where,'Type'=>0))->order('SendTime desc')->select();//系统消息
    $List2=M('mem_message')->where(array($where,'Type'=>1))->order('SendTime desc')->select();//通知消息
    $xtmsg=false;
    $tzmsg=false;
    if($List1){
        foreach($List1 as $key=>$val){
            $exit='';
            $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$uid))->find();
            if(!$exit){
                //未读
                $xtmsg=true;break;
            }
        }
    }
    if($List2){
        foreach($List2 as $key=>$val){
            $exit='';
            $exit=M('readmessage')->where(array('MID'=>$val['ID'],'UID'=>$uid))->find();
            if(!$exit){
                //未读
                $tzmsg=true;break;
            }
        }
    }
    $rest=array(
        'xtmsg'=>$xtmsg,
        'tzmsg'=>$tzmsg,
        );
    return $rest;
}