<?php
namespace Home\Controller;
use Think\Verify;

class ApplyController extends PublicController{

    public function Department(){

        $group = M('AuthGroup')->select();
        $category = M('AuthGroupCategory')->select();
        foreach ($category as $k => $v) {
            foreach ($group as $key => $value) {
                if( $value['category_id'] == $v['id'] ){
                    $category[$k]['child'][] = $value;
                }
            }
        }

        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department'))
        );
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息依申请公开系统');
        $this->assign('category',$category);
        $this->display();
    }

    public function Agree(){
        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息依申请公开系统');
        $this->display();
    }

    public function Rules(){
        $id = I('get.id');
        if( !$id || !is_numeric($id) ){
            $this->error('参数有误');
        }
        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息依申请公开系统');
        $this->display();
    }

    public function Type(){
        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息依申请公开系统');
        $this->display();
    }

    public function Personal(){
        $type = I('get.type');
        $department_id = I('get.id');
        $department_name = M('AuthGroup')->where(array('id'=>$department_id))->getField('title');
        $this->assign('name',$department_name);

        if(IS_AJAX){

            $agree = I('post.agree');
            if( $agree != '1' ){
                $this->error('请同意承诺所获取的政府信息,只用于自身的特殊需要,不作任何炒作及随意扩大公开范围');
            }

            $data = I('');

            if( $data['usage_attach'] && $data['usage_name'] )
                $data['usage_attach'] = json_encode(array($data['usage_attach'],$data['usage_name']));
            unset($data['usage_attach']);
            unset($data['usage_name']);
            if( $data['discount_attach'] && $data['discount_name'] )
                $data['fee_attach'] = json_encode(array($data['discount_attach'],$data['discount_name']));
            unset($data['discount_attach']);
            unset($data['discount_name']);

            if( $data['fee_waivers'] == '1' && !$data['fee_attach'] ){
                $this->error('请上传申请减免的相关证明');
            }

            $data['apply_number'] = getApplyNumber();

            $res =  $this->ActiveRecord( D('Personal') , $data );
            if( $res['status'] == '1' ){
                $res['url'] = U('Success',array('id'=>$res['id']));
            }
            $this->ajaxReturn( $res );
        }

        $this->assign('type',$type);
        $this->assign('department_id',$department_id);

        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('meta_title','政务信息依申请公开系统');
        $this->display();
    }

    public function Organization(){

        $type = I('get.type');
        $department_id = I('get.id');
        $department_name = M('AuthGroup')->where(array('id'=>$department_id))->getField('title');
        $this->assign('name',$department_name);

        if(IS_AJAX){

            $agree = I('post.agree');
            if( $agree != '1' ){
                $this->error('请同意承诺所获取的政府信息,只用于自身的特殊需要,不作任何炒作及随意扩大公开范围');
            }

            $data = I('');

            if( $data['usage_attach'] && $data['usage_name'] )
                $data['usage_attach'] = json_encode(array($data['usage_attach'],$data['usage_name']));
            unset($data['usage_attach']);
            unset($data['usage_name']);

            $data['apply_number'] = getApplyNumber();

            $res =  $this->ActiveRecord( D('Organization') , $data );
            if( $res['status'] == '1' ){
                $res['url'] = U('Success',array('id'=>$res['id']));
            }
            $this->ajaxReturn( $res );
        }

        $breadcrumb = array(
            array('name'=>'政务公开','link'=>U('Open/Index')),
            array('name'=>'政府信息依申请公开系统','link'=>U('Apply/Department')),
        );
        $this->assign('breadcrumb',$breadcrumb);

        $this->assign('type',$type);
        $this->assign('department_id',$department_id);
        $this->assign('meta_title','政务信息依申请公开系统');

        $this->display();
    }

    public function Success(){

        $id = I('get.id');
        if( !$id || !is_numeric($id) ){
            $this->error('参数有误');
        }
        $apply = M('Apply','open_','DB_OPEN')->field('id,apply_number')->find($id);
        $this->assign('apply',$apply);

        $apply_number[] = $apply['apply_number'];
        $apply_history = json_decode(cookie('apply_number_history'),true);
        if( $apply_history ) {
            $apply_history = array_merge($apply_history, $apply_number);
        }else{
            $apply_history = $apply_number;
        }
        $apply_history = array_unique($apply_history);
        cookie('apply_number_history',json_encode($apply_history));
        $this->assign('meta_title','成功提交-政务信息依申请公开系统');

        $this->display();

    }

    public function Result(){

        $identity_number = I('post.identity_number');
        $apply_number = I('post.apply_number');

        if( !$identity_number && !$apply_number ){
            $this->error('参数错误');
        }

        $Model = M('Apply','open_','DB_OPEN');
        $info = $Model->where(array('identity_number'=>$identity_number,'apply_number'=>$apply_number))->find();
        if( !$info ){
            $this->error('查无记录');
        }
        $history = M('ApplyHistory','open_','DB_OPEN')->where(array('apply_id'=>$info['id']))->order('create_date desc')->select();
        $this->assign('history',$history);

        $this->assign('info',$info);
        $this->assign('meta_title','查询结果-政务信息依申请公开系统');
        $this->display();

    }


}