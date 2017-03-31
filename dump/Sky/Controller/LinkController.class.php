<?php
namespace Sky\Controller;

class LinkController extends PublicController {

    public function Index(){

        $where = array('title'=>array('like','%'.I('keyword').'%'));
        $this->ListRecord( M('FriendLink') , $where , 'id desc');
        $this->display('Link/Index');

    }

    public function Edit(){
        if( I('get.id') ){
            $info = M('FriendLink')->find(I('get.id'));
            $this->assign('info',$info);
        }
        if(IS_AJAX){
            $data = I('');
            $this->ajaxReturn( $this->ActiveRecord(D('FriendLink') , $data ,  $_SERVER['HTTP_REFERER']  ) );
        }
        $this->assign('type',M('FriendLinkType')->select());
        $this->display('Link/Edit');
    }

    public function Type(){

        $where = array('title'=>array('like','%'.I('keyword').'%'));

        $this->ListRecord( M('FriendLinkType') , $where , 'id desc');
        $this->display('Link/Type');

    }

    public function TypeEdit(){
        if( I('get.id') ){
            $info = M('FriendLinkType')->find(I('get.id'));
            $this->assign('info',$info);
        }

        if(IS_AJAX){
            $data = I('');
            $this->ajaxReturn( $this->ActiveRecord(D('FriendLinkType') , $data ,  $_SERVER['HTTP_REFERER']  ) );
        }
        $this->display('Link/TypeEdit');
    }
}
