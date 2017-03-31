<?php
namespace Sky\Controller;

class RepositoryController extends PublicController {

    protected $Model = 'Repository' ;
    protected $error_msg = '请选择要操作的知识问答';

    public function Repository(){
        $get = I('get.');
        $where = array();
        if($get['start_date'] || $get['end_date']){
            $where[] = "  create_date between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }
        if(strlen($get['keyword'])){
            $where['title'] = array('title'=>array('like','%'.$get['keyword'].'%'));
        }
        // dump(array($time,$title));exit;
        $this->ListRecord( M('Repository') , $where , 'create_date desc');
        $this->display();
    }

    public function RepositoryEdit(){
        if( I('get.id') ){
            $info = M('Repository')->find(I('get.id'));
            $this->assign('info',$info);
        }

        if(IS_AJAX){
            if( $_POST['video_src'] && $_POST['video_name'] ){
                $_POST['video'] = json_encode(array(
                    'src' => $_POST['video_src'],
                    'name' => $_POST['video_name'],
                ));
                unset( $_POST['video_src']);
                unset( $_POST['video_name']);
            }
            $this->ajaxReturn( $this->ActiveRecord(D('Repository') , I('') , U('Repository/Repository') ) );
        }


        $type = M('RepositoryType')->where(array('status'=>1))->field('id,name')->select(); ;


        $this->assign('type',$type);
        $this->display();

    }

    public function Type(){
        $this->ListRecord( M('RepositoryType') , '' , 'create_date desc');
        $this->display();
    }

    public function TypeEdit(){
        if( I('get.id') ){
            $info = M('RepositoryType')->find(I('get.id'));
            $this->assign('info',$info);
        }
        if(IS_AJAX){
            $this->ajaxReturn( $this->ActiveRecord(D('RepositoryType') , I('') , U('Repository/type') ) );
        }
        $this->display();

    }

}