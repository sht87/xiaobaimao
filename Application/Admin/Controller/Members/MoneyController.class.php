<?php
/**
 *  版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018-01-06 15:50
 * 功能说明: 提现管理
 */

namespace Admin\Controller\Members;

use XBCommon;

use Admin\Controller\System\BaseController;

class MoneyController extends BaseController
{
    const T_TABLE='mem_money';
    const T_ADMIN='sys_administrator';
    const T_MEMBER='mem_info';
    const T_BALANCES='mem_balances';
    /**
     * 提现页
     */
    public function index(){
        $this->display();
    }

    /**
     * 获取提现列表
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
        $where['IsDel'] = 0;
        $where['Type'] = 0;

        $TrueName = I('post.TrueName','','trim');
        $CardNo   = I('post.CardNo','','trim');
        $Mobile = I('post.Mobile','','trim');

        if($TrueName){
            $col['TrueName'] = array('like','%'.$TrueName.'%');
            $memInfo=M(self::T_MEMBER)->field('ID,TrueName')->where($col)->select();
            if($memInfo){
                $where['Uid']=array('in',array_column($memInfo,'ID'));
            }else{
                $where['Uid']='';
            }
        }


        if($CardNo){
            $where['CardNo'] = array('like','%'.$CardNo.'%');
        }

        if($Mobile){
            $col['Mobile'] = array('like','%'.$Mobile.'%');
            $memInfo=M(self::T_MEMBER)->field('ID,UserName')->where($col)->select();
            if($memInfo){
                $where['Uid']=array('in',array_column($memInfo,'ID'));
            }else{
                $where['Uid']='';
            }
        }

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        $Type=array("提现","充值");

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $MemInfo=M(self::T_MEMBER)->field('TrueName,Mobile')->where(array('ID'=>$val['Uid']))->find();
                $val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>$val['OperatorID']),'UserName');
                $val['Mobile']=$MemInfo['Mobile'];
                $val['TrueName']=$MemInfo['TrueName'];
                $val['Type']=$Type[$val['Type']];
                $val['Money']=number_format($val['Money'],2).'元';
                $val['Charge']=number_format($val['Charge'],2).'元';
                if($val['PayType']==1){
                    $val['PayType']="微信";
                }elseif($val['PayType']==2){
                    $val['PayType']="支付宝";
                }else{
                    $val['PayType']='';
                }
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
    }

    /**
     * 详情界面
     */
    public function detail(){
        $id=I('request.ID');
        $tabList=M(self::T_TABLE)->alias('a')
            ->join('left join xb_mem_info b on a.Uid=b.ID')
            ->field('a.*,b.ID as Mid,b.TrueName,b.UserName,b.Mobile')
            ->where(array('a.ID'=>$id))
            ->find();
        if($tabList['OperatorID']){
            $operator=M(self::T_ADMIN)->field('UserName')->where(array('ID'=>$tabList['OperatorID']))->find();
            $tabList['admin']=$operator['UserName'];
        }
        $tabList['Remark']=htmlspecialchars_decode($tabList['Remark']);
        $this->assign('tabList',$tabList);
        $this->display();
    }

    /**
     * 提现审核
     */
    public function aduit(){
        $id=I('get.ID',0,'intval');
        $res=M(self::T_TABLE)->where(array("ID"=>$id))->find();
//        $res['Charge']=$res['Money']*$GLOBALS['BasicInfo']['Cost'];
        $this->assign("res",$res);
        $this->display();
    }

    /**
     * 审核详情
     */
    public function loadajax(){
        $id=I('get.ID',0,'intval');
        if($id){
            $res=M(self::T_TABLE)->where(array("ID"=>$id))->find();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn(array('result'=>false,'message'=>'不存在的记录！'));
            }
        }
    }

    /**
     * 提现审核信息
     */
    public function followdone(){
        $id=I("post.ID",0,"intval");
        $IsAduit=I("post.IsAduit",0,'Intval');
        $Batch=I('post.Batch','','trim');
        $Remark=I("post.Remark","","trim");
        if($id==0){
            $this->ajaxReturn(0,"审核异常，请重新提交！");
        }
        $model=M(self::T_TABLE);
        $find = $recs = $model->where(array('ID'=>$id))->find();
        if($recs['IsAduit']>1){
            $this->ajaxReturn(0,"不能对已有的审核结果,进行操作");
        }
        if($IsAduit==0){
            $this->ajaxReturn(0,"请选择审核结果！");
        }
        if($IsAduit==2 && $Batch==''){
            $this->ajaxReturn(0,'请填写交易单号');
        }
        if(in_array($IsAduit,array(3,2)) && $Remark==''){
            $this->ajaxReturn(0,'请填写审核备注');
        }

        $data=array(
            'IsAduit'   =>$IsAduit,
            'Remark'    =>$Remark,
            'Batch'     =>$Batch,
            'OperatorID'=> $_SESSION['AdminInfo']['AdminID'],
            'UpdateTime'=> date("Y-m-d H:i:s")
        );

        $res=$model->where(array("ID"=>$id))->save($data);
        if(!$res){
            $this->ajaxReturn(0,"审核失败，请重新审核");
        }

        $memRes=M(self::T_MEMBER)->field('TrueName,Mobile')->where(array("ID"=>$find['Uid']))->find();
        $msg="尊敬的用户".$memRes['TrueName']."，您提现的人民币".$find['Money'].'元，审核未通过';
        $text="您于".$find['AddTime']."提现的人民币".$find['Money'].'元';
        if($IsAduit==3){
            //审核未通过  退回提现金额
            $backmoney=$find['Money']+$find['Charge'];
            $backrest=M(self::T_MEMBER)->where(array("ID"=>$find['Uid']))->setInc('Balance',$backmoney);
            if($backrest){
                //发送系统通知消息
                member_sms($find['Uid'],'1','提现失败',$text.',审核未通过');
                $CurrentBalance=M(self::T_MEMBER)->where(array('ID'=>$find['Uid']))->getField('Balance');
                //余额变动明细
                $balancedata=array(
                    'Type'=>'0',
                    'SruType'=>'5',
                    'Amount'=>$backmoney,
                    'CurrentBalance'=>$CurrentBalance,
                    'Description'=>'提现失败资金退还',
                    'UserID'=>$find['Uid'],
                    'UpdateTime'=>date('Y-m-d H:i:s'),
                    'TradeCode'=>date("YmdHis").rand(10000,99999),
                    );
                M(self::T_BALANCES)->add($balancedata);
                $this->ajaxReturn(1,"经过审核，未让其通过!");
            }
        }
        if($IsAduit==2){
            //提现审核通过
            member_sms($find['Uid'],'1','提现审核通过',$text.'审核已通过');
            //短信和微信推送信息
            $Message = new \Extend\Message($id,3);
            $Message->sms();
            $this->ajaxReturn(1,"审核通过！");
        }

    }

    //逻辑删除
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
     * 审核状态
     */
    public function getCate(){
        $row[0]=['id'=>0,'text'=>'--请选择--'];
        $row[1]=['id'=>1,'text'=>'审核通过'];
        $row[2]=['id'=>2,'text'=>'审核未通过'];
        $this->ajaxReturn($row);
    }
}