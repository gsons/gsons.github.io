<?php
namespace Home\Controller;

class AboutController extends PublicController{


    public function Index(){
    	$publicity = get_list(array('type_id'=>array('in',get_child_idArr(1373,','))),'1,8');

    	// 历史文化描述
    	$description2 = M('ArticleType')->where(array('id'=>1373))->getfield('description');

    	// 历史文化列表
    	$list1 = get_list(array('type_id'=>array('in',get_child_idArr(1373,','))),'1,7');

    	// 图说郁南
    	$list2 = get_list(array('type_id'=>array('in',get_child_idArr(2067,','))),'1,4','Article','id,title,src');

    	$this->assign(array(
            'description2' => $description2,
            'list1'        => $list1,
            'list2'        => $list2,
            'meta_title'   => '走进郁南',
    		));
        $this->display();
    }


}