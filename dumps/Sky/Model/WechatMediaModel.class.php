<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatMediaModel extends BaseLogic{

    protected $_validate = array(
    );

    protected $_auto = array(
        array('admin','is_admin',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
    );

}