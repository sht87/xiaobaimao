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

namespace Admin\Controller\Statistics;
use XBCommon;
use Think\Controller;
use Think\Log;
use Admin\Controller\System\BaseController;
class ChannelController extends Controller {

    const T_TABLE = 'channel_statistics';

	/**
     * 信息列表
     */
	public function index(){
		$tgName = M('tg_admin')->query("select * from xb_tg_admin where Status = 1 and IsDel = 0");
		$this->assign('tgName',$tgName);
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
		if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='createDate desc';
         }
        //添加时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=$EndTime;//date('Y-m-d',strtotime($EndTime."+1 day"));
        if($StartTime!=null){
             if($EndTime!=null){
                 //有开始时间和结束时间
                 $where['createDate']=array('between',$ToStartTime.','.$ToEndTime);
             }else{
                 //只有开始时间
                 $where['createDate']=array('egt',$ToStartTime);
             }
        }else{
             //只有结束时间
             if($EndTime!=null){
                 $where['createDate']=array('elt',$ToEndTime);
             }
        }
		$Title = I('post.Title','全部');
		Log::record($Title,'WARN',true);
		if(!is_null($Title)&&$Title!='全部'){
			$where['channelName']=$Title;
		}else{
			$sqlvvv = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 order by UpdateTime desc";
			$query=new XBCommon\XBQuery();
			$channelNames=$query->getBaseList('tg_admin',$sqlvvv);
			$ff = array();
			for($f=0;$f<count($channelNames);$f++){
				array_unshift($ff,$channelNames[$f]['channelName']);
			}
			$where['channelName']=array('in',$ff);
		}
		$result=array();
		if(!$ToStartTime&&!$ToEndTime){
			//查询的列名
			$query=new XBCommon\XBQuery();
			$sql = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 order by UpdateTime desc";
			if(!is_null($Title)&&$Title!='全部'){
				$sql = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 and Name = '".$Title."' order by UpdateTime desc";
			}
			$nameArr=$query->getBaseList('tg_admin',$sql);

			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNum'] = 0;
			$topRow['aliveNum'] = 0;
			$topRow['newNum'] = 0;
			$topRow['applyNum'] = 0;
			$topRow['SumMoney'] = 0;
			for($i=0;$i<count($nameArr);$i++){
				$name = $nameArr[$i]['channelName'];
				$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,sum(aliveNum) aliveNum,sum(applyNum) applyNum,sum(newNum) newNum,sum(SumMoney) SumMoney from xb_channel_statistics where channelName = '".$name."'";
				$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
				$val = $sumRow[0];
				$val['channelName'] = $name;
				if(is_null($val['PV'])){
					$val['PV'] = 0;
				}
				if(is_null($val['UV'])){
					$val['UV'] = 0;
				}
				if(is_null($val['registerNum'])){
					$val['registerNum'] = 0;
				}
				if(is_null($val['aliveNum'])){
					$val['aliveNum'] = 0;
				}
				if(is_null($val['applyNum'])){
					$val['applyNum'] = 0;
				}
				if(is_null($val['newNum'])){
					$val['newNum'] = 0;
				}
				if(is_null($val['SumMoney'])){
					$val['SumMoney'] = 0;
				}
				$topRow['PV'] = $topRow['PV'] + $val['PV'];
				$topRow['UV'] = $topRow['UV'] + $val['UV'];
				$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
				$topRow['aliveNum'] = $topRow['aliveNum'] + $val['aliveNum'];
				$topRow['newNum'] = $topRow['newNum'] + $val['newNum'];
				$topRow['applyNum'] = $topRow['applyNum'] + $val['applyNum'];
				$topRow['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];

				$val['PVUV'] = 0;
				if($val['registerNum']!=0&&$val['applyNum']!=0){
					$val['registernewNum'] = round($val['newNum']/$val['registerNum'],2);
				}
				if($val['registerNum']!=0&&$val['applyNum']!=0){
					$val['registerapplyNum'] = round($val['applyNum']/$val['registerNum'],2);
				}
				$val['registerNumUV'] = 0;
				$val['registerNumCPAUV'] = 0;
				if($val['UV']!=0){
					$val['registerNumUV']=round($val['registerNum']/$val['UV'],2);
					$val['registerNumCPAUV']=round($val['registerNum']*$val['CPA']/$val['UV'],2);
				}
				
				$val['registerNumPV'] = 0;
				if($val['PV']!=0){
					$val['PVUV']= round($val['UV']/$val['PV'],2);
					$val['registerNumPV']=round($val['registerNum']/$val['PV'],2);
				}
				
				$val['priceregisterNum']= $val['SumMoney'];
				
				if($val['registerNum']>0){
					$val['aliveNumregisterNum']= round($val['aliveNum']/$val['registerNum'],2);
				}else{
					$val['aliveNumregisterNum']=0;
				}
				$val['prUV'] = 0;
				if($val['newNum']>0){
					$val['prUV']=round($val['priceregisterNum']/$val['newNum'],2);
				}
				$val['prAll'] = 0;
				if($val['applyNum']>0){
					$val['prAll']=round($val['priceregisterNum']/$val['applyNum'],2);
				}
				$result['rows'][]=$val;
			}

			$topRow['PVUV'] = 0;
			$topRow['registerNumUV'] = 0;
			$topRow['registerNumCPAUV'] = 0;
			if($topRow['UV']!=0){
				$topRow['registerNumUV']=round($topRow['registerNum']/$topRow['UV'],2);
				$topRow['registerNumCPAUV']=round($topRow['registerNum']*$topRow['CPA']/$topRow['UV'],2);
			}
			
			$topRow['registerNumPV'] = 0;
			if($topRow['PV']!=0){
				$topRow['PVUV']= round($topRow['UV']/$topRow['PV'],2);
				$topRow['registerNumPV']=round($topRow['registerNum']/$topRow['PV'],2);
			}
			
			if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
				$topRow['registernewNum'] = round($topRow['registerNum']/$topRow['newNum'],2);
			}
			if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
				$topRow['registerapplyNum'] = round($topRow['registerNum']/$topRow['applyNum'],2);
			}

