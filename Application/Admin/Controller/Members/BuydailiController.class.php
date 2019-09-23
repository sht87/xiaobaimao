<?php
/**
 *  版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018-01-06 15:50
 * 功能说明: 充值管理
 */

namespace Admin\Controller\Members;

use XBCommon;

use Admin\Controller\System\BaseController;

class BuydailiController extends BaseController
{
    const T_TABLE='mem_buydaili';
    const T_ADMIN='sys_administrator';
    const T_MEMBER='mem_info';
    const T_BALANCES='mem_balances';
    const T_LEVELS='mem_levels';

    public function index(){
        $this->display();
    }

    /**
     * 获取充值列表
     */
    public function DataList(){
   		//接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
        $sort=I('post.sort','','trim');
        $order=I('post.order');
        if($sort && $order) {
              $sort = $sort . ' ' . $order;
        }else{
            $sort = 'ID desc';
        }
        //$TrueName = I('post.TrueName','','trim');
        $UserID = I('post.UserID','','trim');
        $Mobile = I('post.Mobile','','trim');
        $OrderSn = I('post.OrderSn','','trim');
        $Title=I('post.Title','','trim');
        $Status=I('post.Status',-5,'intval');
        $PayType=I('post.PayType',-5,'intval');

        // if($TrueName){
        //     $col['TrueName'] = array('like','%'.$TrueName.'%');
        //     $memInfo=M(self::T_MEMBER)->field('ID,TrueName')->where($col)->select();
        //     if($memInfo){
        //         $where['UserID']=array('in',array_column($memInfo,'ID'));
        //     }else{
        //         $where['UserID']='';
        //     }
        // }
        if($UserID){
          $where['UserID']=array('eq',$UserID);
        }
        if($OrderSn){
          $where['OrderSn']=array('eq',$OrderSn);
        }
        if($Mobile){
            $col['Mobile'] = array('like','%'.$Mobile.'%');
            $memInfo=M(self::T_MEMBER)->field('ID')->where($col)->select();
            if($memInfo){
                $where['UserID']=array('in',array_column($memInfo,'ID'));
            }else{
                $where['UserID']='';
            }
        }
        if($Title){
            $col['Name'] = array('like','%'.$Title.'%');
            $memInfo=M(self::T_LEVELS)->field('ID')->where($col)->select();
            if($memInfo){
                $where['LevelID']=array('in',array_column($memInfo,'ID'));
            }else{
                $where['LevelID']='';
            }
        }
        if($Status!=-5){
            $where['Status']=$Status;
        }
        if($PayType!=-5){
            $where['PayType']=$PayType;
        }
        $where['IsDel'] = 0;

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        $PayType=array("","微信","支付宝","余额");
        $Status=array("","待付款","已付款","已取消");
   		  //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
   			  foreach ($array['rows'] as $val){
                  $MemInfo=M(self::T_MEMBER)->field('TrueName,Mobile')->where(array('ID'=>$val['UserID']))->find();
                  $val['TrueName']=$MemInfo['TrueName'];
                  $val['Mobile']=$MemInfo['Mobile'];

                  $LevInfo=M(self::T_LEVELS)->field('Name,Price')->where(array('ID'=>$val['LevelID']))->find();
                  $val['Name']=$LevInfo['Name'];
                  $val['Price']=$LevInfo['Price'];

                  $val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>$val['OperatorID']),'UserName');

                  $val['PayType']=$PayType[$val['PayType']];
                  $val['Status']=$Status[$val['Status']];
                  $val['OrderAmount']=number_format($val['OrderAmount'],2).'元';
   				  $result['rows'][]=$val;
   			  }
   			  $result['total']=$array['total'];
   		  }
        $this->ajaxReturn($result);
   	}

    /**
     * 逻辑删除
     */
    public function del(){
        $mod = M(self::T_TABLE);
        $id_str=I("post.ID",'','trim');
        $data = array('IsDel'=>1);

        $map['ID']  = array('in',$id_str);

        $res = $mod->where($map)->setField($data);
        if($res===false){
            $this->ajaxReturn(0,"删除数据失败！");
        }else{
            $this->ajaxReturn(1,"删除数据成功！");
        }
    }

    /**
     * 详情界面
     */
    public function detail(){
        $id=I('request.ID');
        $tabList=M(self::T_TABLE)->alias('a')
            ->join('left join xb_mem_info b on a.UserID=b.ID')
            ->join('left join xb_mem_levels c on a.LevelID=c.ID')
            ->field('a.*,b.TrueName,b.Mobile,c.Name')
            ->where(array('a.ID'=>$id))
            ->find();
        $this->assign('tabList',$tabList);
        $this->display();
    }

}