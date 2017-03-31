<?php
namespace Sky\Controller;

class ResponsibilityController extends TypeController {

    public function Type(){
        $type = M('ArticleType')->where(array('special_id'=>0))->select();
        $tree = list_to_tree($type);
        $this->makeTree($tree,0 ,'Responsibility/Tree');
        $this->assign('tree',$this->html);
        $this->display('Type/Type');
    }

    public function Cycle(){
        $type = M('ArticleType')->where(array('special_id'=>0))->select();
        $tree = list_to_tree($type);
        $this->makeTree($tree , 0 , 'Cycle');
        $this->assign('tree',$this->html);
        $this->display('Type/Type');
    }

    public function Date(){
        $type = M('ArticleType')->find(I('get.id'));
        if(IS_AJAX){
            $cycle = I('post.cycle');
            $res = M('ArticleType')->where(array('id'=>I('get.id')))->save(array('cycle'=>$cycle));
            if( $res ){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
        $this->assign('info',$type);
        $this->assign('date',C('CYCLE_DATE'));
        $this->display();
    }

    public function Group(){

        if(IS_AJAX){
            $group_id = I('post.group_id');
            if( empty($group_id) ){
                $this->error('请选择部门');
            }
            M('ArticleTypeGroup')->where(array('type_id'=>I('get.id')))->delete();
            foreach ($group_id as $key => $value) {
                $data[] = array(
                    'type_id' => I('get.id'),
                    'group_id' => $value,
                );
            }
            $res = M('ArticleTypeGroup')->addAll($data);
            if( $res ){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }

        }

        $group = getGroup(true);
        $this->assign('group',$group);
        $list = M('ArticleTypeGroup')->where(array('type_id'=>I('get.id')))->join('__AUTH_GROUP__ on __AUTH_GROUP__.id = __ARTICLE_TYPE_GROUP__.group_id')->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function Statistics(){
        $keyword = I('get.keyword');
        $date = C('CYCLE_DATE');
        $cycleList = $this->cycleList();
        if( $keyword ){
            $map['title'] = array('like',"%{$keyword}%");
        }
        $Group = M('AuthGroup')->field('id,title')->where($map)->select();
        foreach ($Group as $key => $value) {
            $cycle = M('ArticleTypeGroup')->where(array('group_id'=>$value['id']))->join('__ARTICLE_TYPE__ on __ARTICLE_TYPE__.id = __ARTICLE_TYPE_GROUP__.type_id')->select();
            $data = array();
            foreach ($date as $k => $v) {
                $data[ $k ]['max'] = 0;
                $data[ $k ]['need'] = 0;
            }
            foreach ($cycle as $k => $v ) {
                $cycleCur = $cycleList[ $v['cycle'] ];
                foreach ($cycleCur as $t => $p) {
                    if( $p[0] < time() && time() < $p[1] ){
                        $target = $p;
                        $step = $t;
                    }
                }
                $data[ $v['cycle'] ]['max'] ++ ;
                $count = M('Article')->where(array('type_id'=>$v['type_id'],'group_id'=>$value['id'],'create_date'=>array('between',$target)))->count();
                if( !$count ){
                    $data[ $v['cycle'] ]['need']++;
                }
            }
            $Group[$key]['data'] = $data;
        }
        $this->assign('CYCLE_DATE',$date);
        $this->assign('list',$Group);
        $this->display();
    }

    public function detail($id = 1){
        $date = C('CYCLE_DATE');
        $group = M('AuthGroup')->find($id);
        $cycleList = $this->cycleList();
        foreach ($date as $key => $value) {
            $type = M('ArticleTypeGroup')->where(array('group_id'=>$id))->join('__ARTICLE_TYPE__ on __ARTICLE_TYPE__.id = __ARTICLE_TYPE_GROUP__.type_id and __ARTICLE_TYPE__.cycle = '.$key)->select();
            $cycleCur = $cycleList[$key];
            foreach ($cycleCur as $k => $v) {
                if( $v[0] < time() && time() < $v[1] ){
                    $target = $v;
                }
            }
            $map['status'] = array('gt',0);
            $map['create_date'] = array('between',$target);
            foreach ($type as $k => $v) {
                $map['type_id'] = $v['id'];
                $type[$k]['exist'] = M('Article')->where($map)->count();
            }
            $list[$key]['type'] = $type;
            $list[$key]['name'] = $value;
        }
        $this->assign('list',$list);
        $this->assign('date',$date);
        $this->assign('group',$group);
        $this->display();
    }

    public function cycleList(){
        $data = S('cycleList');
        if( !$data ) {
            $date = C('CYCLE_DATE');
            $start_date = date('Y-1-1 00:00:00');
            $end_date = date('Y-12-31 23:59:59');
            $data[] = array();
            foreach ($date as $key => $value) {
                $cur_date = strtotime($start_date);
                $list = array();
                while (1) {
                    $_t = array();
                    $_t[] = $cur_date;
                    switch ($key) {
                        case '1':
                            $step = '+1weeks';
                            break;
                        case '2':
                            $step = '+2weeks';
                            break;
                        case '3':
                            $step = '+1months';
                            break;
                        case '4':
                            $step = '+3months';
                            break;
                        case '5':
                            $step = '+6months';
                            break;
                        case '6':
                            $step = '+1years';
                            break;
                        default:
                            break;
                    }
                    $cur_date = strtotime(date('Y-m-d H:i:s', $cur_date) . $step);
                    $_t[] = $cur_date - 1;
                    $list[] = $_t;
                    if ($cur_date > strtotime($end_date)) {
                        break;
                    }
                }
                $data[$key] = $list;
            }
            S('cycleList', $data , 3600 * 24);
        }
        return $data;
    }

}