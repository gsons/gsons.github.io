<?php
namespace Home\Controller;

class SubstationController extends PublicController{

    public function Index(){

    	$list1 = M('special')->where(array('department_id'=>array('GT',0),'status'=>array('GT',0)))->field('id,title')->order('id desc')->select();

    	$this->assign(array(
            'list1'      => $list1,
    		));
        $this->display();
    }


}