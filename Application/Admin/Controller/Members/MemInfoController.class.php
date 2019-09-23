<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 公司电话：0532-58770968
 * 作    者：吴益超
 * 修改时间：2017-06-15 10:40
 * 功能说明：会员信息模块
 */
namespace Admin\Controller\Members;
use XBCommon;
use Admin\Controller\System\BaseController;
class MemInfoController extends BaseController {

    const T_TABLE = 'mem_info';
    const T_BALANCES='mem_balances';  //余额变更明细表
    const T_INTEGRALS='mem_integrals';  //积分变更明细表
    const T_MEMLEVELS='mem_levels';  //代理级别管理

	/**
     * 会员信息列表
     */
	public function index(){
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
	 * 后台用户管理的列表数据获取
	 * @access   public
	 * @return   object    返回json数据
	 */
    public function DataList(){
        //接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
        $sort=I('post.sort');
        $order=I('post.order');
        
        $id = I("request.id",0,'intval');

        if($sort && $order){
            $sort = $sort.' '.$order;
        }else{
            $sort = 'Sort asc,ID desc';
        }
        $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
        $dailiArr=array();
        foreach($dailidata as $k=>$v){
            $dailiArr[$v['ID']]=$v['Name'];
        }
        $MtypeArr=array("","普通会员",$dailiArr[1],$dailiArr[2],$dailiArr[3]);

        $where['IsDel'] = 0;
        if($id){
            $where['Referee'] = $id;
            $result = M(self::T_TABLE)->where($where)->order($sort)->select();
            if($result){
                foreach ($result as $k=>$val){
                    $result[$k]['state'] = has_mem($val['ID']) ? 'closed' : 'open';
                    $result[$k]['Mtype']=$MtypeArr[$val['Mtype']];
                }    
            }
        }else{

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

            $PhoneType = I('post.PhoneType', -5, 'intval');
            if ($PhoneType != -5){
                switch ($PhoneType){
                    case '1':
                        $where['PhoneType'] = 'android';
                        break;
                    case '2':
                        $where['PhoneType'] = 'ios';
                        break;
                    case '3':
                        $where['PhoneType'] = 'other';
                        break;
                }
            }

            if(!$UserName && !$Mobile  && $Status==-5 && !$LoginIP && !$IpCity && $PhoneType==-5){
                $where['Referee'] = 0;  //顶级代理
            }

            $Mtype=I('post.Mtype',-5,'intval');
            if($Mtype!=-5){$where['Mtype']=$Mtype;}

            if(empty($where)){$where = '';}

            //查询的列名
            $col='';
            //获取最原始的数据列表
            $query=new XBCommon\XBQuery();
            $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

            //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
            $result=array();
            if($array['rows']){
                foreach ($array['rows'] as $val){
                    if($val['IsSendwx']=='1'){
                        $val['IsSendwx']='<span style="color:green;">发送</span>';
                    }elseif($val['IsSendwx']=='2'){
                        $val['IsSendwx']='<span style="color:red">不发送</span>';
                    }
                    $val['state'] = has_mem($val['ID']) ? 'closed' : 'open';
                    $val['Mtype']=$MtypeArr[$val['Mtype']];

                    $result['rows'][]=$val;
                }
                $result['total']=$array['total'];
            }
        }

        $this->ajaxReturn($result);
    }

	/**
     * 编辑功能
	*/


    public function Edit($ID=null){
        $ID=(int)$ID;
        $this->assign('ID',$ID);

        $where=array(
            'Status'=>1,
            'IsDel'=>0
        );
        $this->display();
    }

	/**
	 * 查询详细信息
	 */
	public function Shows()
	{
		$id = I("request.ID", 0, 'intval');
		if ($id) {
            $model = M(self::T_TABLE);
            $result = $model->find($id);
            if(!$result==null){

                $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
                $dailiArr=array();
                foreach($dailidata as $k=>$v){
                    $dailiArr[$v['ID']]=$v['Name'];
                }
                //对隐秘数据进行特殊化处理，防止泄露
                $result['Password']='******';
                switch ($result['Mtype']){
                    case 1:$result['Memtype']="普通会员";break;
                    case 2:$result['Memtype']=$dailiArr[1];break;
                    case 3:$result['Memtype']=$dailiArr[2];break;
                    case 4:$result['Memtype']=$dailiArr[3];break;
                }
                $this->ajaxReturn($result);
            }else{
                //没有查询到内容
                $this->ajaxReturn(array('result'=>false,'message'=>'不存在的记录！'));
            }
		}
	}

	/**
	 *保存数据
	 */
    public function Save(){
        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('TrueName','require','会员姓名必须填写！'), //默认情况下用正则进行验证
            );

            //根据表单提交的POST数据和验证规则条件，创建数据对象
            $model=D(self::T_TABLE);
            $FormData=$model->validate($rules)->create();
            if(!$FormData){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                $data=array();  //创建新数组，用于存储保存的数据
                //更新数据判断
                if($FormData['ID']>0)
                {
                    //只更新修改的字段
                    $data['TrueName']=$FormData['TrueName'];
//                    $data['TrueName']=$FormData['TrueName'];
//                    $data['Sex']=$FormData['Sex'];
//                    $data['BorthDate']=$FormData['BorthDate'];
                    $data['IsSendwx']=$FormData['IsSendwx'];
                    $data['Status']=$FormData['Status'];
                    $data['Sort']=$FormData['Sort'];
                    
                    //记录操作者信息和更新操作时间
                    $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                    $data['UpdateTime']=date("Y-m-d H:i:s");

                    $res=$model->where(array('ID'=>$FormData['ID']))->save($data);
                    if($res>0){
                        $this->ajaxReturn(1, '修改成功');
                    }else{
                        $this->ajaxReturn(0, '修改失败');
                    }
                }
            }
        }else{
            $this->ajaxReturn(0, '数据提交方式不对');
        }
    }


	/**
	 * 数据删除处理 单条或多条  逻辑删除
	 */
	public function Del()
	{
		$mod = D(self::T_TABLE);
		//获取删除数据id (单条或数组)
		$ids = I("post.ID", '', 'trim');


        $result = $mod->where(array('Referee'=>$ids,'IsDel'=>'0'))->find();
		if ($result) {
            $this->ajaxReturn(0, "该会员有下级，不能删除");
        }
		
        $where['ID']=array('in',$ids);
        
        $res=$mod->where($where)->setField('IsDel',1);

        if ($res) {
            $this->ajaxReturn(true, "用户删除数据成功！");
        } else {
            $this->ajaxReturn(false, "用户删除数据时出错！");
        }
	}

	/**
     * 查看详情
     */
	public function Detail(){
	    $ID=I('get.ID');
        if(!empty($ID)){
            $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
            $dailiArr=array();
            foreach($dailidata as $k=>$v){
                $dailiArr[$v['ID']]=$v['Name'];
            }
            $Info=M(self::T_TABLE)->where(array('ID'=>$ID))->find();
            $this->assign(array(
                'Info'=>$Info,
                'dailiArr'=>$dailiArr,
                ));
        }
        $this->display();
    }

    /**
     *资金明细
     */
    public function BalanceDetail(){
        $UID=I('get.UID');
        if(!empty($UID)){
            //接收POST信息,拼接查询条件
            $page=I('post.page',1,'intval');
            $rows=I('post.rows',20,'intval');
            $sort='UpdateTime Desc';

            $where['UserID']=$UID;
            //查询的列名
            $col='';
            //获取最原始的数据列表
            $query=new XBCommon\XBQuery();
            $array=$query->GetDataList(self::T_BALANCES,$where,$page,$rows,$sort,$col);

            //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
            $result=array();
            if($array['rows']){
                foreach ($array['rows'] as $val){
                    if($val['Type']==0){
                        $val['Type']="收入";
                        switch ($val['SruType']){
                            case 1:$val['SruType']="推荐会员";
                                switch ($val['Mtype']){
                                    case 0:$val['Mtype']="";
                                        break;
                                    case 1:$val['Mtype']="推荐一级会员";
                                        break;
                                    case 2:$val['Mtype']="发展二级会员";
                                        break;
                                }
                                break;
                            case 2:$val['SruType']="借网贷";
                                break;
                            case 3:$val['SruType']="办信用卡";
                                break;
                            case 4:$val['SruType']="查征信";
                                break;
                            case 5:$val['SruType']="资金退还";
                                break;
                        }
                    }
                    if($val['Type']==1){
                        $val['Type']="支出";
                        $val['SruType']="提现";
                        $val['Mtype']="";
                    }
                    $val['Amount']=number_format($val['Amount'],2).'元';
                    $val['CurrentBalance']=number_format($val['CurrentBalance'],2).'元';
                    $result['rows'][]=$val;
                }
                $result['total']=$array['total'];
            }
            $this->ajaxReturn($result);
        }
    }

    /**
     * 积分明细
     */
    public function IntegralDetail(){
        $UID=I('get.UID');
        if(!empty($UID)){
            //接收POST信息,拼接查询条件
            $page=I('post.page',1,'intval');
            $rows=I('post.rows',20,'intval');
            $sort='UpdateTime Desc';

            $where['UserID']=$UID;
            //查询的列名
            $col='';
            //获取最原始的数据列表
            $query=new XBCommon\XBQuery();
            $array=$query->GetDataList(self::T_INTEGRALS,$where,$page,$rows,$sort,$col);

            //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
            $result=array();
            if($array['rows']){
                foreach ($array['rows'] as $val){
                    $result['rows'][]=$val;
                }
                $result['total']=$array['total'];
            }
            $this->ajaxReturn($result);
        }
    }
    //发送微信消息
    public function send($ID=null){
        $ID=(int)$ID;
        $meminfos=M(self::T_TABLE)->field('ID,NickName,UserName,Mobile,TrueName,ServiceOpenid')->find($ID);
        $this->assign(array(
            'ID'=>$ID,
            'meminfos'=>$meminfos,
            ));
        $this->display();
    }
    //发送微信消息
    public function sendwxmsg(){
        $ID=I('post.ID','');
        $Contents=I('post.Contents','');
        if(!$Contents){
            $this->ajaxReturn(0,'消息内容不能为空!');
        }
        $openid=M(self::T_TABLE)->where(array('ID'=>$ID))->getField('ServiceOpenid');
        if(!$openid){
            $this->ajaxReturn(0,'消息发送失败!');
        }
        $token=$this->get_access_token();
        if(!$token){
            $this->ajaxReturn(0,'token获取失败!');
        }
        //
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
        $datas=array(
            "touser"=>$openid,
            "msgtype"=>"text",
            "text"=>array(
                'content'=>$Contents,
                )
            );
        $res=$this->curl($url,$datas);
        $res=json_decode($res,true);
        $insetdata=array(
            'UserID'=>$ID,
            'Contents'=>$Contents,
            'OperatorID'=>$_SESSION['AdminInfo']['AdminID'],
            'UpdateTime'=>date('Y-m-d H:i:s'),
            );
        if($res['errcode']=='0'){
            //发送成功
            $insetdata['Status']='1';
        }else{
            //发送失败
            $insetdata['Status']='0';
            $insetdata['Errmsg']=$res['errmsg'];
        }
        M('wx_wechatmsgs')->add($insetdata);

        if($res['errcode']=='0'){
            $this->ajaxReturn(1,'发送成功!');
        }else{
            $this->ajaxReturn(0,'发送失败!');
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

	public function exportexcel(){
         //接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=100000;
        $sort=I('post.sort');
        $order=I('post.order');
        
        $id = I("request.id",0,'intval');

        if($sort && $order){
            $sort = $sort.' '.$order;
        }else{
            $sort = 'Sort asc,ID desc';
        }
        $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
        $dailiArr=array();
        foreach($dailidata as $k=>$v){
            $dailiArr[$v['ID']]=$v['Name'];
        }
        $MtypeArr=array("","普通会员",$dailiArr[1],$dailiArr[2],$dailiArr[3]);

        $where['IsDel'] = 0;
        if($id){
            $where['Referee'] = $id;
            $result = M(self::T_TABLE)->where($where)->order($sort)->select();
            if($result){
                foreach ($result as $k=>$val){
                    $result[$k]['state'] = has_mem($val['ID']) ? 'closed' : 'open';
                    $result[$k]['Mtype']=$MtypeArr[$val['Mtype']];
                }    
            }
        }else{
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

            $PhoneType = I('post.PhoneType', -5, 'intval');
            if ($PhoneType != -5){
                switch ($PhoneType){
                    case '1':
                        $where['PhoneType'] = 'android';
                        break;
                    case '2':
                        $where['PhoneType'] = 'ios';
                        break;
                    case '3':
                        $where['PhoneType'] = 'other';
                        break;
                }
            }

            if(!$UserName && !$Mobile  && $Status==-5 && !$LoginIP && !$IpCity && $PhoneType==-5){
                $where['Referee'] = 0;  //顶级代理
            }

			//添加时间
			$StartTime=I('post.StartTime');  //按时间查询
			$EndTime=I('post.EndTime');
			$ToStartTime=$StartTime;
			$ToEndTime=$EndTime;
			if($StartTime!=null){
				 if($EndTime!=null){
					 //有开始时间和结束时间
					 $where['RegTime']=array('between',$ToStartTime.','.$ToEndTime);
				 }else{
					 //只有开始时间
					 $where['RegTime']=array('egt',$ToStartTime);
				 }
			}else{
				 //只有结束时间
				 if($EndTime!=null){
					 $where['RegTime']=array('elt',$ToEndTime);
				 }
			}

            $Mtype=I('post.Mtype',-5,'intval');
            if($Mtype!=-5){$where['Mtype']=$Mtype;}

            if(empty($where)){$where = '';}

            //查询的列名
            $col='';
            //获取最原始的数据列表
            $query=new XBCommon\XBQuery();
            $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

            //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
            $result=array();
            if($array['rows']){
                foreach ($array['rows'] as $val){
                    if($val['IsSendwx']=='1'){
                        $val['IsSendwx']='<span style="color:green;">发送</span>';
                    }elseif($val['IsSendwx']=='2'){
                        $val['IsSendwx']='<span style="color:red">不发送</span>';
                    }
                    $val['state'] = has_mem($val['ID']) ? 'closed' : 'open';
                    $val['Mtype']=$MtypeArr[$val['Mtype']];
					$channelName = M('tg_admin')->where(array('ID'=>$val['TgadminID']))->getField('Name');
					$val['channelName'] = $channelName;
                    $result['rows'][]=$val;
                }
            }
        }

        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>真实姓名</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>会员类型</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>手机号码</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>推广渠道</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>注册时间</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>最后登录时间</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>手机型号</td>
            </tr>';

        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.$row['TrueName'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['Mtype'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['Mobile'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['channelName'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['RegTime'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['LoginTime'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['PhoneType'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'userlist';
        $html = iconv('UTF-8', 'GB2312//IGNORE',$html);
        header("Content-type: application/vnd.ms-excel; charset=GBK");
        header("Content-Disposition: attachment; filename=$str_filename.xls");
        echo $html;
        exit;
    }


}