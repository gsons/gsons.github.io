<?php
namespace Home\Controller;

class NewsController extends PublicController{

    public function Index(){

        // 文章轮播图
        $list1 =  M('Adv')->where(array('status'=>1,'type_id'=>4,'expiration_time'=>array('GT',NOW_TIME)))->field('name,link,src')->order('sort desc')->limit(0,5)->select();
        if(count($list1) < 5){
            $model = M('article');
            $standby = $model->where(array('special_id'=>0,'src'=>array('NEQ',''),'type_id'=>array('in',get_child_idArr(1367)),'status'=>array('GT','0')))->order('sort desc')->field('id,title as name,src,create_date')->limit(0, 5 - count($list2))->select();
            foreach ($standby as $key => $value) {
                $value['link'] = U('News/Details',array('id'=>$value['id']));
                unset($value['id']);
                $list1[] = $value;
            }
        }

    	// 最新动态
    	$list2 = get_list(array('type_id'=>array('in',get_child_idArr(1367,',')),'recommend'=>0),'1,7');
    	$list2_1 = $list2[0] ;
    	unset($list2[0]);

    	// 最新推荐
    	$list3 = get_list(array('type_id'=>array('in',get_child_idArr(1367,',')),'recommend'=>1),'1,7');
    	$list3_1 = $list3[0] ;
    	unset($list3[0]);

    	// 郁南新闻
    	$list4 = get_list(array('type_id'=>array('in',get_child_idArr(19,','))),'1,4');

        // 视频新闻
        $list5 = get_list(array('type_id'=>array('in',get_child_idArr(1379,','))),'1,4','Article','id,title,src');

    	// 部门动态
    	$list6 = get_list(array('type_id'=>array('in',get_child_idArr(1378,','))),'1,8');

    	// 乡镇动态
    	$list7 = get_list(array('type_id'=>array('in',get_child_idArr(1071,','))),'1,8');

        // 公示公告
        $list8 = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,9');

        // 专题
        $special = M('special')->where(array('department_id'=>0,'start_time'=>array('LT',NOW_TIME),'end_time'=>array('GT',NOW_TIME),'status'=>array('GT',0)))->order('id desc')->limit(0,7)->select();

        // 底部轮播图
        $list9 = M('Adv')->where(array('status'=>1,'type_id'=>3))->field('name,link,src')->order('sort desc')->limit(0,6)->select();

    	$this->assign(array(
            'list1'      => $list1,
            'list2'      => $list2,
            'list2_1'    => $list2_1,
            'list3'      => $list3,
            'list3_1'    => $list3_1,
            'list4'      => $list4,
            'list5'      => $list5,
            'list6'      => $list6,
            'list7'      => $list7,
            'list8'      => $list8,
            'special'    => $special,
            'list9'      => $list9,
            'meta_title' => '新闻中心',
    		));
        $this->display();
    }


    public function test(){
        $type = get_child_category('ArticleType','id','name','pid',0);
        dump($type);exit;
    }

    /*public function test(){
    	$id = I('get.id');
    	$nav = get_child_category('ArticleType','id','name','pid',1356);
        // print_r($nav);

        $i = 6;
        $arr1 = array();
        foreach ($nav as $key => $value) {
            echo "INSERT INTO sky_special (`id`, `title`, `theme`, `start_time`, `end_time`, `create_date`, `create_date`, `admin_id`, `status`) VALUES ('",$i,"', '",$value['name'],"', 'default', '1487520000', '1487692800', '1487641551', '1487641946', '1', '1');","<br />";

            echo "UPDATE sky_article_type SET `pid`='0', `special_id`='",$i,"' WHERE id =" ,$value['id'],"; <br />";

            if(!empty($value['sub'])){
                foreach ($value['sub'] as $k => $v) {
                    $arr1[] = $v['id'];
                }

                echo "UPDATE sky_article_type SET `pid`='0', `special_id`='",$i,"' WHERE id in (",implode($arr1, ','),");","<br />";
            }

        $i++;
        $arr1 = array();
        }

    }*/



}