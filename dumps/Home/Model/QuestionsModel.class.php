<?php
namespace Home\Model;
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


}