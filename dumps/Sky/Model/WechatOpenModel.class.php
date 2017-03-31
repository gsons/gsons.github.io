<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatOpenModel extends BaseLogic{

    protected $_validate = array(
        array('name','require','请填写公众号名称',),
        array('account','require','请填写公众号帐号',),
        array('openid','require','请填写公众号OpenID',),
        array('token','require','请填写开发者TOKEN',),
    );

    protected $_auto = array(
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('status','0',BaseLogic::MODEL_BOTH),
    );


}