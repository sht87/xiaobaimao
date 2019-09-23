<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 电    话：18363857597
 * 作    者：李志修
 * 修改时间: 2017-04-27 08:50
 * 功能说明:广告管理
 */
namespace Admin\Controller\System;

use Think\Controller;
use XBCommon;
class AdcontentController extends  BaseController
{

    const T_TABLE = 'sys_adcontent';
    const T_DICTIONARY = 'sys_dictionary';
    const T_ADMINISTRATOR = 'sys_administrator';
    const T_ADVRTTISING = 'sys_advertising';

    public function _initialize()
    {
        parent::_initialize();

        $this->Advertising = M(self::T_ADVRTTISING)->where(array('Status'=>'1','IsDel'=>0))->getField('ID,Name');
        $this->Help_Enable = C('Help.Help_Enable');
    }

    public function index(){

        // $this->assign('Advertising',$this->Advertising);

        $this->display();
    }

    public function lists()
    {
        $this->display();
    }

    public function DataList(){

        $Help_SystemClass = C('Help.Help_SystemClass');

        //接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
        $sort=I('post.sort');
        $order=I('post.order');
        if($sort && $order){
            $sort = $sort.' '.$order;
        }

        $Name=I('post.Title','','trim');
        if($Name){$where['Name'] = array('like','%'.$Name.'%');}

        $Status=I('post.Status',-5,'intval');
        if($Status!=-5){$where['Status']=$Status;}

        $AdvertisingID = I('get.AdvertisingID', 0,'intval');
        if($AdvertisingID){
            $where['AdvertisingID'] = $AdvertisingID;
        }

        $where['IsDel']=0;

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $val['AdvertisingID']=$query->GetValue(self::T_ADVRTTISING,array('ID'=>(int)$val['AdvertisingID']),'Name');
                $val['Status']=$this->Help_Enable[$val['Status']];
                $val['OperatorID']=$query->GetValue(self::T_ADMINISTRATOR,array('ID'=>(int)$val['OperatorID']),'UserName');

                if($val['UrlType'] == 2){
                    if($Help_SystemClass[$val['SystemClass']]['table']){
                        $cateTree = M($Help_SystemClass[$val['SystemClass']]['table'])->where(array('Status'=>1,'ID'=>$val['SystemClassVal']))->getField('Name');
                    }else{
                        $cateTree = $Help_SystemClass[$val['SystemClass']]['child'][$val['SystemClassVal']]['CidVal'];
                    }

                    $val['CateID'] = $Help_SystemClass[$val['SystemClass']]['name'] .' - '.$cateTree;
                }else{
                    $val['CateID'] = $val['Url'];
                }

                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        echo M()->getLastSql();
        $this->ajaxReturn($result);
    }

    //侧边栏列表
    public function DataTree(){
        //实例化模型
//        $arr = catemenu1(self::T_CONTENTCATEGORIES,'IsDel=0');

        $arr =  M(self::T_ADVRTTISING)->field('*,ID as id, Name as text')->where('IsDel=0')->order('Sort asc,ID desc')->select();
        echo json_encode($arr);
    }

    /**
     * 编辑页面
     */
    public function Edit($ID=null){
        $ID=(int)$ID;
        $this->assign('ID',$ID);

        //获取状态列表
        $query=new XBCommon\XBQuery();
        $StatusList=$query->GetList(self::T_DICTIONARY,array('DictType'=>'1','Status'=>'1'),'ID ASC','DictName,DictValue');
        $Advertising=$query->GetList(self::T_ADVRTTISING,array('Status'=>'1'),'ID,Name');
        $this->assign('StatusList',$StatusList);
        $this->assign('Advertising',$Advertising);

        if($ID){
            $find = M(self::T_TABLE)->where(array('ID'=>$ID))->field('UrlType,SystemClass')->find();
        }else{
            $find = array('UrlType'=>1);
        }


        $this->assign('find',$find);

        $this->display();
    }

    /**
     * 查询详细信息
     */
    public function shows()
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
     * 数据保存
     */
    public function Save(){
        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('Name','require','名称必须填写！'), //默认情况下用正则进行验证
                //array('Url','require','跳转链接必须填写！'), //默认情况下用正则进行验证
            );

            //处理表单接收的数据
            $model=D(self::T_TABLE);
            $data=$model->validate($rules)->create();

			if($data['UrlType']==3){
				if($data['productNo']==''){
					$this->ajaxReturn(0, '请输入商品编码');

				}
			}
		
            if(!$data){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
				if($data['UrlType']==3){
					$productNo = $data['productNo'];
					$item = M('items')->where(array('GoodsNo'=>$productNo,'Status'=>1,'IsDel'=>0))->find();
					if(!$item){
						$this->ajaxReturn(0, '产品不存在');
					}else{
						$data['productID'] = $item['ID'];
						$data['Url'] = $item['Openurl'];
					}
				}
                //验证通过，执行后续保存动作
                $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                $data['UpdateTime']=date("Y-m-d H:i:s");

                //保存或更新数据
                if($data['ID']>0){
                    if($data['UrlType']==1){
                        $data['SystemClass']='';
                        $data['SystemClassVal']=0;
                    }
                    if($data['UrlType']==2){
                        $data['Url']='';
                    }
                    $result=$model->where('ID='.$data['ID'])->save($data);
                    if($result>0){
                        $this->ajaxReturn(1, '修改成功');
                    }else{
                        $this->ajaxReturn(0, '修改失败');
                    }
                }else{
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

    /*
     * 获取分类
     */
    public function getLastCate(){

        $Help_SystemClass = C('Help.Help_SystemClass');
        foreach($Help_SystemClass as $k=>$val){
            $cateTree[] = array(
                'id' => $k,
                'text'=> $val['name'],
            );
        }

        //$cateTree=has_children_cate(self::T_TYPE,array("ParentID"=>0,'Status'=>1),'Sort asc,ID desc');
        $this->ajaxReturn($cateTree);
    }

    public function getCate(){

        $ids = I("get.ids", '', 'trim');

        $Help_SystemClass = C('Help.Help_SystemClass');

        if($Help_SystemClass[$ids]['table']){
            $cateTree = has_children_cate1($Help_SystemClass[$ids]['table'],array("ParentID"=>0,'Status'=>1),'Sort asc,ID desc');
        }else{
            foreach($Help_SystemClass[$ids]['child'] as $k=>$val){
                $cateTree[] = array(
                    'id' => $val['Cid'],
                    'text'=> $val['CidVal'],
                );
            }
        }
        //$cateTree=has_children_cate(self::T_TYPE,array("ParentID"=>0,'Status'=>1),'Sort asc,ID desc');
        $this->ajaxReturn($cateTree);
    }

}