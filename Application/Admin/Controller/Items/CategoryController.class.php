<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      王少武
 * @修改日期：  2018-04-07
 * @功能说明：  借款类型控制类
 */
namespace Admin\Controller\Items;
use XBCommon;
use Admin\Controller\System\BaseController;
class CategoryController extends BaseController {

    const T_TABLE='items_category';
    const T_ADMIN='sys_administrator';
    const T_ITEMS='items';
	/**
	 * @功能说明：显示分类页面
	 * @return [type]                           [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * @功能说明：显示商品分类数据
	 */
    public function DataList(){
        //分页操作
        $page=listPage("Sort asc,ID desc");
        //获取最原始的数据列表
        $result= $this->get_cate_child(self::T_TABLE,array('ParentID'=>0,'IsDel'=>0),$page[1]);
        $this->ajaxReturn($result);
    }

    /**
     * 分类管理树结构数组  用于获取分类表树结构 列表展示
     * @param $table string 表名
     * @param $where  string 条件
     * @param $sort  string 排序
     * @return array|bool
     */
   public function get_cate_child($table,$where,$sort='ID desc',$id=0){
        $arr=M($table)->where($where)->order($sort)->select();
        if($arr){
            foreach ($arr as $val){
                $query=new XBCommon\XBQuery();
                $map['ID']=intval($val['OperatorID']);
                $OperatorID=$query->GetValue(self::T_ADMIN,$map,'UserName');
                $two_arr[]=array(
                    'ID' 	        => $val['ID'],
                    'Name'    	    => $val['Name'],
                    'EName' 	    => $val['EName'],
                    'Sort'	        => $val['Sort'],
                    'Imageurl'	    => $val['Imageurl']?1:0,
                    'Status'	    => $val['Status'],
                    'IsRec'	        => $val['IsRec'],
                    'OperatorID' 	=> $OperatorID,
                    'UpdateTime' 	=> $val['UpdateTime'],
                    'children'      => $this->get_cate_child($table,array("ParentID"=>$val['ID'],'IsDel'=>0),$sort)?$this->get_cate_child($table,array("ParentID"=>$val['ID'],'IsDel'=>0),$sort):''
                );
            }
            return $two_arr;
        }else{
            return false;
        }

    }

    /**
     * @功能说明：编辑 添加 页面
     * @return [type]                           [description]
     */
	public function edit(){
		$id=I("request.ID",0,'intval');
		if($id){
			$this->assign("id",$id);
		}
		$this->display();
	}



    /**
     * 查询详细信息
     */
    public function Shows()
    {
        $id = I("request.ID", 0, 'intval');
        if ($id) {
            $model = M(self::T_TABLE);
            $result = $model->find($id);
            if($result){
                $this->ajaxReturn($result);
            }else{
                //没有查询到内容
                $this->ajaxReturn(array('result'=>false,'message'=>'不存在的记录！'));
            }
        }
    }

    /**
     * @功能说明：获取分类树
     */
    public function getLastCate(){
        //$where=array('ParentID'=>0,'IsDel'=>0);
        $where['ParentID']=array('eq','0');
        $where['IsDel']=array('eq','0');
        $cateTree=$this->get_cate_tree(self::T_TABLE,$where,'Sort asc,ID desc');
        $row[0]=array('id'=>0,'text'=>'父级分类');
        if($cateTree){
            $newCateTree=array_merge($row,$cateTree);
        }else{
            $newCateTree=$row;
        }
        $this->ajaxReturn($newCateTree);
    }

    /**
   * 分类管理树结构数组  用于获取分类表树结构 下拉展示
   * @param $table string 表名
   * @param $where  string 条件
   * @param $sort  string 排序
   * @param $map  string 分类其他条件（如：是否显示）
   * @return array|bool
   */
  function get_cate_tree($table,$where,$sort='ID desc',$map=''){
      $arr=M($table)->where($where)->order($sort)->select();
      if($arr) {
          foreach ($arr as $key => $val) {
              $where2=array();
              $where2['ParentID']=array('eq',$val['ID']);
              $where2['IsDel']=array('eq','0');
              $row = array(
                  'id' => $val['ID'],
                  'text' => $val['Name'],
                  'children' =>$this->get_cate_tree($table, $map."ParentID=" . $val['ID'], $sort) ? $this->get_cate_tree($table, $where2, $sort) : ''
              );
              $two_arr[$key] = $row;
              if (empty($two_arr[$key]['children'])) {
                  unset($two_arr[$key]['children']);
              }
          }
          return $two_arr;
      }else{
          return false;
      }
  }

    /**
     * @功能说明：添加 编辑 分类信息
     * @return [type]                           [description]
     */
    public function save(){

        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('Name','require','分类名称必须填写！'), //默认情况下用正则进行验证
                array('EName','require','英文名称必须填写！'), //默认情况下用正则进行验证
                array('Imageurl','require','图片必须上传！'), // 在新增的时候验证name字段是否唯一
            );
            $parentID=I('request.ParentID',0,'intval');   //上级分类ID
            //根据表单提交的POST数据和验证规则条件，创建数据对象
            $model=M(self::T_TABLE);
            $FormData=$model->validate($rules)->create();

            if(!$FormData){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                $exit=M(self::T_TABLE)->where(array('ID'=>array('neq',$FormData['ID']),'ParentID'=>$parentID,'Name'=>$FormData['Name'],'IsDel'=>0))->find();
                if($exit){
                    $this->ajaxReturn(0,"该上级分类下添加的子分类，名称不能相同");
                }
                $exit_EName=M(self::T_TABLE)->where(array('ID'=>array('neq',$FormData['ID']),'EName'=>$FormData['EName'],'IsDel'=>0))->find();
                if($exit_EName){
                    $this->ajaxReturn(0,"英文名称已经存在");
                }
                $data=array();  //创建新数组，用于存储保存的数据

                //只更新修改的字段
                $data=$FormData;
                $data['ParentID']=$parentID;
                $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                $data['UpdateTime']=date("Y-m-d H:i:s");
                //更新数据判断
                if($FormData['ID']>0){
                    $res=$model->where(array('ID'=>$FormData['ID']))->save($data);
                    if($res>0){
                        $this->ajaxReturn(1, '修改成功');
                    }else{
                        $this->ajaxReturn(0, '修改失败');
                    }
                }else{
                    if($result3=$model->add($data)){
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
     * @功能说明：删除分类
     * @return [type]                           [description]
     */
    public function del(){
        $mod = M(self::T_TABLE);
        //获取删除数据id (单条或数组)
        $ids = I("post.ID", '', 'trim');
        $arr=explode(',',$ids);  //转化为一维数组
        //根据选择的ID值，进行物理删除
        if( M(self::T_TABLE)->where(array('ParentID'=>array('in',$arr),'IsDel'=>0))->count()){
           $this->ajaxReturn(0,"该分类下有子分类，请先删除所有子分类后操作");
        }
        $exit=M(self::T_ITEMS)->where(array('CateID'=>array('in',$arr),'IsDel'=>0))->find();
        if($exit ){
            $this->ajaxReturn(0,"该分类正在使用中，无法删除");
        }
        $res=$mod->where(array('ID'=>array('in',$arr)))->setField('IsDel',1);  //逻辑删除
        if ($res) {
            $this->ajaxReturn(true, "删除数据成功！");
        } else {
            $this->ajaxReturn(false, "删除数据时出错！");
        }
    }
}