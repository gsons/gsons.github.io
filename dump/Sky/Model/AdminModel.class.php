<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class AdminModel extends BaseLogic {
    protected $_validate = array(
        array('admin','require','管理员用户名不能为空'),
        array('admin','isExist','管理员用户名已存在',self::MUST_VALIDATE,'callback'),
        array('pwd','require','密码不能为空',self::EXISTS_VALIDATE),
        array('repwd','pwd','两次密码不一致',self::EXISTS_VALIDATE,'confirm'),
    );

    protected $_auto = array(
        array('create_date','time',self::MODEL_INSERT,'function'),
        array('update_date','time',self::MODEL_BOTH,'function'),
        array('pwd','getMD5',self::MODEL_BOTH,'callback')
    );

    function getMD5($data){
        if(!empty($data)) {
            return md5($data . C('MD5_VALIDATE_KEY'));
        }else{
            return false;
        }
    }

    function isExist($data){
        if( I('post.id') ){
            $map['id'] = array('neq',I('post.id'));
        }
        $map['admin'] = $data;
        $exist = M('Admin')->where($map)->count();
        if( $exist > 0 ){
            return false;
        }
    }

}