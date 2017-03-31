<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller{

    public function Index(){
        $this->display();
    }

    public function Lists(){
        $get = I('get.');
        empty($get['search']) && $this->redirect('Index');
        $get['search']  = filter_mark($get['search']);
        $get['notlike'] = filter_mark($get['notlike']);
        if (empty($_SERVER['QUERY_STRING']) ){
            $get['search']  = base64_decode(base64_decode($get['search']));
            $get['notlike'] = base64_decode(base64_decode($get['notlike']));
        }
        $p     = I('p',1);
        $num   = 20;
        $model = M('Article');
        $time  = '';
        $id_in = '';
        $like  = '';
        if($get['start_time'] || $get['end_time']){
            $time = " ( create_date between ".( empty($get['start_time']) ? 0 : strtotime($get['start_time']) )." and ".( empty($get['end_time']) ? NOW_TIME : strtotime($get['end_time']) ) ." ) and ";
        }
        switch ($get['type']) {
            case '1366':
                $idArr = get_child_idArr('1366');
                $get['type'] = '1366';
                break;
            case '1367':
                $idArr = get_child_idArr('1367');
                $get['type'] = '1367';
                break;
            case '1368':
                $idArr = get_child_idArr('1368');
                $get['type'] = '1368';
                break;
            case '1540':
                $idArr = get_child_idArr('1540');
                $get['type'] = '1540';
                break;
            case '2091':
                $idArr = get_child_idArr('2091');
                $get['type'] = '2091';
                break;
            default:
                $idArr = array_merge(get_child_idArr('1366'),get_child_idArr('1367'),get_child_idArr('1368'),get_child_idArr('1540'),get_child_idArr('2091'));
                $get['type'] = '0';
                break;
        }

        $id_in = " (type_id in (".implode(',', $idArr).") ) and ";

        switch ($get['position']){
            case 'title':
                if($get['notlike'])
                    $like = "( title like '%".$get['search']."%' and title not like '%".$get['notlike']."%'  )";
                else
                    $like = "( title like '%".$get['search']."%' )";
                break;
            case 'content':
                if($get['notlike'])
                    $like = "( content like '%".$get['search']."%' and content not like '%".$get['notlike']."%' )";
                else
                    $like = "( content like '%".$get['search']."%' )";
                break;
            default:
                if($get['notlike'])
                    $like = "( (title like '%".$get['search']."%' and title not like '%".$get['notlike']."%') OR ( content like '%".$get['search']."%' and content not like '%".$get['notlike']."%' ) )";
                else
                    $like = "( (title like '%".$get['search']."%') OR  ( content like '%".$get['search']."%' ) )";
                break;
        }
        $sql   = $time . $id_in . $like . ' AND status = 1';
        $order = $get['order'] == 'date' ? 'create_date desc' : 'click desc' ;
        // echo $model->where($sql)->field('id,title,content,create_date')->fetchSql(true)->page($p,$num)->order('create_date desc')->select();exit;
        $list  = $model->where($sql)->field('id,title,type_id,content,click,create_date')->page($p,$num)->order($order)->select();
        $count = $model->where($sql)->count();

        $search = $get['search'];
        $get['search']  = base64_encode(base64_encode($get['search']));
        $get['notlike'] = base64_encode(base64_encode($get['notlike']));

        $page  = new \Think\Bootpage2($count,$num,$get);
        $page->rollPage=5;
        $page->lastSuffix=false;
        $page->setConfig("prev","&laquo;");
        $page->setConfig("next","&raquo;");
        $page->setConfig("first","首页");
        $page->setConfig("last","尾页");
        $page->setConfig("show_total",false);
        $this->assign(array(
            'get'        => $get ,
            'list'       => $list ,
            'count'      => $count ,
            'page'       => $page->show() ,

            'meta_title' => "\"".base64_decode(base64_decode($get['search']))."\"的搜索结果" ,
            ));
        $this->display('Result');
    }

    public function Details(){
        $id = I('id');
        $type_id = M('article')->where(array('id'=>$id))->getField('type_id');
        empty($type_id) && $this->redirect('Index');
        $cache_time = 3600 * 5 ;
        $type = S('type');
        if(empty($type)){
            echo 1;
            $temp = M('ArticleType')->where(array('pid'=>0))->field('id')->select();
            foreach ($temp as $key => $value) {
                $type[$value['id']] = get_child_idArr($value['id']);
            }
            S('type',$type,$cache_time);
        }
        $controllerArr = array(
            '1366'=> 'About',
            '1367'=> 'News',
            '1368'=> 'Open',
            '1370'=> 'Interact',
            '1540'=> 'Economy',
            '2091'=> 'Hall',
            );
        $Controller = 'Search';
        foreach ($controllerArr as $key => $value) {
            if(in_array($type_id, $type[$key])){
                $Controller = $value;
            }
        }
        redirect(U($Controller.'/Details',array('id'=>$id)));
    }


}