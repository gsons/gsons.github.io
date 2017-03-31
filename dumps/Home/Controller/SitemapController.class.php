<?php
namespace Home\Controller;
 
class SitemapController extends PublicController{
    public function Index(){
        if(S("SITEMAP_DATAS")){
            $this->assign("category",S("SITEMAP_DATAS"));
            $this->display("Index/Sitemap");
            exit();
        }
        $ids[]=array("id"=>1366,"name"=>"走进郁南","link"=>U("About/index"));
        $ids[]=array("id"=>1367,"name"=>"新闻中心","link"=>U("News/Index"));
        $ids[]=array("id"=>1368,"name"=>"政务公开","link"=>U("Open/Index"));
        $ids[]=array("id"=>2092,"name"=>"办事服务","link"=>U("Hall/Index"));
        $ids[]=array("id"=>1408,"name"=>"政民互动","link"=>U("Interact/Index"));
        $ids[]=array("id"=>1540,"name"=>"招商引资","link"=>U("Economy/Index"));
        foreach ($ids as $id) {
            $res=M("ArticleType")->field("name,id")->where('pid='.$id["id"])->select();
            foreach ($res as &$v) {
                $v["link"]=U('Public/Lists',array('id'=>$v['id']));
            }
           $category[]=array("name"=>$id["name"],"link"=>$id["link"],"child"=>$res);
        }

        $list = M('special')->where(array('department_id'=>array('GT',0),'status'=>array('GT',0)))->field('id,title as name')->order('id desc')->select();
         foreach ($list as $v) {
             $res=M("ArticleType")->field("name,id")->where('special_id='.$v["id"])->select();
              foreach ($res as &$sv) {
                $sv["link"]=__ROOT__."/special.php/Substation/lists/id/".$sv["id"].".html";
            }
            $category[]=array("name"=>$v["name"],"link"=>__ROOT__."/special.php/Substation/Index/id/".$v["id"].".html","child"=>$res);
        }
        S("SITEMAP_DATAS", $category,3*24*60*60);
    	$this->assign("category",$category);
    	$this->display("Index/Sitemap");
    }
}   
