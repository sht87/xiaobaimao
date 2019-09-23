<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 优惠券模块
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 王少武
// +----------------------------------------------------------------------
// | Date: 2017/9/9
// +---------------------------------------------------------------------- 

namespace Admin\Controller\Credit;
use XBCommon;
use Admin\Controller\System\BaseController;
class ItemsController extends BaseController {

    const T_TABLE = 'items';
    const T_BANK='credit_bank';
    const T_BITYPE='credit_bitype';
    const T_KATYPE='credit_katype';
    const T_SUBJECT='credit_subject';
    const T_YEARFEE='credit_yearfee';
    const T_ADMIN='sys_administrator';
    const T_APPLY='apply_list';

	/**
     * 信息列表
     */
	public function index(){
	    $cateList=M(self::T_BANK)->where(array('IsDel'=>0))->order('Sort asc,ID desc')->select();
        $mtypeList=M(self::T_KATYPE)->where(array('IsDel'=>0))->order('Sort asc,ID desc')->select();
        $this->assign(array(
            "cateList"=>$cateList,
            "mtypeList"=>$mtypeList,
        ));
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
        
        if($sort && $order){
            $sort = $sort.' '.$order;
        }else{
            $sort = 'Sort asc,ID desc';
        }

        $Title=I('post.Title','','trim');
        if($Title){$where['Name'] = array('like','%'.$Title.'%');}

        $BankID=I('post.BankID',-5,'intval');
        if($BankID!=-5){
            $where['BankID']=$BankID;
        }
        $KatypeID=I('post.KatypeID',-5,'intval');
        if($KatypeID!=-5){
            $where['KatypeID']=$KatypeID;
        }
        $Showtype=I('post.Showtype',-5,'intval');
        if($Showtype!=-5){
            $where['Showtype']=$Showtype;
        }
        $Isshow=I('post.Isshow',-5,'intval');
        if($Isshow!=-5){
            $where['Isshow']=$Isshow;
        }
        $Yjtype=I('post.Yjtype',-5,'intval');
        if($Yjtype!=-5){
            $where['Yjtype']=$Yjtype;
        }
        $Status=I('post.Status',-5,'intval');
        if($Status!=-5){
            $where['Status']=$Status;
        }
        $where['IsDel']=0;
        $where['Itype']=2;

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                if($val['Showtype']=='1'){
                    $val['Showtype']='找借贷';
                }elseif($val['Showtype']=='2'){
                    $val['Showtype']='贷款分销';
                }elseif($val['Showtype']=='3'){
                    $val['Showtype']='全部出现';
                }
                if($val['Isshow']=='1'){
                    $val['Isshow']='全国';
                }elseif($val['Isshow']=='2'){
                    $val['Isshow']='部分城市';
                }
                if($val['Status']=='1'){
                    $val['Status']='<span style="color:green;">启用</span>';
                }elseif($val['Status']=='0'){
                    $val['Status']='<span style="color:red;">禁用</span>';
                }
                $val['BankID']=$query->GetValue(self::T_BANK,array('ID'=>$val['BankID']),'BankName');
                $val['SubjectID']=$query->GetValue(self::T_SUBJECT,array('ID'=>$val['SubjectID']),'Name');
                $val['KatypeID']=$query->GetValue(self::T_KATYPE,array('ID'=>$val['KatypeID']),'Name');
                $val['BitypeID']=$query->GetValue(self::T_BITYPE,array('ID'=>$val['BitypeID']),'Name');
                $val['YearfeeID']=$query->GetValue(self::T_YEARFEE,array('ID'=>$val['YearfeeID']),'Name');
                $val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>$val['OperatorID']),'UserName');
                if($val['Yjtype']=='1'){
                    //按比例
                    $val['Yjtype']='按比例';
                    $val['BonusRate1']=$val['BonusRate1']."%";
                    $val['BonusRate2']=$val['BonusRate2']."%";
                    $val['BonusRate3']=$val['BonusRate3']."%";
                    $val['BonusRate4']=$val['BonusRate4']."%";
                    $val['Ymoney1']='';
                    $val['Ymoney2']='';
                    $val['Ymoney3']='';
                    $val['Ymoney4']='';
                }elseif($val['Yjtype']=='2'){
                    //按金额
                    $val['Yjtype']='按金额';
                    $val['Ymoney1']=$val['Ymoney1'].'元';
                    $val['Ymoney2']=$val['Ymoney2'].'元';
                    $val['Ymoney3']=$val['Ymoney3'].'元';
                    $val['Ymoney4']=$val['Ymoney4'].'元';
                    $val['BonusRate1']='';
                    $val['BonusRate2']='';
                    $val['BonusRate3']='';
                    $val['BonusRate4']='';
                }
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }

        $this->ajaxReturn($result);
    }

	/**
     * 编辑功能
	*/
    public function Edit($ID=null){
        $ID=(int)$ID;
        $credit=M(self::T_TABLE)->where(array('ID'=>$ID))->find();

        //阶梯图介绍
        if($credit['StepIntro']){
            $StepIntroArr=unserialize($credit['StepIntro']);
            $this->assign(array(
                'StepIntroArr'=>$StepIntroArr,
                'end'=>count($StepIntroArr),
            ));
        }
        //权益说明
        if($credit['Quanyconts']){
            $QuanycontsArr=unserialize($credit['Quanyconts']);
            $this->assign(array(
                'QuanycontsArr'=>$QuanycontsArr,
                'end'=>count($QuanycontsArr),
            ));
        }
        //参与方式
        if($credit['PartType']){
            $PartTypeArr=unserialize($credit['PartType']);
            $this->assign(array(
                'PartTypeArr'=>$PartTypeArr,
                'end'=>count($PartTypeArr),
            ));
        }
        //工资介绍
        if($credit['FeeIntro']){
            $FeeIntroArr=unserialize($credit['FeeIntro']);
            $this->assign(array(
                'FeeIntroArr'=>$FeeIntroArr,
                'end'=>count($FeeIntroArr),
            ));
        }
        //结算方式
        if($credit['AccountType']){
            $AccountTypeArr=unserialize($credit['AccountType']);
            $this->assign(array(
                'AccountTypeArr'=>$AccountTypeArr,
                'end'=>count($AccountTypeArr),
            ));
        }
        //出现的城市
        $showflag=false;
        if($credit['Isshow']=='2'){
            $showflag=true;
        }
        if($credit['Cityids']){
            $citidArr=explode(',',$credit['Cityids']);
        }
        $this->assign(array(
            'credit'=>$credit,
            'ID'=>$ID,
            'showflag'=>$showflag,
            'citidArr'=>$citidArr,
            'Yjtype'=>$credit['Yjtype'],
            ));
        $this->display();
    }

	/**
	 * 查询详细信息
	 */
	public function loadajax(){
		$id = I("request.ID", 0, 'intval');
		if ($id) {
            $model = M(self::T_TABLE);
            $result = $model->find($id);
            if(!$result==null){
                //对隐秘数据进行特殊化处理，防止泄露
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
                array('Name','require','平台名称必须填写！'), //默认情况下用正则进行验证
                array('BankID',0,'所属银行必须选择！',0,'notequal'), //默认情况下用正则进行验证
                array('SubjectID',0,'卡片主题必须选择！',0,'notequal'), //默认情况下用正则进行验证
                array('KatypeID',0,'信用卡类型必须选择！',0,'notequal'), //默认情况下用正则进行验证
                array('YearfeeID',0,'年费用必须选择！',0,'notequal'), //默认情况下用正则进行验证
                array('BitypeID',0,'币种必须选择！',0,'notequal'), //默认情况下用正则进行验证
                array('AppNumbs','require','申请人数必须填写！'), //默认情况下用正则进行验证
                array('PassRate','require','通过率必须填写！'), //默认情况下用正则进行验证
                array('Jifen1','require','RMB积分说明必须填写！'), //默认情况下用正则进行验证
                array('Jifen2','require','USD积分说明必须填写！'), //默认情况下用正则进行验证
                array('Freetime','require','免息期必须填写！'), //默认情况下用正则进行验证
                array('Freedesc','require','免息期说明必须填写！'), //默认情况下用正则进行验证
                array('Logurl','require','平台logo图必须上传！'), //默认情况下用正则进行验证
                array('MainPic','require','主题图必须上传！'), //默认情况下用正则进行验证
                array('Fakaurl','require','发卡组织logo图必须上传！'), //默认情况下用正则进行验证
                array('Openurl','require','平台链接地址必须填写！'), //默认情况下用正则进行验证
                array('Sort',array(0,999),'排序的大小必须在0-999之间！',0,'between'), //默认情况下用正则进行验证
                array('Intro','require','简短介绍必须填写！'), //默认情况下用正则进行验证
                array('BaseFee','require','基本工资简介必须填写！'), //默认情况下用正则进行验证
                array('StepFee','require','阶梯工资简介必须填写！'), //默认情况下用正则进行验证
                array('Yeardesc','require','年费说明必须填写！'), //默认情况下用正则进行验证
                array('Settletime','require','结算周期必须填写！'), //默认情况下用正则进行验证
				array('price','require','获客价格必须填写！'), //默认情况下用正则进行验证
            );

            //根据表单提交的POST数据和验证规则条件，创建数据对象
            $model=D(self::T_TABLE);
            $FormData=$model->validate($rules)->create();
            if(!$FormData){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                //所属银行操作判断
                $exit_BankID=M(self::T_BANK)->where(array('ID'=>$FormData['BankID'],'IsDel'=>0))->find();
                if(!$exit_BankID){
                    $this->ajaxReturn(0,"操作有误，请重新选择所属银行");
                }
                if($exit_BankID['Status']!=1){
                    $this->ajaxReturn(0,"该银行已被禁用");
                }

                //卡片主题操作判断
                $exit_subjectID=M(self::T_SUBJECT)->where(array('ID'=>$FormData['SubjectID'],'IsDel'=>0))->find();
                if(!$exit_subjectID){
                    $this->ajaxReturn(0,"操作有误，请重新选择卡片主题");
                }
                if($exit_subjectID['Status']!=1){
                    $this->ajaxReturn(0,"该卡片主题已被禁用");
                }

                //信用卡类型操作判断
                $exit_KatypeID=M(self::T_KATYPE)->where(array('ID'=>$FormData['KatypeID'],'IsDel'=>0))->find();
                if(!$exit_KatypeID){
                    $this->ajaxReturn(0,"操作有误，请重新选择信用卡类型");
                }
                if($exit_KatypeID['Status']!=1){
                    $this->ajaxReturn(0,"该信用卡类型已被禁用");
                }

                //年费用操作判断
                $exit_YearfeeID=M(self::T_YEARFEE)->where(array('ID'=>$FormData['YearfeeID'],'IsDel'=>0))->find();
                if(!$exit_YearfeeID){
                    $this->ajaxReturn(0,"操作有误，请重新选择年费用");
                }
                if($exit_YearfeeID['Status']!=1){
                    $this->ajaxReturn(0,"该类年费用已被禁用");
                }

                //币种操作判断
                $exit_BitypeID=M(self::T_BITYPE)->where(array('ID'=>$FormData['BitypeID'],'IsDel'=>0))->find();
                if(!$exit_BitypeID){
                    $this->ajaxReturn(0,"操作有误，请重新选择币种");
                }
                if($exit_BitypeID['Status']!=1){
                    $this->ajaxReturn(0,"该币种已被禁用");
                }

                //校验
                if($FormData['Yjtype']=='1'){
                    //按比例
                    if(!$FormData['BonusRate1']){
                        $this->ajaxReturn(0,"普通会员返点不能为空");
                    }
                    if(!$FormData['BonusRate2']){
                        $this->ajaxReturn(0,"渠道代理返点不能为空");
                    }
                    if(!$FormData['BonusRate3']){
                        $this->ajaxReturn(0,"团队经理返点不能为空");
                    }
                    if(!$FormData['BonusRate4']){
                        $this->ajaxReturn(0,"城市经理返点不能为空");
                    }

                    //代理返点的判断
                    if($FormData['BonusRate1']>=$FormData['BonusRate2'] || $FormData['BonusRate1']>=$FormData['BonusRate3'] || $FormData['BonusRate1']>=$FormData['BonusRate4']){
                        $this->ajaxReturn(0,"普通会员返点一定小于其他代理的返点");
                    }
                    if($FormData['BonusRate2']>=$FormData['BonusRate3'] || $FormData['BonusRate2']>=$FormData['BonusRate4']){
                        $this->ajaxReturn(0,"渠道代理返点一定小于团队经理及城市经理的返点");
                    }
                    if($FormData['BonusRate3']>=$FormData['BonusRate4']){
                        $this->ajaxReturn(0,"团队经理返点一定小于城市经理的返点");
                    }
                    unset($FormData['Ymoney1']);
                    unset($FormData['Ymoney2']);
                    unset($FormData['Ymoney3']);
                    unset($FormData['Ymoney4']);
                }elseif($FormData['Yjtype']=='2'){
                    //按金额
                    if(!$FormData['Ymoney1']){
                        $this->ajaxReturn(0,"普通会员佣金不能为空");
                    }
                    if(!$FormData['Ymoney2']){
                        $this->ajaxReturn(0,"渠道代理佣金不能为空");
                    }
                    if(!$FormData['Ymoney3']){
                        $this->ajaxReturn(0,"团队经理佣金不能为空");
                    }
                    if(!$FormData['Ymoney4']){
                        $this->ajaxReturn(0,"城市经理佣金不能为空");
                    }
                    unset($FormData['BonusRate1']);
                    unset($FormData['BonusRate2']);
                    unset($FormData['BonusRate3']);
                    unset($FormData['BonusRate4']);
                }

                if(!$FormData['StepBase']){
                    $FormData['StepBase']=0.00;
                }
                if(!$FormData['StepInc1']){
                    $FormData['StepInc1']=0.00;
                }
                if(!$FormData['StepInc2']){
                    $FormData['StepInc2']=0.00;
                }

                //阶梯图介绍数据获取
                $StepIntroArr=I('post.StepIntro');
                foreach($StepIntroArr as $k=>$v){
                    if(mb_strlen($v)>18){
                        $this->ajaxReturn(0,"阶梯图的介绍字数不能超过18个字");
                    }
                }
                $FormData['StepIntro']=serialize($StepIntroArr);

                //权益说明数据获取
                $Quanyconts=I('post.Quanyconts','','trim');
                $FormData['Itype']=2;
                if(count($Quanyconts)>1){
                    for($i=0;$i<count($Quanyconts);$i++){
                        if(empty($Quanyconts[$i])){
                            $this->ajaxReturn(0,"多条权益说明，不能有未填的权益说明");
                        }
                    }
                }

                //参与方式数据获取
                $PartType=I('post.PartType');
                $PartTypeArr=array();
                foreach($PartType as $k=>$v){
                    if($v){
                        $PartTypeArr[]=$v;
                    }
                }
                if($PartTypeArr){
                    $FormData['PartType']=serialize($PartTypeArr);
                }

                //工资介绍数据获取
                $FeeIntro=I('post.FeeIntro');
                $FeeIntroArr=array();
                foreach($FeeIntro as $k=>$v){
                    if($v){
                        $FeeIntroArr[]=$v;
                    }
                }
                if($FeeIntroArr){
                    $FormData['FeeIntro']=serialize($FeeIntroArr);
                }
                //结算方式数据获取
                $AccountType=I('post.AccountType');
                $AccountTypeArr=array();
                foreach($AccountType as $k=>$v){
                    if($v){
                        $AccountTypeArr[]=$v;
                    }
                }
                if($AccountTypeArr){
                    $FormData['AccountType']=serialize($AccountTypeArr);
                }


                $Quanyconts=I('post.Quanyconts','','trim');
                $downArr=array();
                foreach($Quanyconts as $k=>$v){
                    if($v){
                        $downArr[]=$v;
                    }
                }
                if($downArr){
                    $FormData['Quanyconts']=serialize($downArr);
                }
                if($FormData['Isshow']=='2'){
                    $FormData['Cityids']=implode(',',$FormData['Cityids']);
                }else{
                    $FormData['Cityids']='';
                }

                $data=array();  //创建新数组，用于存储保存的数据
                //只更新修改的字段
                $data=$FormData;
                //记录操作者信息和更新操作时间
                $data['Openurl']=htmlspecialchars_decode($data['Openurl']);
                $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                $data['UpdateTime']=date("Y-m-d H:i:s");

                //更新数据判断
                if($FormData['ID']>0) {
                    $res=$model->where(array('ID'=>$FormData['ID']))->save($data);
                    if($res>0){
                        $this->ajaxReturn(1, '修改成功');
                    }else{
                        $this->ajaxReturn(0, '修改失败');
                    }
                }else{
                    $res=$model->add($data);
                    if($res>0){
                        $this->ajaxReturn(1, '添加成功');
                    }else{
                        $this->ajaxReturn(0, '添加失败');
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
	    $ID=I('request.ID');
        if(!empty($ID)){
            $Info=M(self::T_TABLE)->alias('a')
                ->join('left join xb_credit_bank b on b.ID=a.BankID')
                ->join('left join xb_credit_bitype c on c.ID=a.BitypeID')
                ->join('left join xb_credit_katype d on d.ID=a.KatypeID')
                ->join('left join xb_credit_yearfee e on e.ID=a.YearfeeID')
                ->join('left join xb_sys_administrator f on f.ID=a.OperatorID')
                ->field('a.*,b.BankName,c.Name as BitypeName,d.Name as KatypeName,e.Name as FeeName,f.UserName as OperatorID')
                ->where(array('a.ID'=>$ID))
                ->find();
            if($Info['Quanyconts']){
                //$QuanycontsArr=explode(',',$Info['Quanyconts']);
                $QuanycontsArr=unserialize($Info['Quanyconts']);
            }
            $this->assign('Info',$Info);
            $this->assign('QuanycontsArr',$QuanycontsArr);
        }
        $this->display();
    }


    /**
     * 获取所属银行
     */
    public function getCate(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_BANK)->field('ID,BankName')->where($where)->order('Sort asc,ID desc')->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['BankName']];
            }
        }
        $this->ajaxReturn($row);
    }

    /**
     * 获取卡片主题
     */
    public function getSubjects(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0,);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_SUBJECT)->field('ID,Name')->where($where)->order('Sort asc,ID desc')->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }
    /**
     * 获取卡类型
     */
    public function getType(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0,);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_KATYPE)->field('ID,Name')->where($where)->order('Sort asc,ID desc')->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }
    /**
     * 获取年费类型
     */
    public function getFee(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0,);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_YEARFEE)->field('ID,Name')->where($where)->order('Sort asc,ID desc')->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }
    /**
     * 获取卡类型
     */
    public function getBitype(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0,);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_BITYPE)->field('ID,Name')->where($where)->order('Sort asc,ID desc')->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }
     /**
     * @功能说明：获取所有区域信息
     * @return [type]                           [description]
     */
    public function getarea()
    {
        $cache=new XBCommon\CacheData();
        $arr1=$cache->Areas();
        //遍历处理源数据库数据
        if ($arr1) {
            foreach ($arr1 as $val) {
                $province['text'] = $val['Name'];
                $province['id'] = $val['ID'];
                $province['state']=$val['state'];
                $arr_city = array();
                foreach ($val['children'] as $city) {
                    $citys['text'] = $city['Name'];
                    $citys['id'] = $city['ID'];
                    $citys['state']=$city['state'];
                    $arr_country = array();
                    foreach ($city['children'] as $country) {
                        $countrys['text'] = $country['Name'];
                        $countrys['id'] = $country['ID'];
                        $countrys['state']=$country['state'];
                        $arr_country[] = $countrys;
                    }
                    $citys['children'] = $arr_country;
                    $arr_city[] = $citys;
                }
                $province['children'] = $arr_city;
                $arr_one[] = $province;
            }
        }
        echo json_encode($arr_one);
    }
    
}