			$topRow['priceregisterNum']= $topRow['SumMoney'];
			
			if($topRow['registerNum']>0){
				$topRow['aliveNumregisterNum']= round($topRow['aliveNum']/$topRow['registerNum'],2);
			}else{
				$topRow['aliveNumregisterNum']=0;
			}
			$topRow['prUV'] = 0;
			if($topRow['newNum']>0){
				$topRow['prUV']=round($topRow['priceregisterNum']/$topRow['newNum'],2);
			}
			$topRow['prAll'] = 0;
			if($topRow['applyNum']>0){
				$topRow['prAll']=round($topRow['priceregisterNum']/$topRow['applyNum'],2);
			}
			$topRow['channelName'] = "###（合计）";

			$zr = array();
			$zr['PV'] = 0;
			$zr['UV'] = 0;
			$zr['registerNum'] = 0;
			$zr['aliveNum'] = 0;
			$zr['newNum'] = 0;
			$zr['applyNum'] = 0;
			$zr['SumMoney'] = 0;
			$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,sum(aliveNum) aliveNum,sum(applyNum) applyNum,sum(newNum) newNum,sum(SumMoney) SumMoney from xb_channel_statistics where channelName = '自然流量'";
			$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
			$val = $sumRow[0];
			if(!is_null($val['PV'])){
				$zr['PV'] = $val['PV'];
			}
			if(!is_null($val['UV'])){
				$zr['UV'] = $val['UV'];
			}
			if(!is_null($val['registerNum'])){
				$zr['registerNum'] = $val['registerNum'];
			}
			if(!is_null($val['aliveNum'])){
				$zr['aliveNum'] = $val['aliveNum'];
			}
			$zr['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];
			$zr['PVUV'] = 0;
			
			$zr['registerNumUV'] = 0;
			$zr['registerNumCPAUV'] = 0;
			if($zr['UV']!=0){
				$zr['registerNumUV']=round($zr['registerNum']/$zr['UV'],2);
				$zr['registerNumCPAUV']=round($zr['registerNum']*$zr['CPA']/$zr['UV'],2);
			}
			
			$zr['registerNumPV'] = 0;
			if($zr['PV']!=0){
				$zr['PVUV']= round($zr['UV']/$zr['PV'],2);
				$zr['registerNumPV']=round($zr['registerNum']/$zr['PV'],2);
			}
			
			$zr['priceregisterNum']= $zr['SumMoney'];
			
			if($zr['registerNum']>0){
				$zr['aliveNumregisterNum']= round($zr['aliveNum']/$zr['registerNum'],2);
			}else{
				$zr['aliveNumregisterNum']=0;
			}
			$zr['prUV'] = 0;
			if($zr['newNum']>0){
				$zr['prUV']=round($zr['priceregisterNum']/$zr['newNum'],2);
			}
			$zr['prAll'] = 0;
			if($zr['applyNum']>0){
				$zr['prAll']=round($zr['priceregisterNum']/$zr['applyNum'],2);
			}
			if($zr['registerNum']!=0&&$zr['newNum']!=0){
				$zr['registernewNum'] = $zr['newNum']/$zr['registerNum'];
				if($zr['registernewNum']!=0){
					$zr['registernewNum'] = round($zr['registernewNum'],2);
				}
			}
			if($zr['registerNum']!=0&&$zr['applyNum']!=0){
				$zr['registerapplyNum'] = $zr['applyNum']/$zr['registerNum'];
				$zr['registerapplyNum'] = round($zr['registerapplyNum'],2);
			}
			$zr['channelName'] = "自然流量";
			array_unshift($result['rows'],$zr);
			array_unshift($result['rows'],$topRow);
			$result['total']=count($nameArr);
		}else{
			//查询的列名
			$col='';
			//获取最原始的数据列表
			$query=new XBCommon\XBQuery();
			
			$array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

			//如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
			if($array['rows']){
	
				$topRow = array();
				$topRow['PV'] = 0;
				$topRow['UV'] = 0;
				$topRow['registerNum'] = 0;
				$topRow['aliveNum'] = 0;
				$topRow['newNum'] = 0;
				$topRow['applyNum'] = 0;
				$topRow['SumMoney'] = 0;
				foreach ($array['rows'] as $val){
					if(is_null($val['PV'])){
						$val['PV'] = 0;
					}
					if(is_null($val['UV'])){
						$val['UV'] = 0;
					}
					if(is_null($val['registerNum'])){
						$val['registerNum'] = 0;
					}
					if(is_null($val['aliveNum'])){
						$val['aliveNum'] = 0;
					}
					if(is_null($val['applyNum'])){
						$val['applyNum'] = 0;
					}
					if(is_null($val['newNum'])){
						$val['newNum'] = 0;
					}
					if(is_null($val['SumMoney'])){
						$val['SumMoney'] = 0;
					}
					$topRow['PV'] = $topRow['PV'] + $val['PV'];
					$topRow['UV'] = $topRow['UV'] + $val['UV'];
					$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
					$topRow['aliveNum'] = $topRow['aliveNum'] + $val['aliveNum'];
					$topRow['newNum'] = $topRow['newNum'] + $val['newNum'];
					$topRow['applyNum'] = $topRow['applyNum'] + $val['applyNum'];
					$topRow['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];
					//按金额
					$val['PVUV'] = 0;
					$val['registerNumUV'] = 0;
					$val['registerNumCPAUV'] = 0;
					if($val['UV']!=0){
						
						$val['registerNumUV']=round($val['registerNum']/$val['UV'],2);
						$val['registerNumCPAUV']=round($val['registerNum']*$val['CPA']/$val['UV'],2);
					}
					$val['registerNumPV'] = 0;
					if($val['PV']!=0){
						$val['PVUV']= round($val['UV']/$val['PV'],2);
						$val['registerNumPV']=round($val['registerNum']/$val['PV'],2);
					}
					
					$val['priceregisterNum']= $val['SumMoney'];
					
					if($val['registerNum']>0){
						$val['aliveNumregisterNum']= round($val['aliveNum']/$val['registerNum'],2);
					}else{
						$val['aliveNumregisterNum']=0;
					}
					$val['prUV'] = 0;
					if($val['newNum']>0){
						$val['prUV']=round($val['priceregisterNum']/$val['newNum'],2);
					}
					$val['prAll'] = 0;
					if($val['applyNum']>0){
						$val['prAll']=round($val['priceregisterNum']/$val['applyNum'],2);
					}
					if($val['registerNum']!=0&&$val['applyNum']!=0){
						$val['registernewNum'] = round($val['newNum']/$val['registerNum'],2);
					}
					if($val['registerNum']!=0&&$val['applyNum']!=0){
						$val['registerapplyNum'] = round($val['applyNum']/$val['registerNum'],2);
					}
					
					$result['rows'][]=$val;
				}
				$topRow['PVUV'] = 0;
				$topRow['registerNumUV'] = 0;
				$topRow['registerNumCPAUV'] = 0;
				if($topRow['UV']!=0){
					$topRow['registerNumUV']=round($topRow['registerNum']/$topRow['UV'],2);
					$topRow['registerNumCPAUV']=round($topRow['registerNum']*$topRow['CPA']/$topRow['UV'],2);
				}
				
				$topRow['registerNumPV'] = 0;
				if($topRow['PV']!=0){
					$topRow['PVUV']= round($topRow['UV']/$topRow['PV'],2);
					$topRow['registerNumPV']=round($topRow['registerNum']/$topRow['PV'],2);
				}
				
				$topRow['priceregisterNum']= $topRow['SumMoney'];
				
				if($topRow['registerNum']>0){
					$topRow['aliveNumregisterNum']= round($topRow['aliveNum']/$topRow['registerNum'],2);
				}else{
					$topRow['aliveNumregisterNum']=0;
				}
				$topRow['prUV'] = 0;
				if($topRow['newNum']>0){
					$topRow['prUV']=round($topRow['priceregisterNum']/$topRow['newNum'],2);
				}
				$topRow['prAll'] = 0;
				if($topRow['applyNum']>0){
					$topRow['prAll']=round($topRow['priceregisterNum']/$topRow['applyNum'],2);
				}
				if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
					$topRow['registernewNum'] = round($topRow['newNum']/$topRow['registerNum'],2);
				}
				if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
					$topRow['registerapplyNum'] = round($topRow['applyNum']/$topRow['registerNum'],2);
				}
				$topRow['channelName'] = "###（合计）";
				array_unshift($result['rows'],$topRow);
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
		$tabList=M(self::T_TABLE)->where(array('ID'=>$ID))->find();
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
            //根据表单提交的POST数据和验证规则条件，创建数据对象
            $model=M(self::T_TABLE);
            //工资介绍数据获取
			$registerNum=I('post.registerNum');
			$ID = I('post.ID');
			//更新数据判断
			if($ID>0) {
				$data['registerNum'] = $registerNum;
				$res=$model->where(array('ID'=>$ID))->save($data);
				if($res>0){
					$this->ajaxReturn(1, '修改成功');
				}else{
					$this->ajaxReturn(0, '修改失败');
				}
			}else{
				$this->ajaxReturn(0, '修改失败');
			}
        }else{
            $this->ajaxReturn(0, '数据提交方式不对');
        }
    }

	//会员导出功能
    public function exportexcel(){
         $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
		$sort=I('post.sort');
		$order=I('post.order');
		if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='createDate desc';
         }
        //添加时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=$EndTime;//date('Y-m-d',strtotime($EndTime."+1 day"));
        if($StartTime!=null){
             if($EndTime!=null){
                 //有开始时间和结束时间
                 $where['createDate']=array('between',$ToStartTime.','.$ToEndTime);
             }else{
                 //只有开始时间
                 $where['createDate']=array('egt',$ToStartTime);
             }
        }else{
             //只有结束时间
             if($EndTime!=null){
                 $where['createDate']=array('elt',$ToEndTime);
             }
        }
		$Title = I('post.Title','全部');
		if(!is_null($Title)&&$Title!='全部'){
			$where['channelName']=$Title;
		}else{
			$sqlvvv = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 order by UpdateTime desc";
			$query=new XBCommon\XBQuery();
			$channelNames=$query->getBaseList('tg_admin',$sqlvvv);
			$ff = array();
			for($f=0;$f<count($channelNames);$f++){
				array_unshift($ff,$channelNames[$f]['channelName']);
			}
			$where['channelName']=array('in',$ff);
		}
		$result=array();
		if(!$ToStartTime&&!$ToEndTime){
			//查询的列名
			$query=new XBCommon\XBQuery();
			$sql = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 order by UpdateTime desc";
			if(!is_null($Title)&&$Title!='全部'){
				$sql = "select Name as channelName from xb_tg_admin where Status = 1 and IsDel = 0 and Name = '".$Title."' order by UpdateTime desc";
			}
			$nameArr=$query->getBaseList('tg_admin',$sql);

			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNum'] = 0;
			$topRow['aliveNum'] = 0;
			$topRow['newNum'] = 0;
			$topRow['applyNum'] = 0;
			$topRow['SumMoney'] = 0;
			for($i=0;$i<count($nameArr);$i++){
				$name = $nameArr[$i]['channelName'];
				$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,sum(aliveNum) aliveNum,sum(applyNum) applyNum,sum(newNum) newNum,sum(SumMoney) SumMoney from xb_channel_statistics where channelName = '".$name."'";
				$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
				$val = $sumRow[0];
				$val['channelName'] = $name;
				if(is_null($val['PV'])){
					$val['PV'] = 0;
				}
				if(is_null($val['UV'])){
					$val['UV'] = 0;
				}
				if(is_null($val['registerNum'])){
					$val['registerNum'] = 0;
				}
				if(is_null($val['aliveNum'])){
					$val['aliveNum'] = 0;
				}
				if(is_null($val['applyNum'])){
					$val['applyNum'] = 0;
				}
				if(is_null($val['newNum'])){
					$val['newNum'] = 0;
				}
				if(is_null($val['SumMoney'])){
					$val['SumMoney'] = 0;
				}
				$topRow['PV'] = $topRow['PV'] + $val['PV'];
				$topRow['UV'] = $topRow['UV'] + $val['UV'];
				$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
				$topRow['aliveNum'] = $topRow['aliveNum'] + $val['aliveNum'];
				$topRow['newNum'] = $topRow['newNum'] + $val['newNum'];
				$topRow['applyNum'] = $topRow['applyNum'] + $val['applyNum'];
				$topRow['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];

				$val['PVUV'] = 0;
				if($val['registerNum']!=0&&$val['applyNum']!=0){
					$val['registernewNum'] = round($val['newNum']/$val['registerNum'],2);
				}
				if($val['registerNum']!=0&&$val['applyNum']!=0){
					$val['registerapplyNum'] = round($val['applyNum']/$val['registerNum'],2);
				}
				$val['registerNumUV'] = 0;
				$val['registerNumCPAUV'] = 0;
				if($val['UV']!=0){
					$val['registerNumUV']=round($val['registerNum']/$val['UV'],2);
					$val['registerNumCPAUV']=round($val['registerNum']*$val['CPA']/$val['UV'],2);
				}
				
				$val['registerNumPV'] = 0;
				if($val['PV']!=0){
					$val['PVUV']= round($val['UV']/$val['PV'],2);
					$val['registerNumPV']=round($val['registerNum']/$val['PV'],2);
				}
				
				$val['priceregisterNum']= $val['SumMoney'];
				
				if($val['registerNum']>0){
					$val['aliveNumregisterNum']= round($val['aliveNum']/$val['registerNum'],2);
				}else{
					$val['aliveNumregisterNum']=0;
				}
				$val['prUV'] = 0;
				if($val['newNum']>0){
					$val['prUV']=round($val['priceregisterNum']/$val['newNum'],2);
				}
				$val['prAll'] = 0;
				if($val['applyNum']>0){
					$val['prAll']=round($val['priceregisterNum']/$val['applyNum'],2);
				}
				$result['rows'][]=$val;
			}

			$topRow['PVUV'] = 0;
			$topRow['registerNumUV'] = 0;
			$topRow['registerNumCPAUV'] = 0;
			if($topRow['UV']!=0){
				$topRow['registerNumUV']=round($topRow['registerNum']/$topRow['UV'],2);
				$topRow['registerNumCPAUV']=round($topRow['registerNum']*$topRow['CPA']/$topRow['UV'],2);
			}
			
			$topRow['registerNumPV'] = 0;
			if($topRow['PV']!=0){
				$topRow['PVUV']= round($topRow['UV']/$topRow['PV'],2);
				$topRow['registerNumPV']=round($topRow['registerNum']/$topRow['PV'],2);
			}
			
			if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
				$topRow['registernewNum'] = round($topRow['registerNum']/$topRow['newNum'],2);
			}
			if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
				$topRow['registerapplyNum'] = round($topRow['registerNum']/$topRow['applyNum'],2);
			}

			$topRow['priceregisterNum']= $topRow['SumMoney'];
			
			if($topRow['registerNum']>0){
				$topRow['aliveNumregisterNum']= round($topRow['aliveNum']/$topRow['registerNum'],2);
			}else{
				$topRow['aliveNumregisterNum']=0;
			}
			$topRow['prUV'] = 0;
			if($topRow['newNum']>0){
				$topRow['prUV']=round($topRow['priceregisterNum']/$topRow['newNum'],2);
			}
			$topRow['prAll'] = 0;
			if($topRow['applyNum']>0){
				$topRow['prAll']=round($topRow['priceregisterNum']/$topRow['applyNum'],2);
			}
			$topRow['channelName'] = "###（合计）";

			$zr = array();
			$zr['PV'] = 0;
			$zr['UV'] = 0;
			$zr['registerNum'] = 0;
			$zr['aliveNum'] = 0;
			$zr['newNum'] = 0;
			$zr['applyNum'] = 0;
			$zr['SumMoney'] = 0;
			$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,sum(aliveNum) aliveNum,sum(applyNum) applyNum,sum(newNum) newNum,sum(SumMoney) SumMoney from xb_channel_statistics where channelName = '自然流量'";
			$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
			$val = $sumRow[0];
			if(!is_null($val['PV'])){
				$zr['PV'] = $val['PV'];
			}
			if(!is_null($val['UV'])){
				$zr['UV'] = $val['UV'];
			}
			if(!is_null($val['registerNum'])){
				$zr['registerNum'] = $val['registerNum'];
			}
			if(!is_null($val['aliveNum'])){
				$zr['aliveNum'] = $val['aliveNum'];
			}
			$zr['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];
			$zr['PVUV'] = 0;
			
			$zr['registerNumUV'] = 0;
			$zr['registerNumCPAUV'] = 0;
			if($zr['UV']!=0){
				$zr['registerNumUV']=round($zr['registerNum']/$zr['UV'],2);
				$zr['registerNumCPAUV']=round($zr['registerNum']*$zr['CPA']/$zr['UV'],2);
			}
			
			$zr['registerNumPV'] = 0;
			if($zr['PV']!=0){
				$zr['PVUV']= round($zr['UV']/$zr['PV'],2);
				$zr['registerNumPV']=round($zr['registerNum']/$zr['PV'],2);
			}
			
			$zr['priceregisterNum']= $zr['SumMoney'];
			
			if($zr['registerNum']>0){
				$zr['aliveNumregisterNum']= round($zr['aliveNum']/$zr['registerNum'],2);
			}else{
				$zr['aliveNumregisterNum']=0;
			}
			$zr['prUV'] = 0;
			if($zr['newNum']>0){
				$zr['prUV']=round($zr['priceregisterNum']/$zr['newNum'],2);
			}
			$zr['prAll'] = 0;
			if($zr['applyNum']>0){
				$zr['prAll']=round($zr['priceregisterNum']/$zr['applyNum'],2);
			}
			if($zr['registerNum']!=0&&$zr['newNum']!=0){
				$zr['registernewNum'] = $zr['newNum']/$zr['registerNum'];
				if($zr['registernewNum']!=0){
					$zr['registernewNum'] = round($zr['registernewNum'],2);
				}
			}
			if($zr['registerNum']!=0&&$zr['applyNum']!=0){
				$zr['registerapplyNum'] = $zr['applyNum']/$zr['registerNum'];
				$zr['registerapplyNum'] = round($zr['registerapplyNum'],2);
			}
			$zr['channelName'] = "自然流量";
			array_unshift($result['rows'],$zr);
			array_unshift($result['rows'],$topRow);
			$result['total']=count($nameArr);
		}else{
			//查询的列名
			$col='';
			//获取最原始的数据列表
			$query=new XBCommon\XBQuery();
			
			$array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

			//如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
			if($array['rows']){
	
				$topRow = array();
				$topRow['PV'] = 0;
				$topRow['UV'] = 0;
				$topRow['registerNum'] = 0;
				$topRow['aliveNum'] = 0;
				$topRow['newNum'] = 0;
				$topRow['applyNum'] = 0;
				$topRow['SumMoney'] = 0;
				foreach ($array['rows'] as $val){
					if(is_null($val['PV'])){
						$val['PV'] = 0;
					}
					if(is_null($val['UV'])){
						$val['UV'] = 0;
					}
					if(is_null($val['registerNum'])){
						$val['registerNum'] = 0;
					}
					if(is_null($val['aliveNum'])){
						$val['aliveNum'] = 0;
					}
					if(is_null($val['applyNum'])){
						$val['applyNum'] = 0;
					}
					if(is_null($val['newNum'])){
						$val['newNum'] = 0;
					}
					if(is_null($val['SumMoney'])){
						$val['SumMoney'] = 0;
					}
					$topRow['PV'] = $topRow['PV'] + $val['PV'];
					$topRow['UV'] = $topRow['UV'] + $val['UV'];
					$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
					$topRow['aliveNum'] = $topRow['aliveNum'] + $val['aliveNum'];
					$topRow['newNum'] = $topRow['newNum'] + $val['newNum'];
					$topRow['applyNum'] = $topRow['applyNum'] + $val['applyNum'];
					$topRow['SumMoney'] = $topRow['SumMoney'] + $val['SumMoney'];
					//按金额
					$val['PVUV'] = 0;
					$val['registerNumUV'] = 0;
					$val['registerNumCPAUV'] = 0;
					if($val['UV']!=0){
						
						$val['registerNumUV']=round($val['registerNum']/$val['UV'],2);
						$val['registerNumCPAUV']=round($val['registerNum']*$val['CPA']/$val['UV'],2);
					}
					$val['registerNumPV'] = 0;
					if($val['PV']!=0){
						$val['PVUV']= round($val['UV']/$val['PV'],2);
						$val['registerNumPV']=round($val['registerNum']/$val['PV'],2);
					}
					
					$val['priceregisterNum']= $val['SumMoney'];
					
					if($val['registerNum']>0){
						$val['aliveNumregisterNum']= round($val['aliveNum']/$val['registerNum'],2);
					}else{
						$val['aliveNumregisterNum']=0;
					}
					$val['prUV'] = 0;
					if($val['newNum']>0){
						$val['prUV']=round($val['priceregisterNum']/$val['newNum'],2);
					}
					$val['prAll'] = 0;
					if($val['applyNum']>0){
						$val['prAll']=round($val['priceregisterNum']/$val['applyNum'],2);
					}
					if($val['registerNum']!=0&&$val['applyNum']!=0){
						$val['registernewNum'] = round($val['newNum']/$val['registerNum'],2);
					}
					if($val['registerNum']!=0&&$val['applyNum']!=0){
						$val['registerapplyNum'] = round($val['applyNum']/$val['registerNum'],2);
					}
					
					$result['rows'][]=$val;
				}
				$topRow['PVUV'] = 0;
				$topRow['registerNumUV'] = 0;
				$topRow['registerNumCPAUV'] = 0;
				if($topRow['UV']!=0){
					$topRow['registerNumUV']=round($topRow['registerNum']/$topRow['UV'],2);
					$topRow['registerNumCPAUV']=round($topRow['registerNum']*$topRow['CPA']/$topRow['UV'],2);
				}
				
				$topRow['registerNumPV'] = 0;
				if($topRow['PV']!=0){
					$topRow['PVUV']= round($topRow['UV']/$topRow['PV'],2);
					$topRow['registerNumPV']=round($topRow['registerNum']/$topRow['PV'],2);
				}
				
				$topRow['priceregisterNum']= $topRow['SumMoney'];
				
				if($topRow['registerNum']>0){
					$topRow['aliveNumregisterNum']= round($topRow['aliveNum']/$topRow['registerNum'],2);
				}else{
					$topRow['aliveNumregisterNum']=0;
				}
				$topRow['prUV'] = 0;
				if($topRow['newNum']>0){
					$topRow['prUV']=round($topRow['priceregisterNum']/$topRow['newNum'],2);
				}
				$topRow['prAll'] = 0;
				if($topRow['applyNum']>0){
					$topRow['prAll']=round($topRow['priceregisterNum']/$topRow['applyNum'],2);
				}
				if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
					$topRow['registernewNum'] = round($topRow['newNum']/$topRow['registerNum'],2);
				}
				if($topRow['registerNum']!=0&&$topRow['applyNum']!=0){
					$topRow['registerapplyNum'] = round($topRow['applyNum']/$topRow['registerNum'],2);
				}
				$topRow['channelName'] = "###（合计）";
				array_unshift($result['rows'],$topRow);
				$result['total']=$array['total'];
			}
		}
        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>渠道名称</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>浏览数</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>申请数</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>申请转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>注册数</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>浏览转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>用户转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>激活数</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>激活率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>结算费用</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>申请数新</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>申请数总</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>申请转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>总申请转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>UV新单价</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>UV总单价</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>日期</td>
            </tr>';
        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.$row['channelName'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['PV'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['UV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['PVUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNumPV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNumUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['aliveNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['aliveNumregisterNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['priceregisterNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['newNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['applyNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registernewNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerapplyNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['prUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['prAll'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['createDate'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'channel';
        $html = iconv('UTF-8', 'GB2312//IGNORE',$html);
        header("Content-type: application/vnd.ms-excel; charset=GBK");
        header("Content-Disposition: attachment; filename=$str_filename.xls");
        echo $html;
        exit;
    }
     /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
     public function arraySequence($array, $field, $sort = 'SORT_DESC'){
         $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
     }
}