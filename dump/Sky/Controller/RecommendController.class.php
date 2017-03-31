<?php
namespace Sky\Controller;

class RecommendController extends PublicController {

    protected $Model = 'ArticleRecommend';
    protected $error_msg = '请选择要进行操作的推荐';

    public function News(){

        $keyword = I('post.keyword');
        if( $keyword ){
            $condition['article_title'] = array('like',"%{$keyword}%");
        }

        $type_id = I('get.type_id');
        if( $type_id ){
            $map['article_type_id'] = $type_id;
            $condition['article_type_id'] = $type_id;
        }
        $week_ago = time() - (3600*24*7);
        $map['article_create_date']  = array('gt',$week_ago);
        $map['sort'] = 0 ;
        $map['status'] = 1 ;
        $wait = M('ArticleRecommend')->where($map)->select();
        $this->assign('wait',$wait);

        $map['sort'] = array('neq',0);
        if( $type_id ){
            $map['header'] = 0;
        }else{
            $map['header'] = 1;
        }
        $ing = M('ArticleRecommend')->where($map)->order('sort asc')->select();
        $this->assign('ing',$ing);

        $this->ListRecord( M('ArticleRecommend') , $condition , 'status asc,create_date desc');
        $this->display();

    }

    public function Sort(){
        if(IS_AJAX){
            $type_id = I('post.type_id');
            if( $type_id == '0' ){
                $header = 1;
            }else{
                $header = 0;
            }
            $art_id = I('post.art_id');
            foreach ($art_id as $key => $value) {
                M('ArticleRecommend')->where(array('article_id'=>$value))->save(array('update_date'=>time(),'sort'=>($key+1),'admin_id'=>is_admin(),'header'=>$header));
                M('Article')->where(array('id'=>$value))->save(array('recommend'=>2,'update_date'=>time()));
            }
            $out_id = I('post.out_id');
            foreach ($out_id as $key=>$value) {
                M('ArticleRecommend')->where(array('article_id'=>$value))->save(array('update_date'=>time(),'sort'=>0,'admin_id'=>is_admin(),'header'=>$header));
                M('Article')->where(array('id'=>$value))->save(array('recommend'=>3,'update_date'=>time()));
            }
            $res = call_user_func('change_recommend',$type_id,$art_id);
            if( $res ){
                $this->success('推荐成功');
            }else{
                $this->error('推荐失败');
            }
        }
    }

}