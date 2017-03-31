<?php
namespace Home\Controller;

class InteractController extends PublicController{

    public function Index(){
        $id         = I('get.id',0);
        $p          = I('get.p',1);
        $num        = 20;
        $type       = M('LetterType')->select();

        $where      = array("is_public"=>1);
        if(in_array($id, array_column($type,'id')))
            $where["type_id"] = $id;

        $get  = I('get.');

        // print_r($where);exit;
        $model = D('Letter');
        $list  = $model->get_list($where,$p,$num);
        $count = $model->where($where)->count();
        $page  = new \Think\Bootpage($count,$num);
        $page->rollPage   = 5;
        $page->lastSuffix = false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("first","首页");
        $page->setConfig("last","尾页");

        // print_r($list);exit;
        $this->assign(array(
            'type'         => $type ,
            'id'           => $id ,
            'name'         => $get['name'] ,
            'index_number' => $get['index_number'] ,
            'list'         => $list ,
            'page'         => $page->show() ,
            ));

        $this->display('List');
    }

    public function Detail(){
        $id = I('get.id');
        // 信件详情
        $detail = D('Letter')->get_detail($id);
        // var_dump($detail);
        empty($detail) && $this->redirect('index');

        if (IS_POST) {
            if(!$this->check_code($_POST['code']))
                $this->error('验证码错误');
            $data = I('post.');
            if (empty($data['observer']))
                $this->error('请输入用户名');
            if (empty($data['comment_content']))
                $this->error('请输入评论');
            $data['letter_id']  = $id;
            $data['ip_address'] = get_client_ip();
            $data['datetime']   = NOW_TIME;
            $data['status']     = 0;
            $data['grade']      = $data['grade'] + 1;
            if(M('LetterComment')->add($data)){
                $this->success('评论成功',U('',array('id'=>$id)));
            }else{
                $this->error('评论失败',U('',array('id'=>$id)));
            }
        }

        $comment = M('LetterComment')->where(array('status'=>1,'letter_id'=>$id))->field('comment_content,datetime,grade')->select();
        $this->assign(array(
            'detail'  => $detail,
            'comment' => $comment,
            ));
        $this->display();
    }

    public function search(){
        $get = I('get.');

        if ( !empty($get['name']) && !empty($get['index_number']) ) {
            $where['name']         = $get['name'];
            $where['index_number'] = $get['index_number'];

            $detail = D('Letter')->where($where)->find();
            if(!empty($detail)){
                $comment = M('LetterComment')->where(array('status'=>1,'letter_id'=>$detail['id']))->field('comment_content,datetime,grade')->select();
                $this->assign(array(
                    'detail'  => $detail,
                    'comment' => $comment,
                    ));
                $this->display('Detail');
                exit();
            }
        }
        $this->redirect('Index');
    }


    public function Apply(){
        if(IS_POST){
            if(!$this->check_code($_POST['code']))
                $this->error('验证码错误');

            // dump($_POST);exit;
            $model = M('Letter');
            $rules = array(
                // 用户信息
                array('name','require','请输入姓名'),
                array('certificate_name','require','请选择证件类型'),
                array('identity_number','require','请输入证件号码'),
                array('telphone','require','请输入联系电话'),
                array('email','require','请输入邮箱'),
                array('email','email','邮箱格式不正确',0,'regex'),
                array('address','require','请输入联系地址'),

                // 信件信息
                array('department_id','require','请选择信访部门'),
                array('title','require','请输入主题'),
                array('content','require','请输入内容'),
                array('type_id','require','请选择问题类别'),
                );
            if($data = $model->validate($rules)->create(I('post.'))){
                $data['first_department_id'] = $data['department_id'];
                $data['status']              = 0;
                $data['is_public']           = 0;
                $data['datetime']            = NOW_TIME;
                $data['ip']                  = get_client_ip(1);
                $data['index_number']        = date('Ymdhis').rand_number(4);
                $model->add($data);
                // var_dump($data);exit;
                $this->assign('apply_number',$data['index_number']);
                $this->success('提交成功',U('Successs',array('num'=>think_encrypt($data['index_number'],'yunan'))));

            }else{
                $this->error($model->getError());
            }
            exit;
        }

        $department = M('AuthGroup')->field('id,title')->select();
        $type       = M('LetterType')->field('id,name')->select();
        $this->assign(array(
            'department' => $department ,
            'type'       => $type ,
            'id'         => I('get.id',1) ,
            ));
        $this->display();
    }


    public function Collect(){
        R('public/Lists',array('2104'));
    }

    public function Comment(){
        $id = I('get.id',0);
        if(IS_AJAX){
            $post = I('post.');
            if(!$this->check_code($post['code'])){
                $this->error('验证码错误');
            }
            $post['article_id'] = $id;
            $post['ip_address'] = get_client_ip();
            $post['datetime'] = NOW_TIME;
            $post['grade'] = $post['grade'] + 1;
            if(M('ArticleComment')->add($post)){
                $this->success('发表成功，请等待管理员审核');
            }else{
                $this->error('发表失败，请重新再试');
            }
            exit;
        }
        $commnet = M('ArticleComment')->where(array('article_id'=>$id,'status'=>1))->limit(3)->order('id desc')->select();
        // dump($commnet);exit;
        $this->assign(array(
            'id'      => $id,
            'commnet' => $commnet,
        ));
        $this->display('Interact/InteractComment');
    }

    public function Interview(){
        $p          = I('get.p',1);
        $num        = 20;

        $model = M('Interview');
        $list  = $model->field("id,title,interview_time,interview_guest,interview_description")->order('interview_time desc')->page($p,$num)->select();
        $count = $model->count();
        $page  = new \Think\Bootpage($count,$num);
        $page->rollPage   = 5;
        $page->lastSuffix = false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("first","首页");
        $page->setConfig("last","尾页");

        $this->assign(array(
            'list'         => $list ,
            'page'         => $page->show() ,
            ));
        $this->display('InterviewList');
    }

    public function InterviewDetails(){

        $id      = I('get.id');
        $model   = M('Interview');
        $details = $model->find($id);
        empty($details) && redirect('/');

        $picture = json_decode($details['picture'],true);
        $cover   = $picture[0];
        unset($picture[0]);

        $list  = $model->field("id,title,create_date")->order('interview_time desc')->page(1,8)->select();

        $this->assign(array(
            'details'    => $details ,
            'list'       => $list ,
            'cover'      => $cover ,
            'picture'    => $picture ,
            'meta_title' => $details['title'] ,
            ));
        $this->display('Interview');
    }


    public function Successs(){
        $num = I('get.num');
        $this->assign('num',think_decrypt($num,'yunan'));
        $this->display('Success');
    }
}