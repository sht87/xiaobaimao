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
class ProductController extends BaseController {

    const T_TABLE = 'product_statistics';

	/**
     * 信息列表
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
		if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='createDate desc';
         }
        //添加时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=date('Y-m-d',strtotime($EndTime."+1 day"));
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
		$Title = I('post.Title');
		if(!is_null($Title)&&$Title!=''){
			$where['productName']=$Title;
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
				//按金额
				$val['PVUV'] = 0;
				$val['registerNumUV'] = 0;
				$val['registerNumCPAUV']=0;
				if($val['PV']!=0){
					$val['PVUV']= round($val['UV']/$val['PV'],2);
					 
				}
				if($val['UV']!=0){
					$val['registerNumUV']=$val['registerNum']/$val['UV'];
					$val['registerNumCPAUV']=$val['registerNum']*$val['CPA']/$val['UV'];
				}
				$val['registerNumCPA']=$val['registerNum']*$val['CPA'];
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
}