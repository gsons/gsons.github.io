<?php
namespace Sky\Controller;
use Home\Controller\PublicController;

class CenterController extends PublicController {

    public function user(){

        $this->Userdata = M('User')->select();

        $this->display('User');

    }

    public function userlog(){

        if( $uid = I('get.id') ){

            $this->Userdata = M('User')->find($uid);
            $Userlog = M('Userlog');
            $this->Userlog = $Userlog->where('user_id = '.$uid)->select();
            $this->display('Userlog');

        }else{

            $this->error('参数异常');

        }

    }

    public function setStatus(){

        if(IS_AJAX){

            $User = D('User');
            $data = I('get.');
            $res = $User->setStatus($data);
            $this->ajaxReturn($res);

        }

    }

}