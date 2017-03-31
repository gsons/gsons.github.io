<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class SkymenuModel extends BaseLogic {

    protected $connection = 'DB_GDYUNAN';


    protected $_validate = array(
        array('name','require','请填写菜单名称'),
        array('sort','is_numeric','排序应填写数字',BaseLogic::VALUE_VALIDATE,'function'),
    );

    protected $_auto = array(
    );

}