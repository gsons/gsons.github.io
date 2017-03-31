<?php
namespace Sky\Controller;

class InterviewController extends PublicController {

    protected $Model = 'Interview';
    protected $error_msg = '请选择要进行操作的在线访谈';

    public function Interview(){
        $this->ListRecord( M('Interview') , '' , 'create_date desc');
        $this->display();
    }

    public function InterviewEdit(){

        if( I('get.id') ){
            $info = M('Interview')->find(I('get.id'));
            $this->assign('info',$info);
        }
        if(IS_AJAX){
            $data = I('post.');
            if(empty($data['picture']))
                $this->error('请上传直播图片');
            $data['picture'] = json_encode($data['picture']);

            $this->ajaxReturn( $this->ActiveRecord(D('Interview') , $data , U('Interview/Interview') ) );
        }
        $this->display();
    }

}