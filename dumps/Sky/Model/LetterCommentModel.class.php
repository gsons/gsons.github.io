<?php
namespace Sky\Model;
use Think\Model\RelationModel;

class LetterCommentModel extends RelationModel{

	protected $_link = array(
        'Letter' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name'   => 'Letter',
            'as_fields'    => 'title',
        )
    );


}