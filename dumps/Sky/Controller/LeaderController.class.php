<?php
namespace Sky\Controller;

class LeaderController extends PublicController {

    protected $Model = 'Article';
    protected $error_msg = '请选择要进行操作的领导';

    public function Index(){

        $get = I('get.');
        if($get['start_date'] || $get['end_date']){
            $where[] = "  create_date between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }

        if(strlen($get['keyword'])){
            $where['title'] = array('like','%'.$get['keyword'].'%');
        }

        if($get['status'] == '1' ){
            $where['status'] = array('gt','0');
        }else if( $get['status'] == '0' ) {
            $where['status'] = array('elt','0');
        }

        $where['special_id'] = 0;
        if(in_array($get['type'], array(1480,1481,1482,1483,1484))){

            $where['type_id'] = $get['type'] ;
        }else{
            $where['type_id'] = array('in',array(1480,1481,1482,1483,1484));
        }


        $this->assign(array(
            'get'   => $get,
            'group' => $group,
            'type' => M('ArticleType')->where(array('id'=>array('in',array(1480,1481,1482,1483,1484))))->field('id,name')->select(),
        ));

        $this->ListRecord( M('Article') , $where , 'sort desc,create_date desc');
        $this->display('Leader/Article');
    }

    public function ArticleEdit(){
        if( I('get.id') ){
            $info = M('Article')->find(I('get.id'));
            $this->assign('info',$info);
        }
        if(IS_AJAX){
            $data = I('');
            $res =  $this->ActiveRecord( D('Article') , $data  ) ;
            if($res['status'] == '1') {
                $article_id = $res['id'] ? $res['id'] : I('post.id');
                cookie('HTTP_PREFER',U('Leader/Index'));
                $res['url'] = U('User/Prefer',array('id'=>$article_id,'controller'=>'Leader'));
            }
            $this->ajaxReturn($res);
        }

        $type = M('ArticleType')->where(array('id'=> array('in',array(1480,1481,1482,1483,1484))))->select();

        $this->assign('type',$type);
        $this->assign('pid',I('pid'));
        $group = getGroup();
        $this->assign('group',$group);
        $this->display('ArticleEdit');
    }


}