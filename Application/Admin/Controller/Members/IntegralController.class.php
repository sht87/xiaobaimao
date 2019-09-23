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
class IntegralController extends BaseController {

    const T_TABLE = 'mem_integrals';  //积分明细表
    const T_INFO='mem_info';  //会员信息表

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
        }
        $where=null;
        $UserID=I('post.UserID');
        if($UserID){$where['UserID'] = array('like','%'.$UserID.'%');}

        $UserName=I('post.UserName');
        if(!empty($UserName)){
            $con['UserName']=array('like','%'.$UserName.'%');
            $UidS=M(self::T_INFO)->where($con)->field('ID')->select();
            if(!empty($UidS)){
                $where['UserID'] = array('in',$UidS['0']);
            }else{
                $where['UserID']=0;
            }
        }

        $Mobile=I('post.Mobile');
        if(!empty($Mobile)){
            $con['Mobile']=array('like','%'.$Mobile.'%');
            $UidS=M(self::T_INFO)->where($con)->field('ID')->select();
            if(!empty($UidS)){
                $where['UserID'] = array('in',$UidS['0']);
            }else{
                $where['UserID']=0;
            }
        }

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
                $MemInfo=M(self::T_INFO)->where(array('ID'=>$val['UserID']))->find();
                $val['UserName']=$MemInfo['UserName'];
                $val['Mobile']=$MemInfo['Mobile'];
                $val['Email']=$MemInfo['Email'];
                $val['TrueName']=$MemInfo['TrueName'];
                switch ($val['Type']){
                    case 0:
                        $val['Type']='收入';
                    case 1:
                        $val['Type']='支出';
                }
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
    }


}