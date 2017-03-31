<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class InterviewModel extends BaseLogic {

    protected $_validate = array(

        array('title','require','请填写会谈标题'),
        array('title','0,50','会谈标题最长50个字符',BaseLogic::MUST_VALIDATE,'length'),
        array('interview_time','require','请填写访谈时间'),
        array('interview_place','require','请填写访谈地点'),
        array('interview_guest','require','请填写访谈嘉宾'),
        array('interview_description','require','请填写访谈摘要'),
        array('content','require','请填写直播内容'),

    );

    protected $_auto = array(
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),

    );

}