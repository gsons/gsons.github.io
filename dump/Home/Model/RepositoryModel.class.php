<?php
namespace Home\Model;
use Think\Model\RelationModel;

class RepositoryModel extends RelationModel{

    protected $_link = array(
            'RepositoryType' => array(
				'mapping_type' => self::BELONGS_TO,
				'class_name'   => 'RepositoryType',
				'foreign_key'  => 'type_id',
				'as_fields'    => 'name:typename',
            ),
        );


}