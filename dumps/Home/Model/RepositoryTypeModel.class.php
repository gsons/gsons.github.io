<?php
namespace Home\Model;
use Think\Model\RelationModel;

class RepositoryTypeModel extends RelationModel{

	public function __construct(){
        parent::__construct();
        $this->set_link();
    }

    protected $link = array();

    protected function set_link(){

        $search = I('get.search');
        if (empty($_SERVER['QUERY_STRING']) ){
            $search = base64_decode($search);
        }

    	$this->_link = array(
            'Repository' => array(
                'mapping_type'   => self::HAS_MANY,
                // 'class_name'  => 'Repository',
                'mapping_name'   => 'Repository',
                'mapping_fields' => 'count(id) as count',
                'foreign_key'    => 'type_id',
                'condition'      => "title like '%".$search."%'",
            ));
    }

}