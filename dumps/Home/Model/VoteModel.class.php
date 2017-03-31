<?php
namespace Home\Model;
use Think\Model\RelationModel;

class VoteModel extends RelationModel{

    protected $_link = array(
        'VoteOption' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'VoteOption',
            'foreign_key'   => 'vote_id',
        ),
        'Questions' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Questions',
            'foreign_key'   => 'questions_id',
        ),
    );


}