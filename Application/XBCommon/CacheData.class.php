<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 刁洪强
 * 修改时间: 2017-06-02 14:30
 * 功能说明:缓存数据处理类库
 */
namespace XBCommon;

use Think\Model;
use Think\Controller;

class CacheData extends Controller
{
    const T_SYS_ROLE = 'sys_role';
    const T_SYS_ROLEMENU = 'sys_rolemenu';
    const T_SYS_MENU = 'sys_menu';
    const T_SYS_BASICINFO = 'sys_basicinfo';

    /**
     * 系统基本信息
     */
    public function BasicInfo(){
        if(XBCache::GetCache('BasicInfo')==null){
            $data=M(self::T_SYS_BASICINFO)->find();
            XBCache::Insert('BasicInfo',$data);
        }
        return XBCache::GetCache('BasicInfo');
    }

    /**
     * 省市区县数据
     */
    public function Areas(){
        if(XBCache::GetCache('Areas')==null) {
            $P = M('sys_areas')->where('Pid = 1 AND Status=1')->order("Sort")->select();
            $areas = array();
            foreach ($P as $val) {
                $arr2 = array();
                $c = M('sys_areas')->where("Pid=" . intval($val['ID']) . ' AND Status=1')->select();
                if ($c) {
                    foreach ($c as $v) {
                        $t = M('sys_areas')->where("Pid=" . intval($v['ID']) . ' AND Status=1')->select();
                        if ($t) {
                            $v['children'] = $t;
                        }
                        $arr2[] = $v;
                    }
                    $val['children'] = $arr2;

                }
                $areas[] = $val;
            }
            XBCache::Insert('Areas',$areas);
        }
        return XBCache::GetCache('Areas');
    }

    /**
     * 左侧菜单数据
     */
    public function LeftMenu(){
        $LeftMenu=XBCache::GetCache('LeftMenu');
        $RoleID=$_SESSION['AdminInfo']['RoleID'];
        //判断当前角色的左侧缓存菜单是否存在
        if(empty($LeftMenu[$RoleID])){
            //若当前角色的左侧菜单缓存不存在，则更新整个左侧菜单的缓存 [缓存插入是键值关系,所以只能重新整个插入]
            $RoleIDS=M(self::T_SYS_ROLE)->where(array('Status'=>1,'IsDel'=>0))->field('ID')->select();
            $RoleMenus=array();
            foreach ($RoleIDS as $role){
                //为每个角色组合左侧菜单
                $ParentMenulist=M(self::T_SYS_ROLEMENU)->where(array('ParentID'=>0,'RoleID'=>$role['ID']))->select();
                $menu=array();
                foreach ($ParentMenulist as $key=>$val){
                    //组装一级菜单名称和图标
                    $info=M(self::T_SYS_MENU)->where(array('ID'=>$val['MenuID']))->find();
                    $menu[$key]['name']=$info['Name'];
                    $menu[$key]['icon']=$info['Icon'];
                    //组装一级菜单下的二级菜单 [为每个当前循环到的角色组装]
                    $menu[$key]['next_menu']=get_menu_list(self::T_SYS_ROLEMENU,array('ParentID'=>$val['MenuID'],'RoleID'=>$role['ID']));
                }
                $RoleMenus[$role['ID']]=$menu;
            }
            //组装完毕,更新缓存
            XBCache::Insert('LeftMenu',$RoleMenus,60);
            //返回当前角色对应的左侧菜单
            return $RoleMenus[$RoleID];
        }else{
            //若当前角色的左侧菜单缓存存在,则直接返回
            return $LeftMenu[$RoleID];
        }
    }

