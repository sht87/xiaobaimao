  <?php


  /**
   * 管理员操作日志
   * @param string $modle 数据模型用于实例化
   * @param string $source_data 源数据
   * @param string $update_date  新数据或返回数据
   * @param string $Parameter  c传入参数 或添加返回ID
   * @param $type              类型   如：添加  删除---
   */
  function admin_opreat_log($source_data='',$update_date='',$parameter='',$type){
      //组装要保存的操作数据
      $data=array();
      $data['IP']    = '172.0.0.1';//session('IP');//ip
      $data['UserName'] ='admin'; //session('Admin');//用户名
      $data['IPCity']  = '合肥';("IPCity");//ip所在地
      $data['TrueName']= 'luheng';('TrueName');//
      $data['UserID']  =2;('RoleID');//用户ID
      $data['DateTime']=date('Y-m-d H:i:s',time());//操作时间
      $data['ControllerName']=CONTROLLER_NAME;
      $data['ActionName']=ACTION_NAME;
      $data['AreaName']=MODULE_NAME;
      $data['BackData']=json_encode($update_date);
      $data['SourceData']=json_encode($source_data);
      $data['Parameter']=json_encode($parameter);
      $data['Type']=$type;
      $data['URL']=C("SERVER_NAME").__SELF__;
      M("sys_log")->add($data);
  }


  /**
   * ############################# 与后台菜单有关 ################################
   */

  /**
   * 时间戳转换成日期
   * @return bool|string
   */
  function createNowDate(){
      return date("Y-m-d H:i:s",time());
  }

  function getSession(){
      session('Admin',1);
      return session('Admin');
  }



  /**
   * 菜单管理树结构数组组装2  用于获取菜单表树结构 展示 （如下拉框显示)
   * @param $table string 表名
   * @param $where  string 条件
   * @param $where  string 排序
   * @return array|bool
   */
  function has_children_menu($table,$where,$sort_order='',$i=0){
      //获取菜单表的第一层数据
      $arr=M($table)->where($where)->order($sort_order)->select();
      if($arr){
          //遍历第一层数据
          $two_arr=array();
          foreach($arr as  $key=>$val){

              //循环处理树结构数据
              $row=array(
                  'id' 	    => $val['ID'],
                  'text'    	=> $val['Name'],
                  'iconCls' 	=> $val['Icon'],
                  'state'     =>'closed',
              );
              $two_arr[$key]=$row;
              $two_arr[$key]['children'] = has_children_menu($table,"ParentID=".$val['ID'],$sort_order,0);
              if(empty($two_arr[$key]['children'])){
                  unset($two_arr[$key]['children']);
                  unset($two_arr[$key]['state']);

              }

          }
          return $two_arr;

      }else{
          return false;
      }
  }

    /**
     * 分类管理树结构数组  用于获取菜单表树结构 展示 （如下拉框显示)
     * @param $table string 表名
     * @param $where  string 条件
     * @param $where  string 排序
     * @return array|bool
     */
    function has_children_cate($table,$where,$sort_order='',$i=0){
      //获取菜单表的第一层数据
      $arr=M($table)->where($where)->order($sort_order)->select();
      if($arr){
        //遍历第一层数据
        $two_arr=array();
        foreach($arr as  $key=>$val){
              //循环处理树结构数据
            $res=has_children_cate($table,"ParentID=".$val['ID'],$sort_order,0);
              $row = array(
                  'id'      => $val['ID'],
                  'text'      => $val['Name'],
                  'state'   =>$res ? 'closed' : 'open',
                  'children'  => $res ? $res : '',
                );
              $two_arr[$key]=$row;
              if(empty($two_arr[$key]['children'])){
                unset($two_arr[$key]['children']);
              }
          }
           return $two_arr;
        }else{
          return false;
        }
    }

  /**
    *用于页面菜单展示
   * @param $table   表明
   * @param $where   条件
   * @param string   $data  辅助条件
   * @return   array
   */
  function get_menu_list($table,$where,$data=''){
      $Menu_Lsit = M($table)->where($where)->select();
      $menu_arr=array();
      if($Menu_Lsit){
          foreach($Menu_Lsit as $key=>$v){//循环获取二级菜单信息
              $menu_info=M("sys_menu")->where(array('ID'=>$v['MenuID'],'Status'=>1,'IsDel'=>0))->order('Sort asc,ID asc')->find();
              if($menu_info){
                  $menu_arr[$key]['name']=$menu_info['Name'];
                  $menu_arr[$key]['icon']=$menu_info['Icon'];
                  $menu_arr[$key]['url']=$menu_info['Url'];

                  $where['ParentID']=$v['MenuID'];
                  $where['RoleID']=$v['RoleID'];
                  $menu_arr[$key]['next_menu']=get_menu_list($table,$where,'');
                  if(empty($menu_arr[$key]['next_menu'])){
                      unset($menu_arr[$key]['next_menu']);
                  }
              }

          }
        }
      return $menu_arr;
  }



    /**
     * 获取分类子菜单
     * @param $where string 拼接搜索where条件
     * @param $table string 需要处理的模型
     * @return $arr
     */

    function catemenu($table,$where,$sort_order=''){
        //实例化模型
        $arr = M($table)->where($where)->order($sort_order)->select();
        if($arr){
          //遍历第一层数据
            $two_arr=array();
            foreach($arr as $val){
              //菜单状态
                //循环处理树结构数据
                $two_arr[]=array(
                    'Name'      => $val['Name'],
                    'ID'      => $val['ID'],
                    'Sort'      => $val['Sort'],
                    'Status'  => $val['Status'],
                    'IsRec'   => $val['IsRec'],
                    'ColumnType'=> $val['ColumnType']==1?"栏目":"单页",
                    'AllowDelete' => $val['AllowDelete']==1?"允许":"禁止",
                    'state' => 'open',
                    'children'   =>catemenu($table,"ParentID=".$val['ID'],$sort_order) ? catemenu($table,"ParentID=".$val['ID'],$sort_order) :''
                );
            }
            return $two_arr;
        }else{
            return false;
        }
    }


  /**
   * 获取分类子菜单
   * @param $where string 拼接搜索where条件
   * @param $table string 需要处理的模型
   * @return $arr
   */

  function credential($table,$where,$sort_order=''){
      //实例化模型
      $arr = M($table)->where($where)->order($sort_order)->select();
      if($arr){
          //遍历第一层数据
          $two_arr=array();
          foreach($arr as $val){
              //菜单状态
              //循环处理树结构数据

              $res=credential($table,"ParentID=".$val['ID'],$sort_order);
              $two_arr[]=array(
                  'Name'      => $val['Name'],
                  'ID'      => $val['ID'],
                  'Sort'      => $val['Sort'],
                  'Status'  => $val['Status'],
                  'state'   =>$res ? 'closed':'open',
                  'children'   =>$res ? $res :''
              );
          }
          return $two_arr;
      }else{
          return false;
      }
  }

    /**
     * 获取分类子菜单
     * @param $where string 拼接搜索where条件
     * @param $table string 需要处理的模型
     * @return $arr
     */

    function catemenu1($table,$where){
        //实例化模型
        $arr = M($table)->where($where)->select();
        if($arr){
          //遍历第一层数据
            $two_arr=array();
            foreach($arr as $val){
                //循环处理树结构数据
                if($val['ColumnType']==1){
                    //栏目
                    $two_arr[]=array(
                        'id'      => $val['ID'],
                        'text'      => $val['Name'],
                        'attributes' => array('category' => 'C'),
                        'state' =>  'open',
                        'children'   =>catemenu1($table,"ParentID=".$val['ID']) ? catemenu1($table,"ParentID=".$val['ID']) :''
                    );
                }else{
                    //单页
                    $two_arr[]=array(
                        'id'      => $val['ID'],
                        'text'      => $val['Name'],
                        'attributes' => array('category' => 'N'),
                        'state' =>  'open',
                        'children'   =>catemenu1($table,"ParentID=".$val['ID']) ? catemenu1($table,"ParentID=".$val['ID']) :''
                    );
                }

            }
            return $two_arr;
        }else{
            return false;
        }
    }

  /**
   * @功能说明: 判断后台管理员操作权限，精确到按钮。
   * @return bool
   */
  function is_permission(){
      //过滤特殊的方法名称,只默认小写
      $SpecialList=array(
          'index',
		  'bindchannelsave',
          'shows',
          'datalist',
          'save',
          'loadajax',
          'rolemenusave',
          'integraldetail',
          'balancedetail',
          'datatree',
          'list',
          'lists',
          'refreshcache',
          'icon',
          'menutree',
          'menubutton',
          'uploader',
          'getarea',
          'modifypwd',
          'perinfor',
          'uploadbatch',
          'upload',
          'map',
          'allpro',
          'allcity',
          'allcount',
          'auditsave',
          'handledone',
          'followdone',
          'sendjuan',
          'users',
          'userdata',
          'getusers',
          'getuserdatalist',
          'getcourses',
          'getchapter',
          'getuserdata',
          'send',
          'orderdetail',
          'check',
          'data_import',
          'ajaxscore',
          'getzyinfo',
          'getmajors',
          'getnotices',
          'getlastcate',
          'getcate',
          'gettype',
          'getfee',
          'getbitype',
          'getobj',
          'getjktimes',
          'home',
          'sendwxmsg',
          'outimgs',
          'getsubjects',
		'itempricesave',
		  'ddjtprice'
      );

      if(in_array(strtolower(ACTION_NAME),$SpecialList)){
          return true;
      }else{
          //此处这样写,必须要求方法名为index
          $MenuUrl =CONTROLLER_NAME;
          $where['Url']=array('like',$MenuUrl.'%');
          $MenuID=M('sys_menu')->where($where)->getField('ID');
          if(!empty($MenuID)){
              //如果寻找到了菜单URL,继续寻找权限当前用户在此菜单上分配了哪些按钮！
              $RoleID=$_SESSION['AdminInfo']['RoleID']; //获得当前角色ID
              $ButtonStr=M('sys_rolemenu')->where(array('RoleID'=>$RoleID,'MenuID'=>$MenuID))->getField('ButtonID');
              //去除首尾逗号，并根据逗号分隔拆分为一个数组
              $ButtonIDS=explode(',',trim($ButtonStr,','));
              foreach ($ButtonIDS as $val){
                  $ButtonName=M('sys_operationbutton')->where(array('ID'=>$val))->getField('EName');
                  if(strtolower($ButtonName)=='add' && strtolower(ACTION_NAME)=='edit' && I("request.ID",0,'intval')==0){
                      //因添加方法使用的是edit而非add，它属于一个特殊的方法
                      return true;
                  }
                  if(!empty($ButtonName) && $ButtonName==ACTION_NAME){
                      //操作的方法和分配的权限相符
                      return true;
                  }
              }
              //循环结束依然没有找到相符的权限
              return false;
          }else{
              //如果没有找到菜单URL
              $ButtonUrl=CONTROLLER_NAME.'/'.ACTION_NAME;
              $tj['ButtonURL']=array('like','%'.$ButtonUrl);
              $tj['ButtonSaveURL']=array('like','%'.$ButtonUrl);
              $tj['_logic']='OR';
              $ExistsUrl=M('sys_menubutton')->where($tj)->count();
              if($ExistsUrl){
                  return true;
              }else{
                  return false;
              }

          }
      }

  }

  /**
   * @功能说明:根据类型名称,获取状态列表
   * @param $ename
   * @return mixed|null
   */
  function Get_Status_List($ename){
      if(!empty($ename)){
          $dictype=M('sys_dictype')->where(array('EName'=>$ename))->find();
          if(!empty($dictype)){
              $result=M('sys_dictionary')->where(array('DictType'=>$dictype['ID']))->field('DictName')->select();
              if(!empty($result)){
                  return $result;
              }
          }
      }
      return null;
  }

  /**
   * @功能说明:根据类型名称和参数值,获取状态名称
   * @param $ename
   * @return mixed|null
   */
  function Get_Status_Val($ename,$val){
      if(!empty($ename)){
          $dictype=M('sys_dictype')->where(array('EName'=>$ename))->find();
          if(!empty($dictype) && !is_null($val)){
              $dict=M('sys_dictionary')->where(array('DictType'=>$dictype['ID'],'DictValue'=>$val))->find();
              if(!empty($dict)){
                  return $dict['DictName'];
              }
          }
      }
      return null;
  }

  /**
   * @功能说明:根据角色ID,获取管理员名称
   * @param $val
   * @return null
   */
  function Get_OperatorName($val){
      if(!empty($val)){
          $user=M('sys_administrator')->where(array('ID'=>$val))->find();
          if(!empty($user)){
              return $user['UserName'];
          }
      }
      return null;
  }

  /**
   * @功能说明:后台操作日志自动记录
   */
  function record_operation_log(){

      $operator=$_SESSION['AdminInfo']['RoleName'].':'.$_SESSION['AdminInfo']['TrueName'];  //操作者
      $column_name=null;  //操作栏目
      $button_name=null;  //操作按钮
      if(!is_null(CONTROLLER_NAME)){
          $con['Url']=array('like',CONTROLLER_NAME.'%');
          $con['IsDel']=0;
          $con['Status']=1;
          $controller_data=M('sys_menu')->where($con)->find();
          if(!empty($controller_data)){
              $column_name=$controller_data['Name'];
          }
      }

      if(!is_null(ACTION_NAME)){
          $con['EName']=ACTION_NAME;
          $con['IsDel']=0;
          $con['Status']=1;
          $button_data=M('sys_operationbutton')->where($con)->find();
          if(!empty($button_data)){
              if($button_data['EName']=='edit' && is_null(I('request.ID'))){
                  $button_name='添加';
              }else{
                  $button_name=$button_data['Name'];
              }
          }
      }

      if(!empty($column_name) && !empty($button_name)){
          $data=array();
          $data['UserID']=$_SESSION['AdminInfo']['AdminID'];
          $data['UserName']=$_SESSION['AdminInfo']['Admin'];
          $data['TrueName']=$_SESSION['AdminInfo']['TrueName'];
          $data['DateTime']=date('Y-m-d H:i:s',time());
          $data['IP']=get_client_ip();
          $data['IPCity']=ip_to_address(get_client_ip());
          $data['URL']='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/admin.php/'.CONTROLLER_NAME;
          $data['ControllerName']=ACTION_NAME;
          $data['Des']=$operator.',在 ['.$column_name.'] 的栏目中执行了 ['.$button_name.'] 操作！';
          $data['Type']=$button_name;
          $model=M('sys_log');
          $model->add($data);
      }
  }
  function apimenu($table,$where,$sort_order=''){
      //实例化模型
      $arr = M($table)->where($where)->order($sort_order)->select();
      if($arr){
          //遍历第一层数据
          $two_arr=array();
          foreach($arr as $val){
              //菜单状态
              //循环处理树结构数据
              $two_arr[]=array(
                  'Name'      => $val['Name'],
                  'ID'      => $val['ID'],
                  'Sort'      => $val['Sort'],
                  'Status'  => $val['Status'],
                  'UpdateTime'  => $val['UpdateTime'],
                  'state' => 'open',
                  'children'   =>apimenu($table,"ParentID=".$val['ID'],$sort_order) ? catemenu($table,"ParentID=".$val['ID'],$sort_order) :''
              );
          }
          return $two_arr;
      }else{
          return false;
      }
  }
  function common_package(){
      $all = M('version')->where('Status=1')->select();
      $result = array();
      foreach ($all as $k => $val){
          if($val['Client'] == 'android'){
              $result['android'][$val['Ver']] = $val['Package'];
          }elseif($val['Client'] == 'ios'){
              $result['ios'][$val['Ver']] = $val['Package'];
          }
      }
      F('Common_Package',$result);
  }

  function has_mem($id)
{
      if($id){
          $result = M('mem_info')->where(array('Referee'=>$id,'IsDel'=>0))->find();

          if($result){
              return true;
          }else{
              return false;
          }
      }else{
          return false;
      }
}

  /**
   * 列表插件分页及排序
   * @param   $sort_parm   string  设置列表单的默认排序  默认为ID DESC(倒序)
   * @return array
   */
  function listPage($sort_parm='ID desc'){
      //排序及分页
      $rows=I("post.rows",20,'intval');
      $page=I("post.page",1,'intval');
      $sort=I("post.sort",'','trim');
      $order=I("post.order",'','trim');
      //分页条件设置
      $limit = sprintf('%d,%d', ($page - 1) * $rows, $rows);
      //排序
      if($sort && $order){
          $sort_order=$sort." ".$order;
      }else{
          $sort_order=$sort_parm;
      }
      $list[]=$limit;
      $list[]=$sort_order;
      return $list;exit;
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
              $row = array(
                  'id' => $val['ID'],
                  'text' => $val['Name'],
                  'children' =>get_cate_tree($table, $map."ParentID=" . $val['ID'], $sort) ? get_cate_tree($table, $map."ParentID=" . $val['ID'], $sort) : ''
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

  function has_children_cate1($table,$where,$sort_order='',$i=0){
      //获取菜单表的第一层数据

      if($i > 1){
          return array();
      }
      $i++;
      $arr=M($table)->where($where)->order($sort_order)->select();

      if($arr){
          //遍历第一层数据
          $two_arr=array();
          foreach($arr as  $key=>$val){

              //循环处理树结构数据
              $where['ParentID'] = $val['ID'];
              $row = array(
                  'id'      => $val['ID'],
                  'text'      => $val['Name'],
                  'i'      => $i,
                  //'children'  =>has_children_cate1($table,$where,$sort_order,$i)
              );
              $two_arr[$key]=$row;

              if(empty($two_arr[$key]['children'])){
                  unset($two_arr[$key]['children']);
              }
          }
          return $two_arr;
      }else{
          return false;
      }
  }
