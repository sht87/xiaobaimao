<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2018/04/13 09:36
 * 功能说明: 新闻资讯
 */
namespace Home\Controller;
class NewsController extends HomeController{

    //单页面
    public function pages(){
        $id = I('get.ID', 0,'intval');
        $pages=M('sys_simplepage')->where(array('ID'=>$id))->find();
        $this->assign(array(
            "pages"=>$pages,
            "title"=>$pages['Title'],
        ));
        $this->display();
    }

    /**
     * 购买代理常见问题
     */
    public function question(){
        $quesList=M('sys_contentmanagement')->where(array('CategoriesID'=>1,'IsDel'=>0,'Status'=>1,'IsPublish'=>1))->order('Sort asc,ID desc')->select();
        $this->assign('quesList',$quesList);
        $this->display();
    }

    /**
     * 新闻公告列表页
     */
    public function index(){
        $this->assign(array(
            "SEOTitle"=>"系统公告"
        ));
        $this->display();
    }

    /**
     * 新闻公告加载
     */
    public function indexdata(){
        //搜索条件

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 3;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        //分页
        $info=M('sys_contentmanagement')->field('ID,Title,Contents,AddTime')->where(array('CategoriesID'=>2,'IsPublish'=>1))
            ->limit($start_numbs,$per_numbs)->order('Sort asc,ID desc')->select();
        foreach ($info as $key=>$val){
            if(mb_strlen($val['Title'])>10){
                $info[$key]['Title']=mb_substr($val['Title'],0,10,'utf-8')."...";
            }
        }
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
    }

    /**
     * 公告详情
     */
    public function details(){
        $id = I('get.ID', 0,'intval');

        $pages=M('sys_contentmanagement')->where(array('ID'=>$id))->find();
        M('sys_contentmanagement')->where(array('ID'=>$id))->setInc('ViewCounk',1);
        $this->assign(array(
            "pages"=>$pages,
            "SEOTitle"=>"系统公告",
        ));
        $this->display();
    }

    //新手帮助
    public function helps(){
        $cateid=I('get.cateid','');
        $words=I('get.words','');
        $this->assign(array(
            'cateid'=>$cateid,
            'words'=>$words,
            'SEOTitle'=>'新手帮助',
            ));
        $this->display();
    }
    //获取新手帮助数据
    public function gethelpsdata(){
        //搜索条件
        $cateid=I('post.cateid','');
        $words=I('post.words','');

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 9;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        if($cateid){
            $where['CategoriesID']=array('eq',$cateid);
        }else{
            //获取新手帮助下面的所有分类数据
            $cateinfos=M('sys_contentcategories')->field('ID,Name')->where(array('ParentID'=>'3'))->select();
            //$cateids=array_column($cateinfos, 'ID');
            $cateids=array();
            foreach($cateinfos as $k=>$v){
                $cateids[]=$v['ID'];
            }
            $where['CategoriesID']=array('in',$cateids);
        }
        if($words){
            $where['Title']=array('like','%'.$words.'%');
        }
        //分页
        $infos=M('sys_contentmanagement')->field('ID,Title,Contents')->where($where)->limit($start_numbs,$per_numbs)->order('Sort asc,ID desc')->select();
        foreach($infos as $k=>&$v){
            $v['Contents']=htmlspecialchars_decode($v['Contents']);
        }
        if(empty($infos)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$infos);
        }
    }
    //价格表
    public function pricelist(){
        $priceimgs=M('sys_adcontent')->field('Name,Pic')->where(array('AdvertisingID'=>'7','Status'=>'1'))->order('Sort asc')->select();
        $this->assign(array(
            'priceimgs'=>$priceimgs,
            ));
        $this->display();
    }

}

 
 