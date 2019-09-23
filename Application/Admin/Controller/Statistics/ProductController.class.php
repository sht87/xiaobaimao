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
        $ToEndTime=$EndTime;
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
		
		if(!$StartTime&&!$EndTime){
			//查询的列名
			$query=new XBCommon\XBQuery();
			$sql = "select distinct productName from xb_product_statistics";
			if(!is_null($Title)&&$Title!=''){
				$sql = "select distinct productName from xb_product_statistics where productName like '%".$Title."%'";
			}
			$nameArr=$query->getBaseList(self::T_TABLE,$sql);
			$result=array();
			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNumCPA'] = 0;
			$topRow['registerNum'] = 0;
			for($i=0;$i<count($nameArr);$i++){
				$name = $nameArr[$i]['productName'];
				$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,productId from xb_product_statistics where productName = '".$name."'";
				$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
				$val = $sumRow[0];
				
				$val['productName'] = $name;
				if(is_null($val['PV'])){
					$val['PV'] = 0;
				}
				if(is_null($val['UV'])){
					$val['UV'] = 0;
				}
				if(is_null($val['registerNum'])){
					$val['registerNum'] = 0;
				}
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
				$productId = $val['productId'];
				$S1 = "select price from xb_item_price where itemId = ".$productId." and ".$val['registerNum']." >= num order by num desc";
				$s1rp =$query->getBaseData(self::T_TABLE,$S1);
				if(count($s1rp)>0){
					$val11 = $s1rp[0];
					$val['registerNumCPA']=$val['registerNum']*$val11['price'];
				}
				$topRow['PV'] = $topRow['PV'] + $val['PV'];
				$topRow['UV'] = $topRow['UV'] + $val['UV'];
				$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
				$topRow['registerNumCPA'] = $topRow['registerNumCPA'] + $val['registerNumCPA'];
				$result['rows'][]=$val;
			}
			$result['total']=count($nameArr);

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
			
			//$topRow['registerNumCPA']=$topRow['registerNum']*$topRow['CPA'];
			$topRow['productName'] = "###（合计）";
			array_unshift($result['rows'],$topRow);

		}else{
			//查询的列名
			$col='';
			//获取最原始的数据列表
			$query=new XBCommon\XBQuery();
			$array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);
			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNum'] = 0;
			$topRow['registerNumCPA'] = 0;
			//如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
			$result=array();
			if($array['rows']){
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
					$productId = $val['productId'];
					$S1 = "select price from xb_item_price where itemId = ".$productId." and ".$val['registerNum']." >= num order by num desc";
					$s1rp =$query->getBaseData(self::T_TABLE,$S1);
					if(count($s1rp)>0){
						$val11 = $s1rp[0];
						$val['registerNumCPA']=$val['registerNum']*$val11['price'];
					}
					$topRow['PV'] = $topRow['PV'] + $val['PV'];
					$topRow['UV'] = $topRow['UV'] + $val['UV'];
					$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
					$topRow['registerNumCPA'] = $topRow['registerNumCPA'] + $val['registerNumCPA'];
					$result['rows'][]=$val;
				}
				$result['total']=$array['total'];

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
				
				//$topRow['registerNumCPA']=$topRow['registerNum']*$topRow['CPA'];
				$topRow['productName'] = "###（合计）";
				array_unshift($result['rows'],$topRow);
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
        //查出相应信息
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
        $ToEndTime=$EndTime;
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
		
		if(!$StartTime&&!$EndTime){
			//查询的列名
			$query=new XBCommon\XBQuery();
			$sql = "select distinct productName from xb_product_statistics";
			if(!is_null($Title)&&$Title!=''){
				$sql = "select distinct productName from xb_product_statistics where productName like '%".$Title."%'";
			}
			$nameArr=$query->getBaseList(self::T_TABLE,$sql);
			$result=array();
			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNumCPA'] = 0;
			$topRow['registerNum'] = 0;
			for($i=0;$i<count($nameArr);$i++){
				$name = $nameArr[$i]['productName'];
				$SLQ = "select sum(PV) PV,sum(UV) UV,sum(registerNum) registerNum,productId from xb_product_statistics where productName = '".$name."'";
				$sumRow =$query->getBaseData(self::T_TABLE,$SLQ);
				$val = $sumRow[0];
				
				$val['productName'] = $name;
				if(is_null($val['PV'])){
					$val['PV'] = 0;
				}
				if(is_null($val['UV'])){
					$val['UV'] = 0;
				}
				if(is_null($val['registerNum'])){
					$val['registerNum'] = 0;
				}
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
				$productId = $val['productId'];
				$S1 = "select price from xb_item_price where itemId = ".$productId." and ".$val['registerNum']." >= num order by num desc";
				$s1rp =$query->getBaseData(self::T_TABLE,$S1);
				if(count($s1rp)>0){
					$val11 = $s1rp[0];
					$val['registerNumCPA']=$val['registerNum']*$val11['price'];
				}
				$topRow['PV'] = $topRow['PV'] + $val['PV'];
				$topRow['UV'] = $topRow['UV'] + $val['UV'];
				$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
				$topRow['registerNumCPA'] = $topRow['registerNumCPA'] + $val['registerNumCPA'];
				$result['rows'][]=$val;
			}
			$result['total']=count($nameArr);

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
			
			//$topRow['registerNumCPA']=$topRow['registerNum']*$topRow['CPA'];
			$topRow['productName'] = "###（合计）";
			array_unshift($result['rows'],$topRow);

		}else{
			//查询的列名
			$col='';
			//获取最原始的数据列表
			$query=new XBCommon\XBQuery();
			$array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);
			$topRow = array();
			$topRow['PV'] = 0;
			$topRow['UV'] = 0;
			$topRow['registerNum'] = 0;
			$topRow['registerNumCPA'] = 0;
			//如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
			$result=array();
			if($array['rows']){
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
					$productId = $val['productId'];
					$S1 = "select price from xb_item_price where itemId = ".$productId." and ".$val['registerNum']." >= num order by num desc";
					$s1rp =$query->getBaseData(self::T_TABLE,$S1);
					if(count($s1rp)>0){
						$val11 = $s1rp[0];
						$val['registerNumCPA']=$val['registerNum']*$val11['price'];
					}
					$topRow['PV'] = $topRow['PV'] + $val['PV'];
					$topRow['UV'] = $topRow['UV'] + $val['UV'];
					$topRow['registerNum'] = $topRow['registerNum'] + $val['registerNum'];
					$topRow['registerNumCPA'] = $topRow['registerNumCPA'] + $val['registerNumCPA'];
					$result['rows'][]=$val;
				}
				$result['total']=$array['total'];

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
				
				//$topRow['registerNumCPA']=$topRow['registerNum']*$topRow['CPA'];
				$topRow['productName'] = "###（合计）";
				array_unshift($result['rows'],$topRow);
			}
		}
        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>产品名称</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>浏览数</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>申请数</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>申请转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>注册数</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>注册转化率</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>CPA</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>结算费用</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>UV成本价</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>日期</td>
            </tr>';

        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.$row['productName'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['PV'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['UV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['PVUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNum'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNumUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['CPA'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNumCPA'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['registerNumCPAUV'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['createDate'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'product';
        //$str_filename = iconv('UTF-8', 'GB2312//IGNORE',$str_filename);
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