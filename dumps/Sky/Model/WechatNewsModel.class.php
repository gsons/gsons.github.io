<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class WechatNewsModel extends BaseLogic{

    protected $_validate = array(
        array('title','require','请填写标题',),
        array('title','0,64','标题超出长度',BaseLogic::MUST_VALIDATE,'length'),
        array('image','require','请选择封面',),
        array('author','require','请填写作者',),
        array('author','0,12','作者超出长度',BaseLogic::MUST_VALIDATE,'length'),
        array('digest','0,120','简介超出长度',BaseLogic::MUST_VALIDATE,'length'),
//        array('digest','require','请填写简介',),
        array('content','require','请填写内容',),
    );

    protected $_auto = array(
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
    );

}