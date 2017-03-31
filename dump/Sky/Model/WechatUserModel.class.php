<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatUserModel extends BaseLogic{

    protected $_validate = array(
    );

    protected $_auto = array(
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
    );

    function sub($data){

        $data['nickname'] = json_encode(emoji_softbank_to_unified($data['nickname']));
        $res = $this->where(array('openid'=>$data['openid']))->find();
        $data['is_sub'] = $data['subscribe'];
        $data['sub_date'] = $data['subscribe_time'];
        if( $res ){
            $data['id'] = $res['id'];
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