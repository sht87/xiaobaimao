<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      刁洪强
 * @修改日期：  2017-05-24 12:58
 * @继承版本:   1.1
 * @功能说明：  物流公司维护
 */
namespace Admin\Controller\System;
use Think\Controller;
use XBCommon;
class LogisticsController extends BaseController {


    const T_TABLE = 'sys_logistics';
    const T_ADMINISTRATOR = 'sys_administrator';


	/**
	 * 物流公司列表
	 */
	public function index(){
		$this->display();
	}


    /**
     * 显示物流公司列表数据
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

        $where=array();

        $Name=I('post.Name');
        if($Name){$where['Name'] = array('like','%'.$Name.'%');}

        $EName=I('post.EName');
        if($EName){$where['EName'] = array('like','%'.$EName.'%');}

        $Status=I('post.Status',-5,'intval');
        if($Status!=-5){$where['Status']=$Status;}

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $val['Status']=Get_Status_Val('is_disable',$val['Status']);
                $val['OperatorID']=Get_OperatorName($val['OperatorID']);
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
    }




	/**
	 * 编辑数据
	 */
    public function Edit($ID=null){
        $ID=(int)$ID;
        $this->assign('ID',$ID);
        $this->display();
    }

    /**
     * 查询详细信息
     */
    public function shows()
    {
        $id = I("request.ID",0, 'intval');
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
	 * 保存编辑数据 添加数据
	 * @access   public
	 * @param    string  $id   获取id组成的字符串
	 * @return  返回处理结果
	 */
    public function Save(){
        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('Name','require','物流公司名称必须填写！'), //默认情况下用正则进行验证
                array('EName','require','英文名称必须填写！'), //默认情况下用正则进行验证
            );

            //处理表单接收的数据
            $model=D(self::T_TABLE);
            $data=$model->validate($rules)->create();
            if(!$data){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                //验证通过，执行后续保存动作
                $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                $data['UpdateTime']=date("Y-m-d H:i:s");

                //保存或更新数据
                if($data['ID']>0){
                    //修改时,判断物流公司名称必须唯一！
                    $where['ID']=array('neq',$data['ID']);  //不等于当前要修改的ID
                    $where['Status']=1;
                    $con1['Name']=$data['Name'];
                    $con2['EName']=$data['EName'];
                    $db=M('Logistics');
                    $NameExists=$db->where($where)->where($con1)->count();
                    if($NameExists>0){
                        $this->ajaxReturn(false, '物流公司已存在！');
                    }
                    $ENameExists=$db->where($where)->where($con2)->count();
                    if($ENameExists>0){
                        $this->ajaxReturn(false, '英文名已存在！');
                    }

                    //无重名，则允许更新保存
                    $result=$model->where('ID='.$data['ID'])->save($data);
                    if($result>0){
                        $this->ajaxReturn(1, '修改成功');
                    }else{
                        $this->ajaxReturn(0, '修改失败');
                    }
                }else{
                    //新增时，判断物流名是否唯一，不允许重复
                    $db=M(self::T_TABLE);
                    $NameExists=$db->where(array('Name'=>$data['Name'],'Status'=>1))->count();
                    if($NameExists>0){
                        $this->ajaxReturn(false, '物流公司已存在！');
                    }
                    $ENameExists=$db->where(array('EName'=>$data['EName'],'Status'=>1))->count();
                    if($ENameExists>0){
                        $this->ajaxReturn(false, '英文名已存在！');
                    }
                    //无重名，则允许新增记录
                    $result=$model->add($data);
                    if($result>0){
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
     * 数据删除处理 单条或多条
     */
    public function Del()
    {
        $mod = D(self::T_TABLE);
        //获取删除数据id (单条或数组)
        $ids = I("post.ID", '', 'trim');
        $arr=explode(',',$ids);  //转化为一维数组

        //根据选择的ID值，进行物理删除
        $con['ID']=array('in',$arr);
        $res=$mod->where($con)->delete();  //逻辑删除
        if ($res) {
            $this->ajaxReturn(true, "删除数据成功！");
        } else {
            $this->ajaxReturn(false, "删除数据时出错！");
        }
    }

}