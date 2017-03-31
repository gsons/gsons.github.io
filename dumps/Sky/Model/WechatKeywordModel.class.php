<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatKeywordModel extends BaseLogic{

    protected $_validate = array(
        array('keyword','require','请填写关键词')
    );

    protected $_auto = array(
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('status','1',BaseLogic::MODEL_INSERT),
    );

    function sub($data){

        $res = $this->where(array('openid'=>$data['openid']))->select();
        if( count($res) == 1 ){
            $data['id'] = $res[0]['id'];
            $data['is_sub'] = 1;
            return $this->SelfUpdate($data);
        }else{
            return $this->SelfAdd($data);
        }

    }

    function unSub($openid){

        $this->is_sub = 0;
        $res = $this->where(array('openid'=>$openid))->save();
        return $res;

    }

}