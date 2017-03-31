<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatKefuModel extends BaseLogic{

    protected $_validate = array(
        array('name','require','请填写客服昵称'),
        array('kf_account','require','请填写客服账号'),
        array('kf_account','1,10','账号长度为1-10位英文以及数字',BaseLogic::VALUE_VALIDATE,'length'),
        array('password','require','请填密码',BaseLogic::EXISTS_VALIDATE),
        array('repassword','require','请填确认密码',BaseLogic::EXISTS_VALIDATE),
        array('repassword','password','两次密码不一致',BaseLogic::EXISTS_VALIDATE,'confirm'),
    );

    protected $_auto = array(
        array('admin','is_admin',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
    );

}