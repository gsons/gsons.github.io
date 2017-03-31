<?php
namespace Home\Controller;

class EconomyController extends PublicController{


    public function Index(){

        // 轮播图
        $list1 = M('Adv')->where(array('status'=>1,'type_id'=>6,'expiration_time'=>array('GT',NOW_TIME)))->field('name,link,src')->order('sort desc')->limit(0,5)->select();
        if(count($list1) < 5){
            $standby = M('article')->where(array('special_id'=>0,'src'=>array('NEQ',''),'status'=>array('GT','0'),'type_id'=>array('in',get_child_idArr(1540))))->order('sort desc , create_date desc')->field('id,title as name,src')->limit(0,5 - count($list1))->select();
            foreach ($standby as $key => $value) {
                $value['link'] = U('Economy/Details',array('id'=>$value['id']));
                unset($value['id']);
                $list1[] = $value;
            }
        }

        // 公示公告
        $list2 = get_list(array('type_id'=>array('in',get_child_idArr(2070,','))),'1,8');

        // 招商动态
        $list3 = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,8');

        // 部门动态
        $list4 = get_list(array('type_id'=>array('in',get_child_idArr(2069,','))),'1,8');

        // 投资政策
        $list5 = get_list(array('type_id'=>array('in',get_child_idArr(2071,','))),'1,8');

        // 郁南宣传
        $list6 = get_list(array('type_id'=>array('in',get_child_idArr(2073,','))),'1,5');

        // 郁南宣传
        $list7 = get_list(array('type_id'=>array('in',get_child_idArr(2068,','))),'1,8');

        $this->assign(array(
            'list1'      => $list1 ,
            'list2'      => $list2 ,
            'list3'      => $list3 ,
            'list4'      => $list4 ,
            'list5'      => $list5 ,
            'list6'      => $list6 ,
            'list7'      => $list7 ,
            'meta_title' => '招商引资' ,
            ));

        $this->display();
    }


}