<?php
namespace Sky\Controller;

class DepartmentsController extends PublicController{

	public function index(){
	    $this->ListRecord(D("AuthGroupCategory"),'','sort asc, id asc');
		$this->display();
	}
	public function edit(){
        if(IS_AJAX) {
            if(IS_GET){
                $count = M('AuthGroup')->where(array('category_id'=>I('get.id')))->count();
                if( $count > 0 ){
                    $this->error('该类别下存在管理组/单位，无法删除');
                }
            }
            $res = $this->ActiveRecord(D("AuthGroupCategory"), I(""));
            $this->ajaxReturn($res);
        }
	}
}