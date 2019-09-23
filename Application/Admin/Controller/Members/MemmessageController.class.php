<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      王少武
 * @修改日期：  2017-05-24 14:19
 * @继承版本:   1.1
 * @功能说明：  短信发送记录
 */
namespace Admin\Controller\Members;

use XBCommon;
use Admin\Controller\System\BaseController;

class MemmessageController extends BaseController {

    const T_TABLE = 'mem_message';
    const T_DICTIONARY = 'sys_dictionary';
    const T_ADMINISTAROR = 'sys_administrator';
    const T_MEMBER = 'mem_info';
    const T_MEMSMS = 'mem_sms';


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
        $sort=I('post.sort','','trim');
        $order=I('post.order');
        if($sort && $order){
            $sort = $sort.' '.$order;
        }else{
            $sort = 'ID desc';
        }

        //搜索会员，所有用户，所有商家三种类型
        $TrueName=I('post.TrueName','','trim');
        if($TrueName){
            $diction=M(self::T_DICTIONARY)->where(array('DictType'=>7,'DictName'=>array('like','%'.$TrueName.'%')))->find();
            $memInfo=M(self::T_MEMBER)->field('ID')->where(array('TrueName'=>array('like','%'.$TrueName.'%')))->select();
            if($memInfo){
                $idsArr=array_column($memInfo,'ID');
                $where['UserID']=array('in',$idsArr);

                if($diction){
                    $map = array(count($idsArr)+1 => 0);
                    $where['UserID'] = array('in', array_merge($map, $idsArr));
                }
            }elseif($diction){
                $where['UserID']=0;
            }else{
                $where['UserID']=null;
            }
        }

        $UserID=I('post.UserID');
        if($UserID){$where['UserID'] = array('eq',$UserID);}

        $Title=I('post.Title');
        if($Title){$where['Title'] = array('like','%'.$Title.'%');}
        $Type=I('post.Type',-5,'intval');
        if($Type!=-5){$where['Type']=$Type;}

//        $Mode=I('post.Mode',-5,'intval');
//        if($Mode!=-5){$where['Mode']=$Mode;}

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
                if($val['UserID']==0){
                    $val['TrueName']="所有会员";
                }else{
                    $memInfo=M(self::T_MEMBER)->field('TrueName,Mobile')->where(array('ID'=>$val['UserID']))->find();
                    $val['TrueName']=$memInfo['TrueName'];
                }
                $val['Status']=$query->GetValue(self::T_DICTIONARY,array('DictType'=>'4','DictValue'=>$val['Status']),'DictName');
                $val['Type']=$query->GetValue(self::T_DICTIONARY,array('DictType'=>'5','DictValue'=>$val['Type']),'DictName');
//                $val['Mode']=$query->GetValue(self::T_DICTIONARY,array('DictType'=>'6','DictValue'=>$val['Mode']),'DictName');
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
    }




	/**
	 * 保存编辑数据 添加数据
	 * @access   public
	 * @param    string  $id   获取id组成的字符串
	 * @return  返回处理结果
	 */
    public function Save()
    {
        if (IS_POST) {
            //数据保存前的验证规则
            $rules = array(
                array('Obj', array(1, 2), '请选择发送的对象！', 0, 'in'),
                array('Type', array(0, 1), '请选择消息类型！', 0, 'in'),
                array('Title', 'require', '内容标题必填！'),
                array('Contents', 'require', '内容必填！'),
            );

            //处理表单接收的数据
            $model = D(self::T_TABLE);
            $FormData = $model->validate($rules)->create();
            if (!$FormData) {
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0, $model->getError());
            } else {
                if ($FormData['Obj'] == 2) {
                    if ($FormData['UserID'] == 0) {
                        $this->ajaxReturn(0, "请选择发送的会员");
                    }
                    $exit = M(self::T_MEMBER)->where(array('ID' => $FormData['UserID'], 'IsDel' => 0, 'Status' => 1))->find();
                    if (!$exit) {
                        $this->ajaxReturn(0, "操作有误，请重新选择发送的会员");
                    }
                }

                //验证通过，执行后续保存动作
                $data = $FormData;
                $data['SendTime'] = date("Y-m-d H:i:s");
                $data['Status'] = 1;

                //保存数据
                if(empty($FormData['UserID'])){  //发送给所有会员
                    $data['UserID']=0;
                    $result=$model->add($data);
                    //分类型保存发送的信息
                    $memInfo=M(self::T_MEMBER)->field('ID,Mobile')->where(array('IsDel'=>0,'Status'=>1))->select();
                    $messageData=array('Uid'=>0,'Remarks'=>$FormData['Contents'],'Addtime'=>date('Y-m-d H:i:s'));
                    $sms_result=M(self::T_MEMSMS)->add($messageData);

                }

                if($FormData['UserID']>0){  //单独发送给某会员
                    $data['UserID']=$FormData['UserID'];
                    $result=$model->add($data);

                    $Mobile=M(self::T_MEMBER)->where(array('ID'=>$FormData['UserID']))->getField('Mobile');
                    //分类型操作
                    $messageData = array('Uid' => $FormData['UserID'], 'Remarks' => $FormData['Contents'], 'Addtime' => date('Y-m-d H:i:s'));
                    $sms_result = M(self::T_MEMSMS)->add($messageData);

                }
                if($result){
                    $this->ajaxReturn(1,"添加成功");
                }else{
                    $this->ajaxReturn(0,"添加失败");
                }
            }
        }
    }


    /**
     * 获取对象
     */
    public function getObj(){
        $row[0]=['id'=>0,'text'=>'--请选择--'];
        $row[1]=['id'=>1,'text'=>'所有会员'];
        $row[2]=['id'=>2,'text'=>'单独会员'];
        $this->ajaxReturn($row);
    }
    /**
     * 获取会员信息
     */
    public function getmajors(){
        $row[0]=['id'=>0,'text'=>'--请选择--'];
        $memInfo=M(self::T_MEMBER)->field('ID,TrueName')->where(array('IsDel'=>0,'Status'=>1))->select();
        foreach($memInfo as $key=>$val){
            $row[$key+1]=['id'=>$val['ID'],'text'=>$val['TrueName']];
        }
        $this->ajaxReturn($row);
    }
    /**
     * 获取消息类型
     */
    public function getCate(){
        $row[0]=['id'=>0,'text'=>'系统消息'];
        $row[1]=['id'=>1,'text'=>'通知消息'];
        $this->ajaxReturn($row);
    }
}