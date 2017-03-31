<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class UserModel extends BaseLogic{

    protected $_validate = array(

        array('username','require','请填写用户名'),
        array('username','isExist','用户名已存在',BaseLogic::MUST_VALIDATE,'callback'),

        array('password','6,20','密码由6-20位英文或数字以及非特殊字符组成',BaseLogic::VALUE_VALIDATE,'length'),
        array('repassword','password','输入的两次密码不一致',BaseLogic::VALUE_VALIDATE,'confirm'),

        array('type','require','请选择用户类型'),

        array('org_short_name','isOrg','政府用户请填写简称',BaseLogic::MUST_VALIDATE,'callback')
    );

    protected $_auto = array(

        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('ip','get_client_ip',BaseLogic::MODEL_INSERT,'function'),
        array('password','md5_password', BaseLogic::MODEL_BOTH ,'callback'),

    );

    function isOrg($data){
        if(I('post.type') == '0'){
            if( empty($data) ){
                return false;
            }
        }
        return true;
    }

    function isExist($data){
        $uid = I('post.id');
        if( empty($uid) ){
            $user = M('User')->where(array('username'=>$data))->select();
            if( count($user) > 0 ){
                return false;
            }
        }else{
            $user = M('User')->where(array('username'=>$data,'id'=>array('neq',$uid)))->select();
            if( count($user) > 0 ){
                return false;
            }
        }
        return true;
    }

    function md5_password($data){
        $seed = I('post.seed');
        $md5_key = C('MD5_VALIDATE_KEY');
        return md5($data.$seed.$md5_key);
    }

}