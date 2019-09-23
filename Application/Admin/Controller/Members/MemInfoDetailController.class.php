<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 公司电话：0532-58770968
 * 作    者：吴益超
 * 修改时间：2017-06-15 10:40
 * 功能说明：会员信息模块
 */
namespace Admin\Controller\Members;
use XBCommon;
use Admin\Controller\System\BaseController;
class MemInfoDetailController extends BaseController {

    const T_TABLE = 'mem_ed';
	
    public function index(){

		$this->display();
	}
    
	/**
	 * 后台用户管理的列表数据获取
	 * @access   public
	 * @return   object    返回json数据
	 */
    public function DataList(){
       $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $name=I('post.name','');
		 $where['isDel'] = 0;
         if($name){
         	$where['name']=array('like','%'.$name.'%');
         }
		 $sort = 'createDate desc';
         $col='';//默认全字段查询
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
				if($val['sex']==1){
					$val['sex']='男';
				}else{
					$val['sex']='女';
				}
				 if($val['houseType']=='1'){
					$val['houseType']='有房贷';
				 }elseif($val['houseType']=='2'){
					$val['houseType']='有房';
				 }else{
					$val['houseType']='无';
				 }

				 if($val['carType']=='1'){
					$val['carType']='有车贷';
				 }elseif($val['carType']=='2'){
					$val['carType']='有车';
				 }else{
					$val['carType']='无';
				 }

				 if($val['zy']=='1'){
					$val['zy']='白领';
				 }elseif($val['zy']=='2'){
					$val['zy']='公务员';
				 }elseif($val['zy']=='3'){
					$val['zy']='私企业主';
				 }else{
					$val['zy']='无业';
				 }

				 if($val['work']=='1'){
					$val['work']='6个月以内';
				 }elseif($val['work']=='2'){
					$val['work']='12个月以内';
				 }else{
					$val['work']='1年以上';
				 }

				 if($val['gjj']=='1'){
					$val['gjj']='1年以内';
				 }elseif($val['gjj']=='2'){
					$val['gjj']='1年以上';
				 }else{
					$val['work']='无公积金';
				 }

				 if($val['ysr']=='1'){
					$val['ysr']='4千以下';
				 }elseif($val['ysr']=='2'){
					$val['ysr']='4千-1万';
				 }else{
					$val['ysr']='1万以上';
				 }

				 if($val['sb']=='1'){
					$val['sb']='有';
				 }else{
					$val['sb']='无';
				 }

				 if($val['xyk']=='1'){
					$val['xyk']='无';
				 }elseif($val['ysr']=='2'){
					$val['xyk']='1万以下';
				 }else{
					$val['xyk']='1万以上';
				 }

				 if($val['bd']=='1'){
					$val['bd']='有';
				 }else{
					$val['bd']='无';
				 }
                 $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }

	   //会员导出功能
    public function exportexcel(){
       $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $name=I('post.name','');
		 $where['isDel'] = 0;
         if($name){
         	$where['name']=array('like','%'.$name.'%');
         }
		 $sort = 'createDate desc';
         $col='';//默认全字段查询
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
				if($val['sex']==1){
					$val['sex']='男';
				}else{
					$val['sex']='女';
				}
				 if($val['houseType']=='1'){
					$val['houseType']='有房贷';
				 }elseif($val['houseType']=='2'){
					$val['houseType']='有房';
				 }else{
					$val['houseType']='无';
				 }

				 if($val['carType']=='1'){
					$val['carType']='有车贷';
				 }elseif($val['carType']=='2'){
					$val['carType']='有车';
				 }else{
					$val['carType']='无';
				 }

				 if($val['zy']=='1'){
					$val['zy']='白领';
				 }elseif($val['zy']=='2'){
					$val['zy']='公务员';
				 }elseif($val['zy']=='3'){
					$val['zy']='私企业主';
				 }else{
					$val['zy']='无业';
				 }

				 if($val['work']=='1'){
					$val['work']='6个月以内';
				 }elseif($val['work']=='2'){
					$val['work']='12个月以内';
				 }else{
					$val['work']='1年以上';
				 }

				 if($val['gjj']=='1'){
					$val['gjj']='1年以内';
				 }elseif($val['gjj']=='2'){
					$val['gjj']='1年以上';
				 }else{
					$val['work']='无公积金';
				 }

				 if($val['ysr']=='1'){
					$val['ysr']='4千以下';
				 }elseif($val['ysr']=='2'){
					$val['ysr']='4千-1万';
				 }else{
					$val['ysr']='1万以上';
				 }

				 if($val['sb']=='1'){
					$val['sb']='有';
				 }else{
					$val['sb']='无';
				 }

				 if($val['xyk']=='1'){
					$val['xyk']='无';
				 }elseif($val['ysr']=='2'){
					$val['xyk']='1万以下';
				 }else{
					$val['xyk']='1万以上';
				 }

				 if($val['bd']=='1'){
					$val['bd']='有';
				 }else{
					$val['bd']='无';
				 }
                 $result['rows'][]=$val;
            }
         }
        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>会员名称</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>手机号码</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>性别</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>期望借款金额</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>身份证号</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>职业</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>工作年限</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>月收入</td>
            </tr>';

        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.$row['name'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['mobile'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['sex'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['money'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['card'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['zy'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['work'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['ysr'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'user';
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