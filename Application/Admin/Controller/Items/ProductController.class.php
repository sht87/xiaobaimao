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

namespace Admin\Controller\Items;
use XBCommon;
use Admin\Controller\System\BaseController;
class ProductController extends BaseController {

    const T_TABLE = 'items';
    const T_CATE='items_category';
    const T_CONDITIONS='items_conditions';
    const T_MONEYTYPE='items_moneytype';
    const T_JKTIEMS='items_jktimes';
    const T_NEEDS='items_needs';
    const T_ADMIN='sys_administrator';

	/**
     * 信息列表
     */
	public function index(){
	    $cateList=M(self::T_CATE)->where(array('IsDel'=>0))->order('Sort asc,ID desc')->select();
        $mtypeList=M(self::T_MONEYTYPE)->where(array('IsDel'=>0))->order('Sort asc,ID desc')->select();
        $jklist=M(self::T_JKTIEMS)->where(array('IsDel'=>0))->order('Sort asc,ID asc')->select();
        $this->assign(array(
            "cateList"=>$cateList,
            "mtypeList"=>$mtypeList,
            "jklist"=>$jklist,
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

        $CateID=I('post.CateID',-5,'intval');
        if($CateID!=-5){
            $where[]="FIND_IN_SET($CateID,CateID)";
        }
        $MoneytypeID=I('post.MoneytypeID',-5,'intval');
        if($MoneytypeID!=-5){
            $where[]="FIND_IN_SET($MoneytypeID,MoneytypeID)";
        }
        $JktimesID=I('post.JktimesID',-5,'intval');
        if($JktimesID!=-5){
            $where[]="FIND_IN_SET($JktimesID,JktimesID)";
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
        $where['Itype']=1;

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
                // $val['CateID']=$query->GetValue(self::T_CATE,array('ID'=>$val['CateID']),'Name');
                // $val['MoneytypeID']=$query->GetValue(self::T_MONEYTYPE,array('ID'=>$val['MoneytypeID']),'Name');
                // $val['JktimesID']=$query->GetValue(self::T_JKTIEMS,array('ID'=>$val['JktimesID']),'Name');
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

        //申请条件，所需材料的多选
        if($ID){
            $where=array('IsDel'=>0);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $condList=M(self::T_CONDITIONS)->where($where)->order('Sort asc,ID desc')->select();
        $needList=M(self::T_NEEDS)->where($where)->order('Sort asc,ID desc')->select();
        $cateList=M(self::T_CATE)->where($where)->order('Sort asc,ID desc')->select();
        $mtypeList=M(self::T_MONEYTYPE)->where($where)->order('Sort asc,ID desc')->select();
        $jklist=M(self::T_JKTIEMS)->where($where)->order('Sort asc,ID desc')->select();
        if($ID){
            $tabList=M(self::T_TABLE)->where(array('ID'=>$ID))->find();
            $condArr=explode(',',$tabList['ConditIDs']);
            $needArr=explode(',',$tabList['NeedIDs']);
            $cateArr=explode(',',$tabList['CateID']);
            $moneytypeArr=explode(',',$tabList['MoneytypeID']);
            $jktimesArr=explode(',',$tabList['JktimesID']);
            //出现的城市
            $showflag=false;
            if($tabList['Isshow']=='2'){
                $showflag=true;
            }
            if($tabList['Cityids']){
                $citidArr=explode(',',$tabList['Cityids']);
            }
        }

        $credit=M(self::T_TABLE)->where(array('ID'=>$ID))->find();
        //阶梯图介绍
        if($credit['StepIntro']){
            $StepIntroArr=unserialize($credit['StepIntro']);
            $this->assign(array(
                'StepIntroArr'=>$StepIntroArr,
                'end'=>count($StepIntroArr),
            ));
        }
        //下款攻略
        if($credit['Downconts']){
            $DowncontsArr=unserialize($credit['Downconts']);
            $this->assign(array(
                'DowncontsArr'=>$DowncontsArr,
                'end'=>count($DowncontsArr),
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
        $this->assign(array(
            'credit'=>$credit,
            'condition'=>$condList,
            'need'=>$needList,
            'catelist'=>$cateList,
            'mtypeList'=>$mtypeList,
            'jklist'=>$jklist,
            'condArr'=>$condArr,
            'needArr'=>$needArr,
            'cateArr'=>$cateArr,
            'moneytypeArr'=>$moneytypeArr,
            'jktimesArr'=>$jktimesArr,
            'showflag'=>$showflag,
            'citidArr'=>$citidArr,
            'Yjtype'=>$tabList['Yjtype'],
        ));
        $this->assign('ID',$ID);
        $this->display();
    }

	/**
	 * 查询详细信息
	 */
	public function loadajax()
	{
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
                array('AppNumbs','require','申请人数必须填写！'), //默认情况下用正则进行验证
                array('PassRate','require','通过率必须填写！'), //默认情况下用正则进行验证
                array('DayfeeRate','require','日费率必须填写！'), //默认情况下用正则进行验证
                array('Logurl','require','平台logo图必须上传！'), //默认情况下用正则进行验证
                array('Openurl','require','平台链接地址必须填写！'), //默认情况下用正则进行验证
                array('Sort',array(0,999),'排序的大小必须在0-999之间！',0,'between'), //默认情况下用正则进行验证
                array('Intro','require','简短介绍必须填写！'), //默认情况下用正则进行验证
                array('BaseFee','require','基本工资简介必须填写！'), //默认情况下用正则进行验证
                array('StepFee','require','阶梯工资简介必须填写！'), //默认情况下用正则进行验证
                array('Settletime','require','结算周期必须填写！'), //默认情况下用正则进行验证
				array('price','require','获客单价必须填写！')
            );
            //根据表单提交的POST数据和验证规则条件，创建数据对象
            $model=D(self::T_TABLE);
            $FormData=$model->validate($rules)->create();

            if(!$FormData){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                //校验
                if($FormData['Yjtype']=='1'){
                    //按比例
                    /*if(!$FormData['BonusRate1']){
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
                    unset($FormData['Ymoney4']);*/
                }elseif($FormData['Yjtype']=='2'){
                    //按金额
                    /*if( !is_numeric( $FormData['Ymoney1']) || $FormData['Ymoney1']<0){
                        $this->ajaxReturn(0,"普通会员佣金不能为空");
                    }
                    if(!is_numeric( $FormData['Ymoney2']) || $FormData['Ymoney2']<0){
                        $this->ajaxReturn(0,"渠道代理佣金不能为空");
                    }
                    if(!is_numeric( $FormData['Ymoney3']) || $FormData['Ymoney3']<0){
                        $this->ajaxReturn(0,"团队经理佣金不能为空");
                    }
                    if(!is_numeric( $FormData['Ymoney4']) || $FormData['Ymoney4']<0){
                        $this->ajaxReturn(0,"城市经理佣金不能为空");
                    }
                    unset($FormData['BonusRate1']);
                    unset($FormData['BonusRate2']);
                    unset($FormData['BonusRate3']);
                    unset($FormData['BonusRate4']);*/
                }
				if(!$FormData['BonusRate1']){
					$FormData['BonusRate1']=0.00;
				}
				if(!$FormData['BonusRate2']){
					$FormData['BonusRate2']=0.00;
				}
				if(!$FormData['BonusRate3']){
					$FormData['BonusRate3']=0.00;
				}
				if(!$FormData['BonusRate4']){
					$FormData['BonusRate4']=0.00;
				}
				
				if(!$FormData['Ymoney1']){
					$FormData['Ymoney1']=0.00;
				}
				if(!$FormData['Ymoney2']){
					$FormData['Ymoney2']=0.00;
				}
				if(!$FormData['Ymoney3']){
					$FormData['Ymoney3']=0.00;
				}
				if(!$FormData['Ymoney4']){
					$FormData['Ymoney4']=0.00;
				}
				

				if(!$FormData['Smoney1']){
					$FormData['Smoney1']=0.00;
				}
				if(!$FormData['Smoney2']){
					$FormData['Smoney2']=0.00;
				}
				if(!$FormData['Smoney3']){
					$FormData['Smoney3']=0.00;
				}
				if(!$FormData['Smoney4']){
					$FormData['Smoney4']=0.00;
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
                if(empty($FormData['CateID'])){
                    $this->ajaxReturn(0,"请选择借款类型");
                }
                if(empty($FormData['MoneytypeID'])){
                    $this->ajaxReturn(0,"请选择借款额度");
                }
                if(empty($FormData['JktimesID'])){
                    $this->ajaxReturn(0,"请选择借款期限");
                }

                if(empty($FormData['ConditIDs'])){
                    $this->ajaxReturn(0,"请选择申请条件");
                }
                if(empty($FormData['NeedIDs'])){
                    $this->ajaxReturn(0,"请选择所需材料");
                }
                $FormData['Openurl']=$FormData['Openurl'];
                $FormData['Itype']=1;
                $FormData['CateID']=implode(',',$FormData['CateID']);
                $FormData['MoneytypeID']=implode(',',$FormData['MoneytypeID']);
                $FormData['JktimesID']=implode(',',$FormData['JktimesID']);

                $FormData['ConditIDs']=implode(',',$FormData['ConditIDs']);
                $FormData['NeedIDs']=implode(',',$FormData['NeedIDs']);
                if($FormData['Isshow']=='2'){
                    $FormData['Cityids']=implode(',',$FormData['Cityids']);
                }else{
                    $FormData['Cityids']='';
                }
                

                //阶梯图介绍数据获取
                $StepIntroArr=I('post.StepIntro');
                foreach($StepIntroArr as $k=>$v){

                    if(mb_strlen($v)>18){
                        $this->ajaxReturn(0,"阶梯图的介绍字数不能超过18个字");
                    }
                }
                $FormData['StepIntro']=serialize($StepIntroArr);


                //下款攻略数据获取
                $Downconts=I('post.Downconts','','trim');
                $downArr=array();
                foreach($Downconts as $k=>$v){
                    if($v){
                        $downArr[]=$v;
                    }
                }
                if($downArr){
                    $FormData['Downconts']=serialize($downArr);
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

                $data=array();  //创建新数组，用于存储保存的数据
                //只更新修改的字段
                $data=$FormData;
                $data['Openurl']=htmlspecialchars_decode($data['Openurl']);
                //记录操作者信息和更新操作时间
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
                ->join('left join xb_sys_administrator d on d.ID=a.OperatorID')
                ->field('a.*,d.UserName as OperatorID')
                ->where(array('a.ID'=>$ID))
                ->find();
            $condList=M(self::T_CONDITIONS)->field('Name')->where(array('ID'=>array('in',explode(',',$Info['ConditIDs']))))->order('Sort asc,ID desc')->select();
            $needList=M(self::T_NEEDS)->field('Name')->where(array('ID'=>array('in',explode(',',$Info['NeedIDs']))))->order('Sort asc,ID desc')->select();

            $cateList=M(self::T_CATE)->field('Name')->where(array('ID'=>array('in',explode(',',$Info['CateID']))))->order('Sort asc,ID desc')->select();
            $mtypeList=M(self::T_MONEYTYPE)->field('Name')->where(array('ID'=>array('in',explode(',',$Info['MoneytypeID']))))->order('Sort asc,ID desc')->select();
            $jklist=M(self::T_JKTIEMS)->field('Name')->where(array('ID'=>array('in',explode(',',$Info['JktimesID']))))->order('Sort asc,ID desc')->select();
            //低版本php不支持array_column()
            $condNameArr=array();
            foreach($condList as $k=>$v){
                $condNameArr[]=$v['Name'];
            }
            $needNameArr=array();
            foreach($needList as $k=>$v){
                $needNameArr[]=$v['Name'];
            }
            $cateNameArr=array();
            foreach($cateList as $k=>$v){
                $cateNameArr[]=$v['Name'];
            }
            $mtypeNameArr=array();
            foreach($mtypeList as $k=>$v){
                $mtypeNameArr[]=$v['Name'];
            }
            $jkNameArr=array();
            foreach($jklist as $k=>$v){
                $jkNameArr[]=$v['Name'];
            }

            $Info['ConditName']=implode('，',$condNameArr);
            $Info['NeedName']=implode('，',$needNameArr);
            
            $Info['CateName']=implode('，',$cateNameArr);//array_column($cateList,'Name')
            $Info['TypeName']=implode('，',$mtypeNameArr);
            $Info['jktimename']=implode('，',$jkNameArr);
            if($Info['Downconts']){
                //$QuanycontsArr=explode(',',$Info['Downconts']);
                $QuanycontsArr=unserialize($Info['Downconts']);
            }
			$linkType = $Info['linkType'];
			if($linkType==1){
				$Info['link'] = '间接跳转';
			}
			if($linkType==2){
				$Info['link'] = '直接跳转';
			}
            $this->assign('Info',$Info);
            $this->assign('QuanycontsArr',$QuanycontsArr);
			$itemPriceArr = M('item_price')->where(array('itemId'=>$ID))->select();
			$this->assign('itemPriceArr',$itemPriceArr);
        }
        $this->display();
    }


    /**
     * 获取借款类型
     */
    public function getCate(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_CATE)->field('ID,Name')->where($where)->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }

    /**
     * 获取借款额度
     */
    public function getType(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_MONEYTYPE)->field('ID,Name')->where($where)->select();
        if($Sjuserid){
            foreach ($Sjuserid as $key=>$val){
                $row[$key+1]=['id'=>$val['ID'],'text'=>$val['Name']];
            }
        }
        $this->ajaxReturn($row);
    }
    /**
     * 获取借款期限
     */
    public function getJktimes(){
        $id=I('request.ID',0,'intval');
        $row[0]=['id'=>0,'text'=>'请选择'];
        if($id){
            $where=array('IsDel'=>0);
        }else{
            $where=array('IsDel'=>0,'Status'=>1);
        }
        $Sjuserid=M(self::T_JKTIEMS)->field('ID,Name')->where($where)->select();
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

	//添加阶梯价格
	public function addJtPrice($ID=null){
        $ID=(int)$ID;
		$this->assign('ID',$ID);
		$itemPriceArr = M('item_price')->where(array('itemId'=>$ID))->select();
		$this->assign('itemPriceArr',$itemPriceArr);
        $this->display();
	}

	public function itemPriceSave(){
		$id = I("post.ID", '', 'trim');
		$price = I("post.price", '', 'trim');
		$num = I("post.num", '', 'trim');
		$itemPrice = M('item_price')->where(array('itemId'=>$id,'num'=>$num))->find();
		if($itemPrice){
			$this->ajaxReturn(0, "已存在");
		}else{
			$data = array();
			$data['itemId'] = $id;
			$data['num'] = $num;
			$data['price'] = $price;
			M('item_price')->add($data);
			$this->ajaxReturn(1, "添加成功");
		}

	}

	public function ddJtPrice(){
        $id=I('request.ID',0,'intval');
		$res = M('item_price')->where(array('ID'=>$id))->delete();
        if ($res) {
            $this->ajaxReturn(true, "删除数据成功！");
        } else {
            $this->ajaxReturn(false, "删除数据时出错！");
        }
	}
}