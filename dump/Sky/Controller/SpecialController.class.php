<?php
namespace Sky\Controller;

class SpecialController extends TypeController {

    protected $Model = 'Article';
    protected $error_msg = '请选择要进行操作的文章';
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
        $where['web'] = 0;
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
        $this->ListRecord( M('Special') , $where , 'create_date desc');
        $this->display();
    }

    public function SpecialArticle(){
        $special_id = I('get.special_id');
        empty($special_id) && $this->redirect('Special/Index');
        $this->assign('special_id',$special_id);
        $this->Article();
    }


    public function Specialtype(){
        $type_id = I('get.id');
        empty($type_id) && $this->redirect('Special/Index');
        $this->assign('special_id',$type_id);
        $this->Type(array('special_id'=>$type_id),'Special/Tree');
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
            $data = I('post.');
            $data['picture'] = json_encode($data['picture']);
            $this->ajaxReturn( $this->ActiveRecord(D('special') , $data , U('Special/Index') ) );
        }

        $group = M('AuthGroupAccess')->where(array('uid'=>is_admin()))->select();
        $department = M('AuthGroup')->where(array('id'=>array('in',array_column($group,'group_id'))))->field('id,title')->select();

        $file_list = scandir(APP_PATH.'/Special/View');
        unset($file_list[0]);
        unset($file_list[1]);
        $this->assign('file_list',$file_list);
        $this->assign('department',$department);
        $this->display();

    }

    public function delete_special(){
        if(IS_AJAX && IS_POST){
            $id = I('post.ids');
            if(empty($id)) $this->error( '请选择要进行操作的专题' );
            $data = M('Article')->where(array('special_id'=>array('in',$id)))->select();
            if(!empty($data))
                $this->error('请先删除专题下的所有文章');
            $this->ajaxReturn( $this->ActiveRecord ( D('special') , I('') , '' ) );
        }
    }

    public function enable_special(){
        if(IS_AJAX && IS_POST){
            $id = I('post.ids');
            if(empty($id)) $this->error( '请选择要进行操作的专题' );
            $this->ajaxReturn( D('special')->SelfSetField($id,'status',1) );
        }
    }

    public function disable_special(){
        if(IS_AJAX && IS_POST){
            $id = I('post.ids');
            if(empty($id)) $this->error( '请选择要进行操作的专题' );
            $this->ajaxReturn( D('special')->SelfSetField($id,'status',0) );
        }
    }



}