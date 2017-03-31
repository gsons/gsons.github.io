<?php
namespace Sky\Model;
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


    protected $_validate = array(
        array('title','require','请填写调查标题'),
        array('title','0,50','调查标题最长50个字符',1,'length'),
    );

}