<?php
namespace Sky\Controller;

class AccountController extends PublicController {

    public function Account(){

        $User = M('User');
        $count  = $User->count();
        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $User->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();

    }

    public function AccountDetail(){

        if(I('get.id')){
            $this->assign('info',M('User')->find(I('get.id')));
        }
        $this->display();

    }

    public function AccountEdit(){

        if(I('get.id')){
            $this->assign('info',M('User')->find(I('get.id')));
        }

        if(IS_AJAX){

            if( $_POST['id'] && empty($_POST['password']) && empty($_POST['repassword']) ){
                unset($_POST['password']);
                unset($_POST['repassword']);
            }else{
                $_POST['seed'] = rand(1000,9999);
            }

            $this->ajaxReturn( $this->ActiveRecord(D('User') ,I('') ,U('Account/Account')) );
        }

        $this->assign('type',C('USER_TYPE'));
        $this->display();
    }

    public function Apply(){

        $Apply = M('Apply');
        $count  = $Apply->count();
        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $Apply->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function ApplyDetail(){

        if(I('get.id')){
            $this->assign('info',M('Apply')->find(I('get.id')));
        }

        if(IS_AJAX){
            $id = I('get.id');
            if ( M('Apply')->where(array('id'=>$id))->setField(array('status'=>1)) ){
                $res = array('status'=>1,'info'=>'操作成功');
            }else{
                $res = array('status'=>0,'info'=>'操作失败');
            }
            $this->ajaxReturn($res);
        }
        $this->display();

    }

    public function Forum(){

        $Forum = M('Forum');
        $count  = $Forum->count();
        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $Forum->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function ForumDetail(){

        if(I('get.id')){
            $this->assign('info',M('Forum')->find(I('get.id')));
            $this->assign('response',M('ForumResponse')->where(array('forum_id'=>I('get.id')))->select());
        }

        if(IS_AJAX){
            if(IS_GET) {
                $id = I('get.id');
                if ( M('Forum')->where(array('id' => $id))->setField( array('status' => I('get.status')) )) {
                    $res = array('status' => 1, 'info' => '操作成功');
                } else {
                    $res = array('status' => 0, 'info' => '操作失败');
                }
                $this->ajaxReturn($res);
            }else{
                $_POST['forum_id'] = I('get.id');
                M('Forum')->where(array('id'=>$_POST['forum_id']))->save(array('isdeal'=>1));
                $this->ajaxReturn( $this->ActiveRecord( D('ForumResponse') , I('') ) );
            }
        }
        $this->display();

    }

    public function ForumResponse(){

        if(IS_AJAX){
            $id = I('get.id');
            if (M('ForumResponse')->where(array('id' => $id))->setField(array('status' => I('get.status')))) {
                $res = array('status' => 1, 'info' => '操作成功');
            } else {
                $res = array('status' => 0, 'info' => '操作失败');
            }
            $this->ajaxReturn($res);
        }

    }



}