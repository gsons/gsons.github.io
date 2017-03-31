<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class SpecialModel extends BaseLogic {

    protected $_validate = array(
        array('title','require','请填写专题标题'),
        array('title','0,50','专题标题最长50个字符',BaseLogic::MUST_VALIDATE,'length'),
        array('start_time','require','请选择开始时间'),
        array('end_time','require','请选择结束时间'),
        array('end_time','check_time','结束时间不能比开始时间小',1,'callback'),
        array('theme','require','请选择专题模版'),
    );

    protected $_auto = array(
        array('admin_id','is_admin',BaseLogic::MODEL_INSERT,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('start_time','strtotime',BaseLogic::MODEL_BOTH,'function'),
        array('end_time','strtotime',BaseLogic::MODEL_BOTH,'function'),
    );

    function check_time($end_time){
        return strtotime($end_time) > strtotime(I('start_time')) ? true : false ;
    }

}