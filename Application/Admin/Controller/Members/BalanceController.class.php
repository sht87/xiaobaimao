<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 公司电话：0532-58770968
 * 作    者：刁洪强
 * 修改时间：2017-06-15 17:40
 * 功能说明：资金明细
 */
namespace Admin\Controller\Members;
use XBCommon;
use Admin\Controller\System\BaseController;
class BalanceController extends BaseController {

    const T_TABLE = 'mem_balances';  //资金明细表
    const T_MEM_INFO='mem_info';  //会员信息表

	/**
     * 资金明细列表
     */
	public function index(){
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
            $sort='UpdateTime Desc';
        }

        $where=array();

        //会员姓名
        $TrueName=I('post.TrueName','','trim');
        $con=array();
        if($TrueName){
            $con['TrueName'] = array('like','%'.$TrueName.'%');
        }

        if(!empty($con)){
            $db=M(self::T_MEM_INFO);
            $MemIDS=$db->where($con)->field('ID')->select();
            $IDS=array();
            foreach ($MemIDS as $val){
                $IDS[]=$val['ID'];
            }
            //根据ID查询会员
            if(!empty($IDS)){
                $where['UserID']=array('in',$IDS);
            }else{
                $where['UserID']=null;
            }
        }
        //手机号码
        $Mobile=I('post.Mobile','','trim');
        $con=array();
        if($Mobile){
            $con['Mobile'] = array('like','%'.$Mobile.'%');
        }

        if(!empty($con)){
            $db=M(self::T_MEM_INFO);
            $MemIDS=$db->where($con)->field('ID')->select();
            $IDS=array();
            foreach ($MemIDS as $val){
                $IDS[]=$val['ID'];
            }
            //根据ID查询会员
            if(!empty($IDS)){
                $where['UserID']=array('in',$IDS);
            }else{
                $where['UserID']=null;
            }
        }

        //变更类型
        $Type=I('post.Type',-5,'intval');
        if($Type!=-5){$where['Type'] = $Type;}
        
        $SruType=I('post.SruType',-5,'intval');
        if($SruType!=-5){$where['SruType'] = $SruType;}

        //变更时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=date('Y-m-d',strtotime($EndTime."+1 day"));
        if($StartTime!=null){
            if($EndTime!=null){
                //有开始时间和结束时间
                $where['UpdateTime']=array('between',$ToStartTime.','.$ToEndTime);
            }else{
                //只有开始时间
                $where['UpdateTime']=array('egt',$ToStartTime);
            }
        }else{
            //只有结束时间
            if($EndTime!=null){
                $where['UpdateTime']=array('elt',$ToEndTime);
            }
        }

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $mem=M(self::T_MEM_INFO)->where(array('ID'=>$val['UserID']))->field('TrueName,Mobile')->find();
                $val['TrueName']=$mem['TrueName'];
                $val['Mobile']=$mem['Mobile'];
                $val['MemType']="非推荐";
                if($val['Type']==0){
                    $val['Type']="收入";
                    switch ($val['SruType']){
                        case 1:$val['SruType']="推荐会员";
                               switch ($val['Mtype']){
                                   case 1:$val['MemType']="推荐一级会员";
                                       break;
                                   case 2:$val['MemType']="发展二级会员";
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
                    $val['SruType']="";
                    $val['Mtype']="";
                }
                $val['Amount']=number_format($val['Amount'],2)."元";
                $val['CurrentBalance']=number_format($val['CurrentBalance'],2)."元";
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
    }


}