    /**
     * @功能说明：权限菜单按钮
     * @return mixed|null
     */
    public function RoleMenu($EnName){

        $RoleID = $_SESSION['AdminInfo']['RoleID'];
        $MenuEnName = str_replace("/","_",$EnName).'_Menu_'.$RoleID;

        $cache = XBCache::GetCache($MenuEnName);

        if(empty($cache)) {
            $RoleID = $_SESSION['AdminInfo']['RoleID'];
            $MenuID = M("sys_menu")->where("Url='%s'", $EnName)->getField("ID");
            $ButtonID = M("sys_rolemenu")->where("RoleID=%d and MenuID=%d", $RoleID, $MenuID)->getField("ButtonID");
            $ButtonStr = substr($ButtonID, 1, strlen($ButtonID));
            $ButtonArr = explode(",", $ButtonStr);
            $list = array();
            foreach ($ButtonArr as $id) {
                $arr = M("sys_operationbutton")->find($id);
                $list[] = $arr;
            }
            XBCache::Insert($MenuEnName,$list);
            $cache = $list;
        }
        return  $cache;
    }

    /**
     * 功能说明:权限菜单及快捷菜单
     * @param $EnName
     * @return mixed|null
     */
    public function RoleBottom($EnName){
		$RoleID = $_SESSION['AdminInfo']['RoleID'];
        $MenuEnName = str_replace("/","_",$EnName).'_Bottom_'.$RoleID;
		
		$cache = XBCache::GetCache($MenuEnName);

        if(empty($cache)) {
            
            //获取菜单ID
            $MenuID = M("sys_menu")->where("Url='%s'", $EnName)->getField("ID");
            //获取相应权限的数据
            $ButtonID = M("sys_rolemenu")->where("RoleID=%d and MenuID=%d", $RoleID, $MenuID)->getField("ButtonID");
            $ButtonStr = substr($ButtonID, 1, strlen($ButtonID));
            $ButtonArr = explode(",", $ButtonStr);
            //根据权限 拼接操作js现在内容
            $html = "<script type='text/javascript'>\n\r";
            $html .= "function OpenWin(Type) {\n\r";
            $html .= "   switch (Type) {\n\r";
            //列表右键控制
            $right_str = '<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">' . "\n\r";

            foreach ($ButtonArr as $id) {
                    //拼接一写控制窗口的方法
                    $str = "";
                    //获取某一功能的一些数据
                    $arr = M("sys_operationbutton")->find($id);
                    if ($arr['EName'] == 'Separator') {
                        $right_str .= "<div class=\"menu-sep\"></div>\n\r";
                    } else {
                        $right_str .= "<div data-options=\"name:'" . $arr['EName'] . "'\">" . $arr['Name'] . "</div>\n\r";
                    }
                    if(empty($MenuID) || empty($id)){
                        //菜单地址区分大小写，填写需注意
                        return '根据URL未查询到菜单ID,请检查菜单地址大小写是否正确！';
                    }
                    $db=M("sys_menubutton");
                    $MenubuttonArr = $db->where("MenuID=%d and ButtonID=%d", $MenuID, $id)->find();

                    if ($MenubuttonArr['Width']) {//宽度
                        $str .= ",'width':'" . $MenubuttonArr['Width'] . "'";
                    }
                    if ($MenubuttonArr['Height']) {//高度
                        $str .= ",'height':'" . $MenubuttonArr['Height'] . "'";
                    }
                    if ($MenubuttonArr['ButtonSaveURL']) {//保存跳转的url
                        $str .= ", 'save': {'url': '" . $MenubuttonArr['ButtonSaveURL'] . "' }";
                    }

                    if ($arr['EName'] == 'del') {//删除  有删除口令
                        $str1 = ",'token': $('input[name=__RequestVerificationToken]').val()";
                    }
                    //添加于编辑基本通用一个页面  如有特殊情况请特殊对待
                    if ($arr['EName'] == 'add') {
                        $add = 'edit';
                    } else {
                        $add = $arr['EName'];
                    }
                    if ($arr['EName'] != 'Separator') {
                        if ($arr['EName'] == 'del') {//删除  有删除口令
                            $html .= "        case '" . $arr['EName'] . "':$.XB.open({ 'type':'" . $arr['EName'] . "','openmode':'" . $MenubuttonArr['OpenMode'] . "'" . $str1 . ", 'dialog': { 'url':'/admin.php/" . CONTROLLER_NAME . "/" . $add . "/', 'title': '" . $arr['Name'] . "'" . $str . " } });break;\n\r";
                        } else {
                            if ($MenubuttonArr['OpenMode'] == 2 || $MenubuttonArr['OpenMode'] == 6 || $MenubuttonArr['OpenMode'] == 3) {   //特殊的跳转打开方式
                                $html .= "        case '" . $arr['EName'] . "':$.XB.open({ 'type':'" . $arr['EName'] . "','openmode':'" . $MenubuttonArr['OpenMode'] . "', 'dialog': { 'url': '" . $add . "/', 'title': '" . $arr['Name'] . "'" . $str . " } });break;\n\r";
                            } else {
                                if ($MenubuttonArr['ButtonURL']) {   //有跳转地址的
                                    $html .= "        case '" . $arr['EName'] . "':$.XB.open({ 'type':'" . $arr['EName'] . "','openmode':'" . $MenubuttonArr['OpenMode'] . "', 'dialog': { 'url': '/admin.php/" . $MenubuttonArr['ButtonURL'] . "/', 'title': '" . $arr['Name'] . "'" . $str . " } });break;\n\r";
                                } else {                             //默认跳转地址
                                    $html .= "        case '" . $arr['EName'] . "':$.XB.open({ 'type':'" . $arr['EName'] . "','openmode':'" . $MenubuttonArr['OpenMode'] . "', 'dialog': { 'url': '/admin.php/" . CONTROLLER_NAME . "/" . $add . "/', 'title': '" . $arr['Name'] . "'" . $str . " } });break;\n\r";
                                }
                            }
                        }
                    }
            }
            $right_str .= "<div class=\"menu-sep\"></div>\n\r";
            $right_str .= "<div data-options=\"name:'all'\">全选</div>\n\r";
            $right_str .= "<div data-options=\"name:'clearall'\">全不选</div>\n\r";
            $right_str .= "<div data-options=\"name:'anti'\">反选</div>\n\r";
            $right_str .= "<div class=\"menu-sep\"></div>\n\r";
            $right_str .= "<div>退出</div>\n\r";
            $right_str .= '</div>';
            $html .= "        case 'all': $.XB.open({ 'type': 'all'});break;\n\r        case 'clearall': $.XB.open({ 'type': 'clearall'});break;\n\r        case 'anti': $.XB.open({ 'type': 'anti'});break;}\n\r}\n\r";
            $html .= "</script>\n\r";
            $data=$html.$right_str;
            XBCache::Insert($MenuEnName,$data);
			
			$cache = $data;
        }
        return $cache;

    }


