<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2018-04-08 09:21
 * 功能说明: 微信自定义菜单控制器
 */
 namespace Admin\Controller\Wechat;
 
 use Admin\Controller\System\BaseController;
 use XBCommon;

 class WechatmenusController extends BaseController{

     public function index(){
         $token=$this->get_access_token();
         if($token){
            //创建菜单
            // $creat_menu=$this->createmenu($token);
            // var_dump($creat_menu);exit;
            //获取菜单
            $get_menu=$this->getmenu($token);
            //$this->delmenu($token);
            if($get_menu){
                $memu=json_decode($get_menu,true);
                $this->assign('memuinfos',$memu['menu']);
            }
            
         }
         // $mem='{"menu":{"button":[{"type":"view","name":"我的首页","url":"http:\/\/jrr.ahceshi.com\/index\/index","sub_button":[]},{"name":"轻松娱乐","sub_button":[{"type":"click","name":"刮刮乐","key":"VCX_GUAHAPPY","sub_button":[]},{"type":"click","name":"转盘","key":"VCX_LUCKPAN","sub_button":[]}]},{"type":"view","name":"会员中心","url":"http:\/\/jrr.ahceshi.com\/member\/index","sub_button":[]}]}}';
         // $memu=json_decode($mem,true);
         // $this->assign('memuinfos',$memu['menu']);
         $this->display();
     }
     /**
      * 查询详细信息
      */
     public function shows(){
         $id=I('request.ID',0,'intval');
         if($id){
             $tabList=M(self::T_TABLE)->where(array('ID'=>$id))->find();
             $this->ajaxReturn($tabList);
         }else{
            $this->ajaxReturn(array('tabList'=>false,'message'=>'没有该记录'));
         }
     }

     /**
      *保存数据
      */
     public function Save(){

         if(!IS_POST){
             $this->ajaxReturn(0,'数据提交方式不对');
         }
         $data=I('post.');
         //校验
         if(!$data['name1'] && ($data['name1_chird1'] || $data['name1_chird2'])){
            $this->ajaxReturn(0,'第一列一级菜单必须要有');
         }
         if(!$data['name1'] && ($data['name1_chird3'] || $data['name1_chird4'])){
            $this->ajaxReturn(0,'第一列一级菜单必须要有');
         }
         if(!$data['name1'] && $data['name1_chird5']){
            $this->ajaxReturn(0,'第一列一级菜单必须要有');
         }

         if(!$data['name2'] && ($data['name2_chird1'] || $data['name2_chird2'])){
            $this->ajaxReturn(0,'第二列一级菜单必须要有');
         }
         if(!$data['name2'] && ($data['name2_chird3'] || $data['name2_chird4'])){
            $this->ajaxReturn(0,'第二列一级菜单必须要有');
         }
         if(!$data['name2'] && $data['name2_chird5']){
            $this->ajaxReturn(0,'第二列一级菜单必须要有');
         }

         if(!$data['name3'] && ($data['name3_chird1'] || $data['name3_chird2'])){
            $this->ajaxReturn(0,'第三列一级菜单必须要有');
         }
         if(!$data['name3'] && ($data['name3_chird3'] || $data['name3_chird4'])){
            $this->ajaxReturn(0,'第三列一级菜单必须要有');
         }
         if(!$data['name3'] && $data['name3_chird5']){
            $this->ajaxReturn(0,'第三列一级菜单必须要有');
         }
         $menuArr=array();
         //组装菜单
         if($data['name1']){
            $onedata=array();
            $onedata['name']=$data['name1'];

            if($data['name1_chird1'] && $data['linkurl1_chird1']){
                $child=array();
                $child['name']=$data['name1_chird1'];
                $child['type']='view';
                $child['url']=$data['linkurl1_chird1'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name1_chird1']){
                $child=array();
                $child['name']=$data['name1_chird1'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name1_chird2'] && $data['linkurl1_chird2']){
                $child=array();
                $child['name']=$data['name1_chird2'];
                $child['type']='view';
                $child['url']=$data['linkurl1_chird2'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name1_chird2']){
                $child=array();
                $child['name']=$data['name1_chird2'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name1_chird3'] && $data['linkurl1_chird3']){
                $child=array();
                $child['name']=$data['name1_chird3'];
                $child['type']='view';
                $child['url']=$data['linkurl1_chird3'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name1_chird3']){
                $child=array();
                $child['name']=$data['name1_chird3'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name1_chird4'] && $data['linkurl1_chird4']){
                $child=array();
                $child['name']=$data['name1_chird4'];
                $child['type']='view';
                $child['url']=$data['linkurl1_chird4'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name1_chird4']){
                $child=array();
                $child['name']=$data['name1_chird4'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name1_chird5'] && $data['linkurl1_chird5']){
                $child=array();
                $child['name']=$data['name1_chird5'];
                $child['type']='view';
                $child['url']=$data['linkurl1_chird5'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name1_chird5']){
                $child=array();
                $child['name']=$data['name1_chird5'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if(!$onedata['sub_button'][0] && $data['linkurl1']){
                $onedata['type']='view';
                $onedata['url']=$data['linkurl1'];
            }else{
                $onedata['type']='click';
                $onedata['key']='VCX_LUCKPAN';
            }
            $menuArr['button'][]=$onedata;
         }

         if($data['name2']){
            $onedata=array();
            $onedata['name']=$data['name2'];

            if($data['name2_chird1'] && $data['linkurl2_chird1']){
                $child=array();
                $child['name']=$data['name2_chird1'];
                $child['type']='view';
                $child['url']=$data['linkurl2_chird1'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name2_chird1']){
                $child=array();
                $child['name']=$data['name2_chird1'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name2_chird2'] && $data['linkurl2_chird2']){
                $child=array();
                $child['name']=$data['name2_chird2'];
                $child['type']='view';
                $child['url']=$data['linkurl2_chird2'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name2_chird2']){
                $child=array();
                $child['name']=$data['name2_chird2'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name2_chird3'] && $data['linkurl2_chird3']){
                $child=array();
                $child['name']=$data['name2_chird3'];
                $child['type']='view';
                $child['url']=$data['linkurl2_chird3'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name2_chird3']){
                $child=array();
                $child['name']=$data['name2_chird3'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name2_chird4'] && $data['linkurl2_chird4']){
                $child=array();
                $child['name']=$data['name2_chird4'];
                $child['type']='view';
                $child['url']=$data['linkurl2_chird4'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name2_chird4']){
                $child=array();
                $child['name']=$data['name2_chird4'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name2_chird5'] && $data['linkurl2_chird5']){
                $child=array();
                $child['name']=$data['name2_chird5'];
                $child['type']='view';
                $child['url']=$data['linkurl2_chird5'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name2_chird5']){
                $child=array();
                $child['name']=$data['name2_chird5'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if(!$onedata['sub_button'][0] && $data['linkurl2']){
                $onedata['type']='view';
                $onedata['url']=$data['linkurl2'];
            }else{
                $onedata['type']='click';
                $onedata['key']='VCX_LUCKPAN';
            }
            $menuArr['button'][]=$onedata;
         }

         if($data['name3']){
            $onedata=array();
            $onedata['name']=$data['name3'];

            if($data['name3_chird1'] && $data['linkurl3_chird1']){
                $child=array();
                $child['name']=$data['name3_chird1'];
                $child['type']='view';
                $child['url']=$data['linkurl3_chird1'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name3_chird1']){
                $child=array();
                $child['name']=$data['name3_chird1'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name3_chird2'] && $data['linkurl3_chird2']){
                $child=array();
                $child['name']=$data['name3_chird2'];
                $child['type']='view';
                $child['url']=$data['linkurl3_chird2'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name3_chird2']){
                $child=array();
                $child['name']=$data['name3_chird2'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name3_chird3'] && $data['linkurl3_chird3']){
                $child=array();
                $child['name']=$data['name3_chird3'];
                $child['type']='view';
                $child['url']=$data['linkurl3_chird3'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name3_chird3']){
                $child=array();
                $child['name']=$data['name3_chird3'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name3_chird4'] && $data['linkurl3_chird4']){
                $child=array();
                $child['name']=$data['name3_chird4'];
                $child['type']='view';
                $child['url']=$data['linkurl3_chird4'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name3_chird4']){
                $child=array();
                $child['name']=$data['name3_chird4'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if($data['name3_chird5'] && $data['linkurl3_chird5']){
                $child=array();
                $child['name']=$data['name3_chird5'];
                $child['type']='view';
                $child['url']=$data['linkurl3_chird5'];
                $onedata['sub_button'][]=$child;
            }elseif($data['name3_chird5']){
                $child=array();
                $child['name']=$data['name3_chird5'];
                $child['type']='click';
                $child['key']='VCX_LUCKPAN';
                $onedata['sub_button'][]=$child;
            }

            if(!$onedata['sub_button'][0] && $data['linkurl3']){
                $onedata['type']='view';
                $onedata['url']=$data['linkurl3'];
            }else{
                $onedata['type']='click';
                $onedata['key']='VCX_LUCKPAN';
            }
            $menuArr['button'][]=$onedata;
         }
         if($menuArr){
            $token=$this->get_access_token();
            $this->delmenu($token);
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
            $res=$this->curl($url,$menuArr);
            $res=json_decode($res,true);
            if($res['errcode']=='0'){
                $this->ajaxReturn(1,'自定义菜单成功!');
            }else{
                $this->ajaxReturn(0,$res['errmsg']);
            }
         }else{
            $this->ajaxReturn(0,'自定义菜单失败!');
         }
     }

     /**
     * 获取access_token
     */
    private function get_access_token(){
        $basicinfo=M('sys_basicinfo')->find();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$basicinfo['AppID']."&secret=".$basicinfo['AppSecret'];
        $data = json_decode(file_get_contents($url),true);
        if($data['access_token']){
            return $data['access_token'];
        }else{
            return false;
        }
    }
    /**
     * 创建菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    public function createmenu($access_token)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $arr = array( 
            'button' =>array(
                array(
                    'name'=>'我的首页',
                    'type'=>'view',
                    'url'=>'http://jrr.ahceshi.com/index/index',
                ),
                array(
                    'name'=>'轻松娱乐',
                    'sub_button'=>array(
                        array(
                            'name'=>'刮刮乐',
                            'type'=>'click',
                            'key'=>'VCX_GUAHAPPY',
                        ),
                        array(
                            'name'=>'转盘',
                            'type'=>'click',
                            'key'=>'VCX_LUCKPAN',
                        )
                    )
                ),
                array(
                    'name'=>'会员中心',
                    'type'=>'view',
                    'url'=>'http://jrr.ahceshi.com/member/index',
                )
            )
        );
       // var_dump(json_encode($arr));exit;
        $res=$this->curl($url,$arr);
        return $res;
    }
    private function curl($url='',$data=array()){
        if(!$url)return false;
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt ($curl,CURLOPT_TIMEOUT,60);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);

        if(!empty($data)){
          curl_setopt($curl,CURLOPT_POST,1);
         // curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
          curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data,JSON_UNESCAPED_UNICODE));
        }
        $res=curl_exec($curl);
        if(curl_errno($curl)){
          echo '访问出错：'.curl_error($curl);
        }
        return $res;
    }
    /**
     * 查询菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    
    private function getmenu($access_token)
    {
        # code...
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
        $data = file_get_contents($url);
        return $data;
    }
    /**
     * 删除菜单
     * @param $access_token 已获取的ACCESS_TOKEN
     */
    
    private function delmenu($access_token)
    {
        # code...
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
        $data = json_decode(file_get_contents($url),true);
        if ($data['errcode']==0) {
            # code...
            return true;
        }else{
            return false;
        }
    }
    //测试 客服接口,,给用户发微信消息
    public function DataList(){
        $token=$this->get_access_token();
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
        //$url='https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token='.$token;
        $datas=array(
            "touser"=>"op4rSjujVZBRLseRNkLZiGEai81o",
            "msgtype"=>"text",
            "text"=>array(
                'content'=>'你好啊落叶666',
                )
            );
        $res=$this->curl($url,$datas);
        var_dump($res);
    }

 }