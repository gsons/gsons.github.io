<?php
namespace Home\Widget;
use Think\Controller;

class InteractWidget extends Controller{

    public function nav(){
        $this->display('Interact/nav');
    }

    public function comment(){
		$year = strtotime(date('Y',NOW_TIME).'-1-1');
		$LetterHistory = M('LetterHistory');
		$Letter        = M('Letter');
		$year_first_letter_id = $Letter->where(array('datetime'=>array('EGT',$year)))->order('id')->getField('id');
		$total_top = $Letter->where(array('datetime'=>array('EGT',$year)))->field('department_id,count(id) as total')->group('department_id')->order('total desc')->limit('0,10')->select();
		if(!empty($total_top)){
			$department_id_arr   = array_column($total_top,'department_id');

			$department_name_arr = M('authGroup')->where(array('id'=>array('in',$department_id_arr)))->field('id,title')->select();
			$department_name_arr = array_column($department_name_arr,'title','id');

			$reply_arr           = $LetterHistory->where(array('status'=>3,'department_id'=>array('in',$department_id_arr),'letter_id'=>array('EGT',$year_first_letter_id)))->field('department_id,count(id) as reply')->group('department_id')->select();
			$reply_arr           = array_column($reply_arr,'reply','department_id');
		}

		$this->assign(array(
			'total_top'           => $total_top,
			'department_name_arr' => $department_name_arr,
			'reply_arr'           => $reply_arr,
			));
        $this->display('Interact/comment');
    }

}