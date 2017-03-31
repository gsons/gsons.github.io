<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class RepositoryModel extends BaseLogic {

    protected $_validate = array(

        array('title','require','请填写新闻标题'),
        array('title','0,50','新闻标题最长50个字符',BaseLogic::MUST_VALIDATE,'length'),
        array('type_id','require','请选择新闻分类'),
        array('content','require','请填写新闻内容'),

    );

    protected $_auto = array(
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('user_id','get_uid',BaseLogic::MODEL_BOTH,'callback'),

    );

    protected function get_uid(){
    	return session('auid') ? session('auid') : 0 ;
    }

}