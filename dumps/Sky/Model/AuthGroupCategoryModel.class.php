<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class AuthGroupCategoryModel extends BaseLogic {
    protected $_validate = array(
        array('name','require','请输入机构名称！'),
        array('number','is_numeric','序号请输入数字',0,'function'),
        array('sort','is_numeric','排序请输入数字',0,'function'),
    );

}