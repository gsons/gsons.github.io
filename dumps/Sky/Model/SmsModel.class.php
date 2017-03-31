<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class SmsModel extends BaseLogic {

    //字段 type:类型,admin:用户名,pwd:密码,create_date:创建时间

    //protected $insertFields = array('url','keyword','title','imgdesc','corver','create_date','create_admin','wechat_id');
    //protected $updatesFields = array('content','create_date','create_admin','wechat_id');

    protected $_validate = array(
    );

    protected $_auto = array(
        array('create_date','time',self::MODEL_INSERT,'function'),
    );

}