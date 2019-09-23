<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      王少武
 * @修改日期：  2017-05-24 14:19
 * @继承版本:   1.1
 * @功能说明：  短信发送记录
 */
namespace Admin\Controller\Members;

use XBCommon;
use Admin\Controller\System\BaseController;

class SendmessageController extends BaseController {

    const T_TABLE = 'mem_message';
    const T_DICTIONARY = 'sys_dictionary';
    const T_ADMINISTAROR = 'sys_administrator';
    const T_MEMBER = 'mem_info';
    const T_MEMSMS = 'mem_sms';
    const T_MEMLEVELS='mem_levels';  //代理级别管理
    const T_MEM = 'mem_info';


    /**
	 * 列表
	 */
	public function index(){
		$this->display();
	}
    /*
    * 保存消息数据
    */
    public function Save(){
        $Contents=I('post.Contents','','trim');//消息内容
        $MemIDS=I('post.MemIDS');//会员ID
        if ($Contents==''){
            $this->ajaxReturn(0,'请填写消息内容！');
        }
        if (empty($MemIDS)){
            $this->ajaxReturn(0,'请选择发送会员！');
        }
        $token=$this->get_access_token();
        if(!$token){
            $this->ajaxReturn(0,'token获取失败!');
        }
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
        foreach ($MemIDS as $k => $v) {
            //先查询下用户的openid是否存在
            $men = M(self::T_MEM)->where("ID = $v")->find();
            if($men['ServiceOpenid']){
                $datas=array(
                    "touser"=>$men['ServiceOpenid'],
                    "msgtype"=>"text",
                    "text"=>array(
                        'content'=>$Contents,
                        )
                );
                $res=$this->curl($url,$datas);
                $res=json_decode($res,true);
                $insetdata=array(
                    'UserID'=>$v,
                    'Contents'=>$Contents,
                    'OperatorID'=>$_SESSION['AdminInfo']['AdminID'],
                    'UpdateTime'=>date('Y-m-d H:i:s'),
                    );
                if($res['errcode']=='0'){
                    //发送成功
                    $insetdata['Status']='1';
                }else{
                    //发送失s败
                    $insetdata['Status']='0';
                    $insetdata['Errmsg']=$res['errmsg'];
                    $this->ajaxReturn(0,"群发消息失败！");
                }
                M('wx_wechatmsgs')->add($insetdata);
            }else{
                $this->ajaxReturn(0,"用户opendid不存在，无法发送！");
            }
            
        }
        $this->ajaxReturn(1,"群发消息成功！");
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
     * 批量指派 获取任务列表
     */
    public function getcate(){
        $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
        $dailiArr=array();
        foreach($dailidata as $k=>$v){
            $dailiArr[$v['ID']]=$v['Name'];
        }
        $MtypeArr=array("","普通会员",$dailiArr[1],$dailiArr[2],$dailiArr[3]);
        $this->assign("dailidata",$MtypeArr);
        $this->display();
    }

    /**
     * 显示列表数据
     */
    public function DataList(){
        //接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
        $sort=I('post.sort');
        $order=I('post.order');


        if($sort && $order){
            $sort = $sort.' '.$order;
        }else{
            $sort = 'Sort asc,ID desc';
        }
        $where['IsDel'] = 0;
        
        $UserName=I('post.UserName','','trim');
        if($UserName){$where['TrueName'] = array('like','%'.$UserName.'%');}

        $Mobile=I('post.Mobile');
        if($Mobile){$where['Mobile']=array('like','%'.$Mobile.'%');}

        $Status=I('post.Status',-5,'intval');
        if($Status!=-5){$where['Status']=$Status;}

        $LoginIP=I('post.LoginIP');
        if($LoginIP){$where['LoginIP']=array('like','%'.$LoginIP.'%');}

        $IpCity=I('post.IpCity');
        if($IpCity){$where['IpCity']=array('like','%'.$IpCity.'%');}

        
        $Mtype=I('post.Mtype',-5,'intval');
        if($Mtype!=-5){$where['Mtype']=$Mtype;}

        if(empty($where)){$where = '';}

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_MEM,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
        $dailiArr=array();
        foreach($dailidata as $k=>$v){
            $dailiArr[$v['ID']]=$v['Name'];
        }
        $MtypeArr=array("","普通会员",$dailiArr[1],$dailiArr[2],$dailiArr[3]);
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $val['state'] = has_mem($val['ID']) ? 'closed' : 'open';
                $val['Mtype']=$MtypeArr[$val['Mtype']];
                if($val['IsSendwx']=='1'){
                    $val['IsSendwx']='<span style="color:green;">发送</span>';
                }elseif($val['IsSendwx']=='2'){
                    $val['IsSendwx']='<span style="color:red">不发送</span>';
                }
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }        
        $this->ajaxReturn($result);
    }
}