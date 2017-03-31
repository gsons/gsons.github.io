<?php
namespace Home\Model;
use Think\Model\RelationModel;

class LetterModel extends RelationModel{

    protected $_link = array(
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

    public function get_list($where,$p,$num){
       	return $this->where($where)->field("id,title,name,status,datetime,department_id")->order('datetime desc')->relation('AuthGroup')->page($p,$num)->select();
    }

    public function get_detail($id){
        return $this->where(array('is_public'=>1,'id'=>$id))->relation(true)->find();
    }

}