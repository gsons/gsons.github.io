<?php
namespace Sky\Model;
use Think\Model\RelationModel;

class QuestionsModel extends RelationModel{

    protected $_link = array(
        'Vote' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Vote',
            'foreign_key'   => 'questions_id',
            'mapping_order' => 'id asc',
        ),
    );

    protected $_validate = array(
        array('title','require','请填写调查标题'),
        array('title','0,50','调查标题最长50个字符',1,'length'),
        array('start_time','require','请选择开始时间'),
        array('end_time','require','请选择结束时间'),
        array('end_time','check_time','结束时间不能比开始时间小',1,'callback'),
    );

    protected $_auto = array(
        array('user_id','is_admin',1,'function'),
        array('create_date','time',1,'function'),
        array('update_date','time',3,'function'),
        array('start_time','strtotime',3,'function'),
        array('end_time','strtotime',3,'function'),
    );

    function check_time($end_time){
        return strtotime($end_time) > strtotime(I('start_time')) ? true : false ;
    }

}