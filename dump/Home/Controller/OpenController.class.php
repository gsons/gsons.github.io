<?php
namespace Home\Controller;

class OpenController extends PublicController{

    public function Index(){

        // 文章轮播图
        $list4 =  M('Adv')->where(array('status'=>1,'type_id'=>1,'expiration_time'=>array('GT',NOW_TIME)))->field('name,link,src')->order('sort desc')->limit(0,3)->select();
        if(count($list4) < 3){
            $type_id = ' ( type_id in ('.get_child_idArr(1368,',').') and type_id not in ( 1480,1481,1482,1483,1484 ) ) ';
            // echo $type_id;exit;
            $standby = M('article')->where(array($type_id,'special_id'=>0,'src'=>array('NEQ',''),'status'=>array('GT','0')))->order('sort desc , create_date desc')->field('id,title as name,src')->limit(0, 3 - count($list4))->select();
            foreach ($standby as $key => $value) {
                $value['link'] = U('News/Details',array('id'=>$value['id']));
                unset($value['id']);
                $list4[] = $value;
            }
        }

        // 政策解读
        $list1_pic  = get_list(array('type_id'=>array('in',get_child_idArr(2083,',')),'src'=>array('NEQ','')),'1,3','Article','id,title,create_date,description,src');
        $list1_text = get_list(array('type_id'=>array('in',get_child_idArr(2083,',')),'src'=>array('EQ','')),'1,5');

        // 公示公告
        $list2 = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,6');

        $work_activity = M('Information','open_','DB_OPEN')->where(array('catalog_id'=>'3'))->order('public_date desc')->limit(6)->select();
        $this->assign('work_activity',$work_activity);

        // 信息公开制度
        $list3 = get_list(array('type_id'=>array('in',get_child_idArr(1381,','))),'1,9');

        // 政务信息公开
        $list5 = get_list(array('type_id'=>array('in','2114,1383,1453,1454,1455,1456,1457,1389,1390,1391,1392,1393,1427,1473,1474,1475,1428,1476,1477,1478,1430,2115,1384,1385,1386,1387,1388,1431,1432,1433'),),'1,9');

        // 重点领域信息公开专栏_子栏目
        $child = M('ArticleType')->where(array('pid'=>'1690'))->field('id,name')->limit(0,10)->select();

        $catalog = M('Catalog','open_','DB_OPEN')->limit(6)->select();
        foreach ($catalog as $key => $value) {
            $catalog[$key]['list'] = M('Information','open_','DB_OPEN')->where(array('catalog_id'=>$value['id'],'status'=>2))->limit(10)->order('create_date desc')->select();
        }
        $this->assign('catalog',$catalog);
        $this->assign(array(
            'list1_pic'  => $list1_pic ,
            'list1_text' => $list1_text ,
            'list2'      => $list2 ,
            'list3'      => $list3 ,
            'list4'      => $list4 ,
            'list5'      => $list5 ,
            'child'      => $child ,
            ));
        $this->assign('meta_title','政务公开');
        $this->display();
    }


    public function Department(){
        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政务公开目录系统','link'=>U('Open/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);

        $group = M('AuthGroup')->select();
        $category = M('AuthGroupCategory')->order('sort asc')->select();
        foreach ($category as $k => $v) {
            foreach ($group as $key => $value) {
                if( $value['category_id'] == $v['id'] ){
                    $category[$k]['child'][] = $value;
                }
            }
        }
        $this->assign('meta_title','政务信息公开目录');
        $this->assign('category',$category);
        $this->display();
    }

    public function Ls(){

        $department_id = I('get.id');
        $department = M('AuthGroup')->find($department_id);
        $this->assign('department',$department);
        $catalog = M('Catalog','open_','DB_OPEN')->where(array('department_id'=>array('in','0,'.$department_id)))->select();

        $all = 0;
        $parent = array();
        foreach ($catalog as $key => $value) {
            $count = M('Information','open_','DB_OPEN')->where(array('department_id'=>$department_id,'catalog_id'=>$value['id'],'status'=>2))->count();
            $catalog[$key]['count'] = $count;
            $all += $count;
        }
        foreach ($catalog as $key => $value) {
            if( $value['pid'] == '0' ){
                $parent[] = $value;
                unset($catalog[$key]);
            }
        }
        foreach ($parent as $key => $value) {
            foreach ($catalog as $k => $v) {
                if( $value['id'] == $v['pid'] ){
                        $parent[$key]['child'][] = $v;
                }
            }
        }
        $this->assign('catalog',$parent);

        $this->assign('all',$all);

        $map['department_id'] = $department_id;
        $map['status'] = 2 ;

        $catalog_id = I('get.catalog_id');
        if( $catalog_id ){
            $map['catalog_id'] = $catalog_id;
        }
        $index_number = I('get.index_number');
        if( $index_number ){
            $map['index_number'] = array('like',"%{$index_number}%");
            $this->assign('index_number',$index_number);
        }
        $keyword = I('get.keyword');
        if( $keyword ){
            $map['title'] = array('like',"%{$keyword}%");
            $this->assign('keyword',$keyword);
        }
        $start_date = I('get.start_date');
        $end_date = I('get.end_date');
        if( $start_date && !$end_date ){
            $map['public_date'] = array('egt',strtotime($start_date));
        }else if( $end_date && !$start_date ){
            $map['public_date'] = array('elt',strtotime($end_date));
        }else if( $end_date && $start_date ){
            $map['public_date'] = array('between',array(strtotime($start_date),strtotime($end_date)));
        }
        $this->assign('start_date',$start_date);
        $this->assign('end_date',$end_date);
        $current_catalog = M('Catalog','open_','DB_OPEN')->find($catalog_id);
        $this->assign('catalog_id',$catalog_id);
        $this->assign('current_catalog',$current_catalog);

        $order = 'public_date desc';
        $this->ListRecord( M('Information','open_','DB_OPEN') , $map , $order );

        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政务公开目录系统','link'=>U('Open/Department')),
            array('name'=> $department['title'],'link'=>U('Open/Ls',array('id'=>$department_id)))
        );
        if( $catalog_id ){
            $breadcrumb[] =  array('name'=>$current_catalog['name'],'link'=>U('Open/Department',array('id'=>$department_id,'catalog_id'=>$catalog_id)));
        }
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息公开目录');
        $this->display();
    }

    public function Guide(){
        $info = M('Article')->find(76151);
        $this->assign('meta_title','政务信息公开指南');
        $this->assign('info',$info);
        $this->display();
    }

    public function Detail(){
        $id = I('get.id');
        if( !$id || !is_numeric($id) ){
            $this->error('参数错误');
        }
        $Information = M('Information','open_','DB_OPEN');
        $info = $Information->find($id);
        $this->assign('info',$info);

        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政务公开目录系统','link'=>U('Open/Department')),
            array('name'=> $info['department_name'],'link'=>U('Open/Ls',array('id'=>$info['department_id']))),
            array('name'=> getCatalogName($info['catalog_id']),'link'=>U('Open/Ls',array('id'=>$info['department_id'],'catalog_id'=>$info['catalog_id']))),
            array('name'=> $info['title'],'link'=>'')
        );
        $this->assign('breadcrumb',$breadcrumb);

        $this->assign('meta_title',$info['title'].'-政务信息公开目录');
        $this->display();
    }

    public function Article(){
        $this->ListRecord( M('Article','',' sort desc , create_date desc, id desc') );
        $this->display();
    }

}