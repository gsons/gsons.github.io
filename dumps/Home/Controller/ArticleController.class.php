<?php
namespace Home\Controller;

class ArticleController extends PublicController{

    public function Lists($id = 0){
        $id = $id ? $id : I('id');
        empty($id) && $this->redirect('Index/index');
        $child_tree = get_child_category('ArticleType','id','name','pid',$id,array('special_id'=>0));

        $p = I('p',1);
        $num = 20;
        $where = array('type_id'=>array('in',get_child_idArr($id,',')));
        $model = M('Article');
        $list = $model->where($where)->page($p,$num)->order('sort desc , create_date desc')->select();

        $count = $model->where($where)->count();
        $page   = new \Think\Bootpage($count,$num);
        $page->rollPage=5;
        $page->lastSuffix=false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("first","首页");
        $page->setConfig("last","尾页");

        // 公示公告
        $list2 = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,8');

        $this->assign(array(
            'id'         => $id ,
            'title'      => M('ArticleType')->where(array('id'=>$id))->getfield('name') ,
            'child_tree' => $child_tree ,
            'list'       => $list ,
            'list2'      => $list2 ,
            'page'       => $page->show() ,
            ));

        $this->assign('cid',I('get.cid'));
        $this->display();
    }

    public function Details(){

        $id = I('id');
        empty($id) && $this->redirect(U('Index/index'));

        $arc = M('Article')->find($id);

        // 最新推荐
        $recommend = get_list(array('type_id'=>array('in',get_child_idArr($arc['type_id'],','),'recommend'=>1)),'1,6','Article','id,title,create_date',' sort desc ,create_date desc');

        // 热门文章
        $hot = get_list(array('type_id'=>array('in',get_child_idArr($arc['type_id'],','))),'1,6','Article','id,title,create_date','click desc');

        $this->assign(array(
            'arc'       => $arc ,
            'recommend' => $recommend ,
            'hot'       => $hot ,
            ));
        $this->display();
    }


}