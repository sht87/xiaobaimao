<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      刁洪强
 * @修改日期：  2017-05-24 14:19
 * @继承版本:   1.1
 * @功能说明：  短信发送记录
 */
namespace Admin\Controller\System;
use Think\Controller;
use XBCommon;
class MessageController extends BaseController {

    const T_SMS = 'sys_sms';
    const T_ADMINISTAROR = 'sys_administrator';
    const T_MEMBER = 'mem_info';


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

        $where=null;

        $ObjectID=I('post.ObjectID');
        if($ObjectID){$where['ObjectID'] = array('like','%'.$ObjectID.'%');}

        $SendMess=I('post.SendMess');
        if($SendMess){$where['SendMess'] = array('like','%'.$SendMess.'%');}

        $Type=I('post.Type',-5,'intval');
        if($Type!=-5){$where['Type']=$Type;}

        $Mode=I('post.Mode',-5,'intval');
        if($Mode!=-5){$where['Mode']=$Mode;}

        $Status=I('post.Status',-5,'intval');
        if($Status!=-5){$where['Status']=$Status;}

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_SMS,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $val['Status']=Get_Status_Val('send_status',$val['Status']);
                $val['Type']=Get_Status_Val('send_type',$val['Type']);
                $val['Mode']=Get_Status_Val('receive_mode',$val['Mode']);
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
    public function Save(){
        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('ObjectID','require','发送对象不可为空！'), //默认情况下用正则进行验证
                array('SendMess','require','发送内容可不为空！'), //默认情况下用正则进行验证
            );

            //处理表单接收的数据
            $model=D(self::T_SMS);
            $data=$model->validate($rules)->create();
            if(!$data){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                //验证通过，执行后续保存动作
                $data['Type']=1; // 短信发送类型  0 系统发送 1 手工发送
                $data['SendTime']=date("Y-m-d H:i:s");
                $Obj=I("request.Obj",1,'intval');
                $sendmess=I("request.SendMess",'','trim');
                if($Obj==1){
                    $where='';
                    $data['ObjectID']='所有人';
                }else{
                    $ObjectID=I("request.ObjectID",'','trim');
                    $where=array('UserName'=>$ObjectID);
                }
                //保存或更新数据
                if(empty($data['ID'])){
                    if((int)$data['Mode'] < 0 || (int)$data['Mode'] > 2){
                        $this->ajaxReturn(false, '消息接收模式选择错误！');
                    }
                    //调用短信发送接口,将调用的结果值，传递给Status，用于判断短信是否发送出去【未集成】
                    $data['Status']=1;
                    //保存发送信
                    $result=$model->add($data);
                    if($result>0){
                        if((int)$data['Mode']>0) {
                            $result = M(self::T_MEMBER)->where($where)->select();
                            foreach ($result as $val) {
                                $res = sendmessage($val['Mobile'], $sendmess);
                                if (!strpos($res, 'success')) {
                                    $this->ajaxReturn(0, $val['Phone'] . '短信发送失败,请重试！');
                                }
                            }
                        }
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
     * 删除模块继承核心类
	*/

}