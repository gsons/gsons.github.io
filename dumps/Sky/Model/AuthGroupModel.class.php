<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class AuthGroupModel extends BaseLogic {

    //字段 title:组名,status:状态,rules:对应规则

    //protected $insertFields = array('url','keyword','title','imgdesc','corver','create_date','create_admin','wechat_id');
    //protected $updatesFields = array('content','create_date','create_admin','wechat_id');

    protected $_validate = array(
        array('title','isExist','管理组/角色已存在',self::MUST_VALIDATE,'callback'),
    );

    protected $_auto = array(
        array('rules','toRules',self::MODEL_BOTH,'callback')
    );

    function toRules($data){
        if(is_array($data)){
            return implode(',',$data);
        }
    }

    function isExist($data){
        if( I('post.id') ){
            $map['id'] = array('neq',I('post.id'));
        }
        $map['title'] = $data;
        $exist = M('AuthGroup')->where($map)->count();
        if( $exist > 0 ){
            return false;
        }
    }

}