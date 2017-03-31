<?php
namespace Sky\Model;
use Think\Model\RelationModel;

class LetterModel extends RelationModel{

	protected $_link = array();

	public function get_detail($id){
    	$this->_link = array(
	        'AuthGroup' => array(
				'mapping_type' => self::BELONGS_TO,
				'class_name'   => 'AuthGroup',
				'foreign_key'  => 'department_id',
				'as_fields'    => 'title:department',
            ),
            'LetterHistory' => array(
                'mapping_type' => self::HAS_MANY,
                'class_name'   => 'LetterHistory',
                'mapping_name' => 'history',
            ),
        );
        return $this->where(array('id'=>$id))->relation(true)->find();
    }

}