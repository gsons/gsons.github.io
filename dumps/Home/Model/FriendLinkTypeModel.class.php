<?php
namespace Home\Model;
use Think\Model\RelationModel;

class FriendLinkTypeModel extends RelationModel{

    protected $_link = array(
        'FriendLink' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'FriendLink',
            'foreign_key'   => 'type_id',
            'mapping_limit'  => '21',
            'mapping_order' => 'ID desc',
            )
        );

}