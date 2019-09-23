<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/4/23 9:28
 * 功能说明: 新闻接口控制器
 */
 
 namespace Api\Controller\Home;
 use Api\Controller\Core\BaseController;

 class NewsController extends BaseController{

     const T_PAGES='sys_simplepage';
     const T_NOTICE='sys_contentmanagement';

     /**
      * @功能说明: 获取 单页 信息
      * @传输方式: get
      * @提交网址: /Home/News/getpages
      * @提交信息:  client=android&ver=1.1&package=android.ceshi&id=1
      * @提交信息说明:  id:单页面的ID,如注册协议，用户协议，关于我们，产品介绍等
      * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
      */
     public function getpages(){
         $para=I('get.');
         $pages=M(self::T_PAGES)->field('Title,Contents')->where(array('ID'=>$para['id']))->find();
         if(!$pages){
             exit(json_encode(array('result'=>0,'message'=>'没有更多数据！')));
         }
         $pages['Contents']=htmlspecialchars_decode($pages['Contents']);
         $pages['Contents']=str_replace('src="/Upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$pages['Contents']);
         $pages['Contents']=str_replace('src="/upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$pages['Contents']);
         M(self::T_PAGES)->where(array('ID'=>$para['id']))->setInc('ViewCounk',1);
         exit(json_encode(array('result'=>1,'message'=>'查询成功','data'=>$pages)));
     }

     /**
      * @功能说明: 获取 系统公告和常见问题 列表
      * @传输方式: get
      * @提交网址: /Home/News/getnewslist
      * @提交信息:  client=android&ver=1.1&package=android.ceshi&cid=2&page=0&rows=10
      * @提交信息说明:    cid:类型  1常见问题  2系统公告   page:当前页码数，默认值为0  rows:每页展示的数量，默认值为10
      * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
      */
     public function getnewslist(){
         $para=I('get.');
         $page=$para['page']?$para['page']:0;
         $rows=$para['rows']?$para['rows']:10;
         if($para['cid']==1){
             $field='ID,Title,Contents';
         }else{
             $field='ID,Title,AddTime';
         }
         $List=M(self::T_NOTICE)->field($field)->where(array('CategoriesID'=>$para['cid'],'IsPublish'=>1))->order('Sort asc,ID desc')->limit($page*$rows,$rows)->select();
         if(empty($List)){
             exit(json_encode(array('result'=>0,'message'=>"暂没有可展示的数据")));
         }
         if($para['cid']==1){
            foreach ($List as $key=>$val){
                 $List[$key]['Contents']=htmlspecialchars_decode($val['Contents']);
            }
         }
         $data=array(
             'result'=>1,
             'message'=>'success',
             'data'=>$List
         );
         exit(json_encode($data));
     }

     /**
      * @功能说明: 获取 系统公告 详情
      * @传输方式: get
      * @提交网址: /Home/News/getnotice
      * @提交信息:  client=android&ver=1.1&package=android.ceshi&id=1
      * @提交信息说明:    id:系统公告列表ID
      * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
      */
     public function getnotice(){
         $para=I('get.');
         $Info=M(self::T_NOTICE)->field('Title,AddTime,Contents')->where(array('ID'=>$para['id'],'CategoriesID'=>2))->find();
         if(empty($Info)){
             exit(json_encode(array('result'=>0,'message'=>"传递的参数id不正确")));
         }
         $Info['Contents']=htmlspecialchars_decode($Info['Contents']);
         $Info['Contents']=str_replace('src="/upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$Info['Contents']);
         $Info['Contents']=str_replace('src="/Upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$Info['Contents']);

         $data=array(
             'result'=>1,
             'message'=>'success',
             'data'=>$Info
         );
         M(self::T_NOTICE)->where(array('ID'=>$para['id']))->setInc('ViewCounk',1);
         exit(json_encode($data));
     }

     /**
      * @功能说明: 获取 新手帮助 列表
      * @传输方式: get
      * @提交网址: /Home/News/newerhelps
      * @提交信息:  client=android&ver=1.1&package=android.ceshi&cateid=2&words='你好'&page=0&rows=10
      * @提交信息说明:    cateid:类型  4融客店  5注册登录 6工资结算 7推荐有钱 words:搜索的关键字  page:当前页码数，默认值为0  rows:每页展示的数量，默认值为10
      * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
      */
     public function newerhelps(){
         $para=I('get.');
         $page=$para['page']?$para['page']:0;
         $rows=$para['rows']?$para['rows']:10;
         //搜索条件
        $cateid=$para['cateid'];
        $words=$para['words'];

        $where=array();
        if($cateid){
            $where['CategoriesID']=array('eq',$cateid);
        }else{
            //获取新手帮助下面的所有分类数据
            $cateinfos=M('sys_contentcategories')->field('ID,Name')->where(array('ParentID'=>'3'))->select();
            $cateids=self::i_array_column($cateinfos, 'ID');
            $where['CategoriesID']=array('in',$cateids);
        }
        if($words){
            $where['Title']=array('like','%'.$words.'%');
        }
        //分页
        $infos=M('sys_contentmanagement')->field('ID,Title,Contents')->where($where)->limit($page*$rows,$rows)->order('Sort asc,ID desc')->select();
        foreach($infos as $k=>&$v){
            $v['Contents']=htmlspecialchars_decode($v['Contents']);
        }

         $data=array(
             'result'=>1,
             'message'=>'success',
             'data'=>$infos
         );
         exit(json_encode($data));
     }
	function i_array_column($input, $columnKey, $indexKey=null){
		if(!function_exists('array_column')){ 
			$columnKeyIsNumber  = (is_numeric($columnKey))?true:false; 
			$indexKeyIsNull            = (is_null($indexKey))?true :false; 
			$indexKeyIsNumber     = (is_numeric($indexKey))?true:false; 
			$result                         = array(); 
			foreach((array)$input as $key=>$row){ 
				if($columnKeyIsNumber){ 
					$tmp= array_slice($row, $columnKey, 1); 
					$tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null; 
				}else{ 
					$tmp= isset($row[$columnKey])?$row[$columnKey]:null; 
				} 
				if(!$indexKeyIsNull){ 
					if($indexKeyIsNumber){ 
					  $key = array_slice($row, $indexKey, 1); 
					  $key = (is_array($key) && !empty($key))?current($key):null; 
					  $key = is_null($key)?0:$key; 
					}else{ 
					  $key = isset($row[$indexKey])?$row[$indexKey]:0; 
					} 
				} 
				$result[$key] = $tmp; 
			} 
			return $result; 
		}else{
			return array_column($input, $columnKey, $indexKey);
		}
	}
     /**
      * @功能说明: 获取 价格列表
      * @传输方式: get
      * @提交网址: /Home/News/pricelist
      * @提交信息:  client=android&ver=1.1&package=android.ceshi
      * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
      */
     public function pricelist(){
         $priceimgs=M('sys_adcontent')->field('Name,Pic')->where(array('AdvertisingID'=>'7','Status'=>'1'))->order('Sort asc')->select();
         if($priceimgs){
           foreach($priceimgs as $k=>$v){
               $priceimgs[$k]['Pic']='http://'.$_SERVER['HTTP_HOST'].$v['Pic'];
           }
         }
         $data=array(
             'result'=>1,
             'message'=>'success',
             'data'=>$priceimgs
         );
         exit(json_encode($data));
     }

 }
 