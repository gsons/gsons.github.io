<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatReplyModel extends BaseLogic{

    protected $_validate = array(
        array('name','require','请填写名称',),
        array('content','require','请填写回复内容',),
    );

    protected $_auto = array(
        array('admin','is_admin',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
    );


}