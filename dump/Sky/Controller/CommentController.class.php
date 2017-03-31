<?php
namespace Sky\Controller;
use Home\Controller\PublicController;

class CommentController extends PublicController {

    public function comment()
    {
        $Commentdata = M('Comment')->select();
        $User = M('User')->select();
        $Article = M('Article')->select();
        foreach($Commentdata as $key=>$value){
            foreach($User as $k=>$v){
                if($value['user_id'] == $v['id'] ){
                    $Commentdata[$key]['e-mail'] = $v['e-mail'];
                }
            }
            foreach($Article as $k=>$v){
                if($value['article_id'] == $v['id'] ){
                    $Commentdata[$key]['title'] = $v['title'];
                    $Commentdata[$key]['eng_title'] = $v['eng_title'];
                }
            }
        }
        $this->Comment = $Commentdata;

        $this->display('Comment');
    }

    public function setStatus(){

        if(IS_AJAX){

            $User = D('Comment');
            $data = I('get.');
            $res = $User->setStatus($data);
            $this->ajaxReturn($res);

        }

    }
}