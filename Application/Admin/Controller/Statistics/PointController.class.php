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
use Admin\Controller\System\BaseController;
class PointController extends BaseController {

    const T_TABLE = 'point_home';

	/**
     * 信息列表
     */
	public function index(){

		$cateList=M('items_category')->field('ID,Name,Imageurl')->where(array('ParentID'=>0,'IsRec'=>'1','Status'=>1,'IsDel'=>0))->order('Sort asc,ID desc')->limit(4)->select();
		for($i=0;$i<count($cateList);$i++){
			$val = $cateList[$i];
			$this->assign('cate'.$i,$val['Name']);
		}
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
		$sort = 'createDate desc';
		//查询的列名	
		$result=array();
		$topRow = array();
		
		//查询的列名
		$col='';
		//获取最原始的数据列表
		$query=new XBCommon\XBQuery();
		$where = array();
		$where['enable'] = 1;
		$array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);
		$result=array();
		$topRow = array();
		$topRow['banner'] = 0;
		$topRow['message'] = 0;
		$topRow['cate1'] = 0;
		$topRow['cate2'] = 0;
		$topRow['cate3'] = 0;
		$topRow['cate4'] = 0;
		$topRow['cate5'] = 0;
		$topRow['cate6'] = 0;
		$topRow['cate7'] = 0;
		$topRow['cate8'] = 0;
		if($array['rows']){
			foreach ($array['rows'] as $val){
				$result['rows'][]=$val;
			}
			$SLQ = "select sum(banner) banner,sum(message) message,sum(cate1) cate1,sum(cate2) cate2,sum(cate3) cate3,sum(cate4) cate4,sum(cate5) cate5,sum(cate6) cate6,sum(cate7) cate7,sum(cate8) cate8 from xb_point_home";
			$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
			$mm = $sumRow[0];
			$result['total']=$array['total'];
			$topRow['banner'] = $mm['banner'];
			$topRow['message'] = $mm['message'];
			$topRow['cate1'] = $mm['cate1'];
			$topRow['cate2'] = $mm['cate2'];
			$topRow['cate3'] = $mm['cate3'];
			$topRow['cate4'] = $mm['cate4'];
			$topRow['cate5'] = $mm['cate5'];
			$topRow['cate6'] = $mm['cate6'];
			$topRow['cate7'] = $mm['cate7'];
			$topRow['cate8'] = $mm['cate8'];
			$topRow['createDate'] = "###（合计）";
			array_unshift($result['rows'],$topRow);
		}else{
			$topRow['createDate'] = "###（合计）";
			$result['rows'][] = $topRow;
		}
        $this->ajaxReturn($result);
    }
}