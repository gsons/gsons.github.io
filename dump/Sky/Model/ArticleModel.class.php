<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class ArticleModel extends BaseLogic {

    protected $_validate = array(
        array('title','require','请填写新闻标题'),
        array('title','0,100','新闻标题最长100个字符',BaseLogic::MUST_VALIDATE,'length'),
        array('type_id','require','请选择新闻分类'),
        array('type_id','check_type','请选择最下级分类',1,'callback'),
        array('description','check_description','请填写工作分工',1,'callback'),
        array('group_id','require','请选择作者'),
//        array('group_id','check_group','请选择自己所在部门',1,'callback'),
        array('content','require','请填写新闻内容'),
        array('source','require','请填写新闻来源'),
        array('source','0,20','新闻来源最长20个字符',BaseLogic::MUST_VALIDATE,'length'),
    );

    protected $_auto = array(
        array('admin_id','is_admin',BaseLogic::MODEL_INSERT,'function'),
        array('edit_id','is_admin',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','strtotime',BaseLogic::MODEL_BOTH,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),
        array('writer','writer',BaseLogic::MODEL_BOTH,'callback'),
        array('description','description',BaseLogic::MODEL_BOTH,'callback'),
    );

    function check_type($type_id){
        if ($type_id == 0) {
            return false;
        }
        $child = M('ArticleType')->where(array('pid'=>$type_id))->field('id')->find();
        return empty($child) ? true : false ;
    }

    function check_group($group_id){
        if ($group_id == 0) {
            return false;
        }
        $row = M('AuthGroupAccess')->where(array('uid'=>is_admin(),'group_id'=>array('in',array(157,$group_id))))->find();
        return empty($row) ? false : true ;
    }


    function check_description($value){
        if(in_array($_POST['type_id'],array(1480,1481,1482,1483,1484))){
            return empty($value) ? false : true;
        }else{
            return true;
        }
    }

    function description($value){

        if(in_array($_POST['type_id'],array(1480,1481,1482,1483,1484))){
            return $value;
        }else{
            $str    = strip_tags( empty($value) ? $_POST['content'] : $value ) ;
            $length = in_array($_POST['type_id'],array(1480,1481,1482,1483,1484)) ? strlen($str) : 140;
            return msubstr( $str ,0,$length,'utf-8',strlen($str) > $length ? true : false);
        }
    }

    function writer(){
        $row = M('AuthGroup')->where(array('id'=>I('post.group_id')))->field('title')->find();
        return $row['title'];
    }
}