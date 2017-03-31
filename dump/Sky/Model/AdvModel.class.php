<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class AdvModel extends BaseLogic{

    protected $_validate = array(

        array('name','require','请填写名称'),
        array('link','require','请填写跳转地址'),
        array('type','require','请选择焦点图分类'),
        array('src','require','请上传图片'),
        array('sort','is_numeric','排序请填写数字',BaseLogic::VALUE_VALIDATE,'function'),
    );

    protected $_auto = array(

        array('expiration_time','strtotime',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),

    );
}