<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 发送微信模板消息
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 
// +----------------------------------------------------------------------
// | Date: 2017/12/27
// +----------------------------------------------------------------------

namespace Extend;
class Message
{
    public $tpl;   //选择 的对应模板
    public $id;

    const T_TABLE = 'mem_info';
    const T_ITEMS = 'items';//商品表
    const T_APPLYLIST = 'apply_list';//申请记录表
    const T_LISTRESULT = 'apply_listresult';//放款结果表
    const T_MEMMONEY = 'mem_money';//提现充值明细表
    const T_MEMBALANCES = 'mem_balances';//金额变动明细表

    public function __construct($id,$tpl=1){
        $this->tpl   =  $tpl;
        $this->id    =  $id;
        $this->appId = C('Help.Wachet_AppId');
        $this->appSecret = C('Help.Wachet_AppSecret');;
    }
    public function sms(){
        if($this->tpl == 1){
            //当用户提交申请贷款的时候(身份验证通过),通知推荐人
            $applyinfo=M(self::T_APPLYLIST)->where(array('ID'=>$this->id))->find();
            $iteminfo=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo')->where(array('ID'=>$applyinfo['ItemID']))->find();
            $meminfo=M(self::T_TABLE)->field('ID,TrueName,Mobile,ServiceOpenid,IsSendwx,LoginClient,DeviceToken')->where(array('ID'=>$applyinfo['UserID']))->find();

            //发短信通知
           if($meminfo['Mobile']){
              sendmessage($meminfo['Mobile'],'尊敬的会员您好,您推广的产品：'.$iteminfo['Name'].'，编号:'.$iteminfo['GoodsNo'].'. '.$applyinfo['TrueName'].'提交了申请!');
           }
           //发送微信模板消息
           if($meminfo['ServiceOpenid'] && $meminfo['IsSendwx']=='1'){
              $keyword2='产品编号:'.$iteminfo['GoodsNo'];
              $content=$applyinfo['TrueName'].',手机号码:'.substr_replace($applyinfo['Mobile'],'****',3,4).'，刚刚验证身份,提交了申请!';
              $text='{
                       "touser":"'.$meminfo['ServiceOpenid'].'",
                       "template_id":"0ee6xEmvjhyLtu91LtFqADyF0WiBSrT7Fz3nYfR3ZKo",     
                       "data":{
                           "first": {"value":"您推广的产品有人申请提交成功!",  "color":"" },
                           "keyword1":{"value":"'.$iteminfo['Name'].'","color":""},
                           "keyword2":{"value":"'.$keyword2.'", "color":"" },
                           "remark":{"value":"'.$content.'","color":"" }
                       }
                    }';

              //配置信息
              $Token = new \Extend\Token($this->appId,$this->appSecret);
              $AccessToken  = $Token->getAccessToken();

              $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
              https_request($url,$text);
           }
           //发送友盟推送消息(app推送消息)
           if($meminfo['LoginClient'] && $meminfo['DeviceToken']){
               $Contents='尊敬的会员您好,您推广的产品：'.$iteminfo['Name'].'，编号:'.$iteminfo['GoodsNo'].'. '.$applyinfo['TrueName'].'提交了申请!';
               upush($Contents,$meminfo['LoginClient'],$meminfo['DeviceToken']);
           }
        }elseif($this->tpl == 2){
           //后台匹配成功,给推荐人返佣金,通知推荐人  佣金提醒
           $applyinfo=M(self::T_APPLYLIST)->where(array('ID'=>$this->id))->find();
           $iteminfo=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo')->where(array('ID'=>$applyinfo['ItemID']))->find();
           $meminfo=M(self::T_TABLE)->field('ID,TrueName,Mobile,ServiceOpenid,IsSendwx,LoginClient,DeviceToken')->where(array('ID'=>$applyinfo['UserID']))->find();
           $applyresult=M(self::T_LISTRESULT)->where(array('ListID'=>$this->id,'IsDel'=>'0'))->find();
           if($applyresult['Bonus']){
               //发短信通知
               if($meminfo['Mobile']){
                  sendmessage($meminfo['Mobile'],'尊敬的会员您好,您推广的产品：'.$iteminfo['Name'].'，编号:'.$iteminfo['GoodsNo'].'. 推广成功,并获得:'.$applyresult['Bonus'].'元佣金!');
               }
               //发送微信模板消息
               if($meminfo['ServiceOpenid'] && $meminfo['IsSendwx']=='1'){
                  $keyword2=date('Y-m-d');
                  $content='感谢您的推广与使用!';
                  $text='{
                           "touser":"'.$meminfo['ServiceOpenid'].'",
                           "template_id":"JV13We-QVvIa2MsD8FSdYw8sYZVq_sZ5bVIn8HfpzZQ",     
                           "data":{
                               "first": {"value":"您获得了一笔推广佣金!",  "color":"" },
                               "keyword1":{"value":"'.$applyresult['Bonus'].'","color":""},
                               "keyword2":{"value":"'.$keyword2.'", "color":"" },
                               "remark":{"value":"'.$content.'","color":"" }
                           }
                        }';

                  //配置信息
                  $Token = new \Extend\Token($this->appId,$this->appSecret);
                  $AccessToken  = $Token->getAccessToken();

                  $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
                  https_request($url,$text);
               }
               //发送友盟推送消息(app推送消息)
               if($meminfo['LoginClient'] && $meminfo['DeviceToken']){
                   $Contents='尊敬的会员您好,您推广的产品：'.$iteminfo['Name'].'，编号:'.$iteminfo['GoodsNo'].'. 推广成功,并获得:'.$applyresult['Bonus'].'元佣金!';
                   upush($Contents,$meminfo['LoginClient'],$meminfo['DeviceToken']);
               }
           }
        }elseif($this->tpl == 3){
           //提现成功,通知提现人
           $txinfo=M(self::T_MEMMONEY)->find($this->id);
           $meminfo=M(self::T_TABLE)->field('ID,TrueName,Mobile,ServiceOpenid,IsSendwx,Balance,LoginClient,DeviceToken')->where(array('ID'=>$txinfo['Uid']))->find();
           if($txinfo['Type']=='0' && $txinfo['IsAduit']=='2'){
               //发短信通知
               if($meminfo['Mobile']){
                  sendmessage($meminfo['Mobile'],'尊敬的会员您好,您的提现金额:'.$txinfo['Money'].'元，已经审核成功!');
               }
               //发送微信模板消息
               if($meminfo['ServiceOpenid'] && $meminfo['IsSendwx']=='1'){
                  $keyword2=date('Y-m-d');
                  $content='如有疑问,请联系平台客服,谢谢!';
                  $text='{
                           "touser":"'.$meminfo['ServiceOpenid'].'",
                           "template_id":"6J8upt_8jX3AObBO0ZOb_ul7dbG1M6UAfdIB9ZWwCZA",     
                           "data":{
                               "first": {"value":"您提现申请审核成功!",  "color":"" },
                               "keyword1":{"value":"'.$txinfo['AddTime'].'","color":""},
                               "keyword2":{"value":"提现", "color":"" },
                               "keyword3":{"value":"'.$txinfo['Money'].'", "color":"" },
                               "keyword4":{"value":"'.$meminfo['Balance'].'", "color":"" },
                               "remark":{"value":"'.$content.'","color":"" }
                           }
                        }';

                  //配置信息
                  $Token = new \Extend\Token($this->appId,$this->appSecret);
                  $AccessToken  = $Token->getAccessToken();

                  $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
                  https_request($url,$text);
               }
               //发送友盟推送消息(app推送消息)
               if($meminfo['LoginClient'] && $meminfo['DeviceToken']){
                   $Contents='尊敬的会员您好,您的提现金额:'.$txinfo['Money'].'元，已经审核成功!';
                   upush($Contents,$meminfo['LoginClient'],$meminfo['DeviceToken']);
               }

           }
        }elseif($this->tpl == 4){
           //得佣金提醒:查征信,购买代理
           $balanceinfo=M(self::T_MEMBALANCES)->find($this->id);
           $meminfo=M(self::T_TABLE)->field('ID,TrueName,Mobile,ServiceOpenid,IsSendwx,Balance,LoginClient,DeviceToken')->where(array('ID'=>$balanceinfo['UserID']))->find();
           if($balanceinfo['Type']=='0' && in_array($balanceinfo['SruType'],array('1','4'))){
               //发送微信模板消息
               if($meminfo['ServiceOpenid'] && $meminfo['IsSendwx']=='1'){
                  $keyword2=date('Y-m-d');
                  $content='感谢您的推广与使用!';
                  $text='{
                           "touser":"'.$meminfo['ServiceOpenid'].'",
                           "template_id":"JV13We-QVvIa2MsD8FSdYw8sYZVq_sZ5bVIn8HfpzZQ",     
                           "data":{
                               "first": {"value":"您获得了一笔推广佣金!",  "color":"" },
                               "keyword1":{"value":"'.$balanceinfo['Amount'].'","color":""},
                               "keyword2":{"value":"'.$balanceinfo['UpdateTime'].'", "color":"" },
                               "remark":{"value":"'.$content.'","color":"" }
                           }
                        }';

                  //配置信息
                  $Token = new \Extend\Token($this->appId,$this->appSecret);
                  $AccessToken  = $Token->getAccessToken();

                  $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
                  https_request($url,$text);
               }
               //发送友盟推送消息(app推送消息)
               if($meminfo['LoginClient'] && $meminfo['DeviceToken']){
                   $Contents='尊敬的会员您好,您获得了一笔推广佣金:'.$balanceinfo['Amount'].'元，感谢您的推广与使用!';
                   upush($Contents,$meminfo['LoginClient'],$meminfo['DeviceToken']);
               }
               
           }
        }

    }
}