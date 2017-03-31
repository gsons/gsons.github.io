<?php
namespace Sky\Controller;

class CategoryController extends PublicController {

    public function Topic(){
        $map = array(
            'status' => 1
        );
        $order = 'id desc';
        $this->assign('list',M('Topic')->where($map)->order($order)->select());
        $this->display();
    }

    public function TopicEdit(){
        if(I('get.id')){
            $this->assign('info',M('Topic')->find(I('get.id')));
        }
        if(IS_AJAX){
            $this->ajaxReturn( $this->ActiveRecord(D('Topic') ,I('') ,U('Category/Topic')) );
        }
        $this->display();
    }

    public function Forum(){
        $map = array(
            'status' => 1
        );
        $order = 'id desc';
        $this->assign('list',M('ForumCategory')->where($map)->order($order)->select());
        $this->display();
    }

    public function ForumEdit(){
        if(I('get.id')){
            $this->assign('info',M('ForumCategory')->find(I('get.id')));
        }
        if(IS_AJAX){
            $this->ajaxReturn( $this->ActiveRecord(D('ForumCategory') ,I('') ,U('Category/Forum')) );
        }
        $this->display();
    }


}