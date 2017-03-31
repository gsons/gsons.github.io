<?php
namespace Sky\Controller;
use Think\Controller;

class UserController extends Controller {

    public function logout(){
        session('auid',null);
        cookie('auto_login_auid',null);
        S('Admindata',null);
        header('Location:'.U('User/Login'));
    }

    public function login() {
        if(IS_POST){
            $data = I('post.');
            $Admin = D('Admin');
            $res = $Admin->where(array('admin'=>$data['username'],'pwd'=>md5($data['pass'].C("MD5_VALIDATE_KEY"))))->select();
            if( count($res) == 1 ){
                //清楚上一次登录信息
                session('auid',null);
                cookie('auto_login_auid',null);
                S('Admindata',null);

                session('auid',$res[0]['id']);
                if( $data['auto'] == 1 ){
                    cookie(md5('auto_login_auid'),base64_encode($res[0]['id']),3600*24*3);
                }
                $Admin->LogAction(1);
                $Admin->where(array('id'=>$res[0]['id']))->save(array('last_login'=>time()));
                $this->success('登录成功',U('Index/Index'));
                exit;
            }else{
                $this->error('用户名或者密码错误');
            }
        }
        $this->display('Login');
    }

    public function Profile(){
        if(IS_POST){
            $data = I('post.');
            if( $data['pwd'] != $data['re-pwd'] ){
                $this->error('两次密码不一致');
            }
            $aid = is_admin();
            $Admin = D('Admin');
            $res = $Admin->where(array('id'=>$aid,'pwd'=>md5($data['old-pass'].C("MD5_VALIDATE_KEY"))))->select();
            if( count($res) == 1 ){
                $save = array(
                    'pwd'=>md5($data['pwd'].C("MD5_VALIDATE_KEY"))
                );
                $effect = $Admin->where(array('id'=>$aid))->save($save);
                if( $effect === false ){
                    $this->error('修改失败');
                }else{
                    $this->success('修改成功');
                }
            }else{
                $this->error('原密码错误！');
            }
        }
        $this->display();
    }

    public function Prefer(){
        $this->display();
    }


}