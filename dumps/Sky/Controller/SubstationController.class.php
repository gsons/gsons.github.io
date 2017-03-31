<?php
namespace Sky\Controller;

class SubstationController extends TypeController {

    protected $Model = 'Special';
    protected $error_msg = '请选择要进行操作的专题';
    /**
     * 1   进行中
     * 2   未开始
     * 3   已过期
     */
    public function Index(){
        $type = I('get.type');
        switch ($type) {
            case '2':
                $where = array('start_time'=>array('GT',NOW_TIME));
                break;

            case '3':
                $where = array('end_time'=>array('LT',NOW_TIME));
                break;

            default:
                $where = array('start_time'=>array('LT',NOW_TIME),'end_time'=>array('GT',NOW_TIME));
                break;
        }


        $where['web'] = 1;
        $group = M('AuthGroupAccess')->where(array('uid'=>is_admin()))->select();
        foreach ($group as $key => $value) {
            if( $value['group_id'] == ADMIN_GROUP_ID ){
                $all = true;
            }
        }
        if( !$all ){
            $group = getGroup();
            foreach ($group as $key => $value) {
                $group_id[] = $value['id'];
            }
            $access = M('SpecialAccess')->where(array('group_id'=>array('in',$group_id)))->select();
            foreach ($access as $key => $value) {
                $special_id[] = $value['special_id'];
            }
            $where['id'] = array('in',$special_id);
        }

        /*$group = M('AuthGroupAccess')->where(array('uid'=>is_admin()))->select();
        $idArr = array_column($group,'group_id');
        if(!in_array(ADMIN_GROUP_ID, $idArr)){
            $where['department_id'] = array('in',$idArr);
        }else{
            $where['department_id'] = array('GT',0);
        }*/
        $this->ListRecord( M('Special') , $where , 'create_date desc');
        $this->display();
    }

    public function SpecialArticle(){
        $special_id = I('get.special_id');
        empty($special_id) && $this->redirect('Substation/Index');
        $this->assign('special_id',$special_id);
        $this->Article();
    }


    public function Specialtype(){
        $type_id = I('get.id');
        empty($type_id) && $this->redirect('Substation/Index');
        $this->assign('special_id',$type_id);
        $this->Type(array('special_id'=>$type_id),'Substation/Tree');
    }

    public function Group(){
        $special_id = I('get.special_id');
        $group = getGroup(true);
        $this->assign('group',$group);
        if(IS_AJAX){
            $group_id = I('post.group_id');
            $res1 = M('SpecialAccess')->where(array('special_id'=>$special_id))->delete();
            foreach ($group_id as $key => $value) {
                $data[] = array(
                    'department_id' => $value,
                    'special_id' => $special_id
                );
            }
            $res2 = M('SpecialAccess')->addAll($data);
            if( $res1 !== false && $res2 ){
                $this->success();
            }else{
                $this->error();
            }
        }
        $info = M('SpecialAccess')->where(array('special_id'=>$special_id))->select();
        $group_id = '';
        foreach ($info as $key=>$value) {
            $group_id[] = $value['department_id'];
        }
        $list = M('AuthGroup')->where(array('id'=>array('in',$group_id)))->select();
        $this->assign('list',$list);
        $this->display('Special/Group');
    }

    public function Edit(){

        if( I('get.id') ){
            $info = M('special')->find(I('get.id'));
            $this->assign('info',$info);
        }

        if(IS_AJAX){
            $_POST['start_time'] = '2000-01-01';
            $_POST['end_time']   = '2035-01-01';
            $data               = I('post.');
            if (empty($data['department_id'])) {
                $this->error('请选择所属部门');
                exit;
            }
            $data['picture']    = json_encode($data['picture']);

            $this->ajaxReturn( $this->ActiveRecord(D('special') , $data , U('Substation/Index') ) );
        }

        $group = M('AuthGroupAccess')->where(array('uid'=>session('auid')))->select();
        $groupArr = array_column($group,'group_id');
        if(in_array(ADMIN_GROUP_ID,$groupArr))
            $department = M('AuthGroup')->field('id,title')->select();
        else
            $department = M('AuthGroup')->where(array('id'=>array('in',$groupArr)))->field('id,title')->select();

        $file_list = scandir(APP_PATH.'/Special/View');
        unset($file_list[0]);
        unset($file_list[1]);
        $this->assign('file_list',$file_list);
        $this->assign('department',$department);
        $this->display();

    }

    public function Manage(){

        $get   = I('get.');
        $title = M('special')->where(array('id'=>$get['id']))->getField('title');
        empty($title) && $this->redirect('Index');
        $type  = M('ArticleType')->where(array('special_id'=>$get['id']))->field('id,name')->select();
        $where = array('special_id'=>$get['id']);
        if($get['type_id']){
            $where['type_id'] = $get['type_id'];
        }

        if($get['start_date'] || $get['end_date']){
            $where[] = "  create_date between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }
        if(strlen($get['keyword'])){
            $where['title'] = array('like','%'.$get['keyword'].'%');
        }
        if($get['special_id'] > 0 ){
            $where['special_id'] = $get['id'];
        }

        $this->ListRecord( M('Article') , $where , 'create_date desc');

        $this->assign(array(
            'id'      => $get['id'],
            'type_id' => $get['type_id'],
            'title'   => $title,
            'type'    => $type,
            ));
        $this->display('NewPage');
    }

}