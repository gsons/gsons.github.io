<?php
namespace Sky\Controller;
use Think\Controller;

class MemberController extends PublicController {

    protected $Model = 'Admin';
    protected $error_msg = '请选择要进行操作的管理员';

    public function Admin(){

        if( I('get.keyword')){
            $keyword = I('get.keyword');
            $map['admin'] = array('like',"%{$keyword}%");
        }
        $this->ListRecord( M('Admin') , $map , 'create_date desc' );
        $this->display();
    }

    public function Adminedit(){
        if (IS_AJAX) {
            $Admin = D('Admin');
            if (IS_GET) {
                $id = I('get.id');
                if ($id == ADMIN_USER_ID) {
                    $this->error('超级管理员不允许删除！');
                    exit;
                }
            }
            $this->ajaxReturn($this->ActiveRecord( $Admin ,I('') ) );
        } else {
            $gid = I('get.id');
            if ($gid == ADMIN_USER_ID) {
                $this->error('超级管理员不允许做修改！');
                exit;
            }
            $Admin = M('Admin')->find($gid);
            $Group = M('AuthGroup')->field('id,title')->select();
            $this->Group = $Group;
            $this->Admin = $Admin;
            $this->display('Adminedit');
        }
    }


    public function SelectGroup(){

        if(IS_AJAX){
            $group_id = I('post.group_id');
            if( empty($group_id) ){
                $this->error('请选择部门');
            }
            M('AuthGroupAccess')->where(array('uid'=>I('get.id')))->delete();
            foreach ($group_id as $key => $value) {
                $data[] = array(
                    'uid' => I('get.id'),
                    'group_id' => $value,
                );
            }
            $res = M('AuthGroupAccess')->addAll($data);
            if( $res ){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }

        }

        $group = getGroup(true);
        $this->assign('group',$group);
        $list = M('AuthGroupAccess')->where(array('uid'=>I('get.id')))->join('__AUTH_GROUP__ on __AUTH_GROUP__.id = __AUTH_GROUP_ACCESS__.group_id')->select();
        $this->assign('list',$list);
        $this->display('Responsibility/Group');
    }

    public function Group(){
        if( I('get.keyword')){
            $keyword = I('get.keyword');
            $map['title'] = array('like',"%{$keyword}%");
        }
        $this->ListRecord( M('AuthGroup') , $map  , 'id desc' , '' , true, false, 200);
        $this->display('Group');
    }

    public function Groupedit(){

        if(IS_AJAX) {
            $AuthGroup = D('AuthGroup');
            if(IS_GET){
                $id = I('get.id');
                if($id == ADMIN_GROUP_ID ){
                    $this->error('超级管理组不允许删除！');
                }
                $count = M('AuthGroupAccess')->where(array('group_id'=>$id))->count();
                if( $count > 0 ){
                    $this->error('管理组下尚存在管理员，不可删除');
                }
            }else{
                $rules = I('post.rules');
                foreach ($rules as $key => $value) {
                    if( !$value ){
                        unset($rules[$key]);
                    }
                }
                $_POST['rules'] = $rules;
            }
            $this->ajaxReturn($this->ActiveRecord(  $AuthGroup , I('') ));
        }else{

            $gid = I('get.id');
            if ($gid ==  ADMIN_GROUP_ID ) {
                $this->error('超级管理组不允许做修改！');
                exit;
            }
            $info = M('AuthGroup')->find($gid);
            $Authlist = M('Skymenu')->field('id,name,pid,group')->select();
            foreach ($Authlist as $key => $value) {
                if( $value['group'] != '' ){
                    $root[] = $value;
                    unset($Authlist[$key]);
                }
            }
            foreach ($root as $key => $value) {
                $group[] = $value['group'];
            }
            $group = array_unique($group);
            foreach ($group as $key => $value) {
                $top[] = array('id'=>'','name'=>$value);
            }

            foreach ($top as $key => $value) {
                foreach ($root as $k => $v) {
                    if ($value['name'] == $v['group']) {
                        $top[$key]['child'][] = $v;
                    }
                }
            }

            foreach ($Authlist as $key => $value) {
                if( $value['pid'] == '0' ){
                    $top[] = $value;
                    unset($Authlist[$key]);
                }
            }
            foreach ($top as $key => $value) {
                foreach ($Authlist as $k => $v) {
                    if ($value['id'] == $v['pid']) {
                        $top[$key]['child'][] = $v;
                    }
                }
            }

            $this->assign('top',$top);
            $this->assign('info',$info);
            $this->assign('type',M('AuthGroupCategory')->order('sort asc')->select());
            $this->display('Groupedit');

        }
    }

    public function SetRules(){
        if(IS_AJAX){
            $ids = '';
            parse_str(urldecode($_POST['ids']));
            if( !$ids ){
                $this->error('尚未选择相关部门');
            }
            foreach ($_POST['rules'] as $key=>$value) {
                if( $value ){
                    $rules[] = $value;
                }
            }
            $rules = implode(',',$rules);
            if( M('AuthGroup')->where(array('id'=>array('in',$ids)))->save(array('rules'=>$rules)) ){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }

        $Authlist = M('Skymenu')->field('id,name,pid,group')->select();
        foreach ($Authlist as $key => $value) {
            if( $value['group'] != '' ){
                $root[] = $value;
                unset($Authlist[$key]);
            }
        }
        foreach ($root as $key => $value) {
            $group[] = $value['group'];
        }
        $group = array_unique($group);
        foreach ($group as $key => $value) {
            $top[] = array('id'=>'','name'=>$value);
        }

        foreach ($top as $key => $value) {
            foreach ($root as $k => $v) {
                if ($value['name'] == $v['group']) {
                    $top[$key]['child'][] = $v;
                }
            }
        }

        foreach ($Authlist as $key => $value) {
            if( $value['pid'] == '0' ){
                $top[] = $value;
                unset($Authlist[$key]);
            }
        }
        foreach ($top as $key => $value) {
            foreach ($Authlist as $k => $v) {
                if ($value['id'] == $v['pid']) {
                    $top[$key]['child'][] = $v;
                }
            }
        }

        $this->assign('top',$top);
        $this->display();
    }

    public function Info(){
        $Admin = M('Admin')->find(is_admin());
        if(IS_AJAX){
            $data = I('post.');
            $data['id'] = is_admin();
            $this->ajaxReturn( $this->ActiveRecord( D('Admin') , $data ) );
        }
        $this->assign('Admin',$Admin);
        $this->display();
    }

    public function GroupMember(){
        $group_id = I('get.id');
        $uid = M('AuthGroupAccess')->where(array('group_id'=>$group_id))->select();
        foreach ($uid as $key => $value) {
            $ids[] = $value['uid'];
        }
        $this->ListRecord( M('Admin') , array('id'=>array('in',$ids ? $ids : '')) );
        $this->display();
    }

}