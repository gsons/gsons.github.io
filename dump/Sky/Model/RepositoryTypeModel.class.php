<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class RepositoryTypeModel extends BaseLogic {

    protected $_validate = array(

        array('name','require','请填写分类'),

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