    /**
     * 功能说明:更新系统所有缓存
     */
    public function UpdateCache(){
        XBCache::Remove('BasicInfo');
        $this->ClearMenu();
        $this->UpdateArea();
        $this->BasicInfo();
    }

    /**
     * 功能说明:清除权限菜单缓存
     */
    public function ClearMenu(){
        XBCache::Remove('LeftMenu');
        $this->LeftMenu();
    }

    public function ClearRoleMenu(){
        $con['IsDel']=0;
        $con['Status']=1;
        $con['Url']=array('NEQ','#');

        $list=M('sys_menu')->where($con)->field('Url')->select();
        $RoleID = $_SESSION['AdminInfo']['RoleID'];
        foreach ($list as $val){
            $EnName=$val['Url'];
            $MenuTopName = str_replace("/","_",$EnName).'_Menu_'.$RoleID;
            $MenuBottomName = str_replace("/","_",$EnName).'_Bottom_'.$RoleID;
            XBCache::Remove($MenuTopName);
            XBCache::Remove($MenuBottomName);
        }
    }

    /**
     * 功能说明:更新当前区域缓存信息
     */
    public function UpdateArea(){
        XBCache::Remove('Areas');
        $this->Areas();
    }

    /**
     * 获取平台设置
     */
    public function GetPlatSet(){
        if(XBCache::GetCache('PlatSet')==null){
            $data=M('platform_setting')->find();
            XBCache::Insert('PlatSet',$data);
        }
        return XBCache::GetCache('PlatSet');
    }


}