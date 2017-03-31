<?php
namespace Sky\Controller;

class ArticleController extends PublicController {

    protected $Model = 'Article';
    protected $error_msg = '请选择要进行操作的文章';

    public function Article(){
        $group = getGroup();

        $groupArr = array_column($group,'id');
        $groupArr[] = '0';
        $get = I('get.');
        if($get['start_date'] || $get['end_date']){
            $where[] = "  create_date between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }
        if(strlen($get['keyword'])){
            $where['title'] = array('like','%'.$get['keyword'].'%');
        }

        if($get['type'] > 0 ){

            $where[] = '(  type_id in ('.get_child_idArr($get['type'],',').') and type_id not in (1480,1481,1482,1483,1484) )' ;
        }else{
            $where['type_id'] = array('not in',array(1480,1481,1482,1483,1484));
        }

        if($get['status'] == '1' ){
            $where['status'] = array('gt','0');
        }else if( $get['status'] == '0' ) {
            $where['status'] = array('elt','0');
        }

        if( $get['special_id'] ){
            $where['special_id'] = $get['special_id'];
            $type_where = array('id'=>array('not in',array('1480','1481','1482','1483','1484')),'special_id'=>$get['special_id']);
        }else{
            $where['special_id'] = 0;
            $type_where = array('id'=>array('not in',array('1480','1481','1482','1483','1484')),'special_id'=>0);
        }


        $where['group_id'] = $get['group'] > 0 ? $get['group'] : array('in',$groupArr);
        $this->assign(array(
            'get'   => $get,
            'group' => $group,
            'type' => M('ArticleType')->where($type_where)->field('id,name')->select(),
        ));

        $this->ListRecord( M('Article') , $where , 'create_date desc');
        $this->display('Article/Article');
    }

    public function ArticleEdit(){
        if( I('get.id') ){
            $info = M('Article')->find(I('get.id'));
            if($info['type_id'] == '2104'){
                $Model = D('ArticleComment');
                $map = array('comment_content' => array('like','%'.I('get.keyword','').'%'));
                $map['letter_id'] = $info['id'];

                $count  = $Model->where($map)->count();

                $Page = new \Think\Bootpage($count,$this->getPerpage());
                $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
                $show = $Page->show();
                $order = 'id desc';
                $list = $Model->where($map)->order('datetime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
                $this->assign('list',$list);
                $this->assign('page',$show);
            }
            $this->assign('info',$info);
        }
        $special = I('get.special',($info['special_id'] ? $info['special_id'] : 0 ) );
        if(IS_AJAX){
            if(IS_GET){
                $id = I('get.id');
                if( $id ) {
                    $static = M('Article')->where(array('id' => $id))->getField('static');
                    if ($static) {
                        $this->error('静态文章不允许删除！');
                    }
                }else{
                    //{$info.special_id|get_field='special','title'}{:get_nav($info['type_id'],false)}
                    $sp_title = get_field($info['special_id'],'Special','title');
                    $this->ajaxReturn( array( 'msg'=> $sp_title ? $sp_title.'>' : '' . substr(get_nav(I('get.type_id'),false),1)));
                }
            }
            if( $_POST['video_src'] && $_POST['video_name'] ){
                $_POST['video'] = serialize(array(
                    'src'  => I('post.video_src'),
                    'name' => I('post.video_name'),
                ));
                unset( $_POST['video_src']);
                unset( $_POST['video_name']);
            }
            $data = I('');
            $data['special_id'] = $special;
            if( $special ){
                $_p = M('Special')->find($special);
                if( $_p['department_id'] ){
                    cookie('HTTP_PREFER',U('Substation/Manage',array('type'=>I('post.type_id'),'id'=>$special)));
                }else{
                    cookie('HTTP_PREFER',U('Special/SpecialArticle',array('type'=>I('post.type_id'),'id'=>$special)));
                }
            }else{
                cookie('HTTP_PREFER',U('Article/Article',array('type'=>I('post.type_id'))));
            }
            $res =  $this->ActiveRecord( D('Article') , $data  ) ;
            if($res['status'] == '1') {
                $article_id = $res['id'] ? $res['id'] : I('post.id');
                $ext = I('post.complicate');
                if ($ext) {
                    $fail = '';
                    $success = 0;
                    $ext_id = I('post.ext_type_id');
                    $article = M('Article')->find($article_id);
                    $ext_type = M('ArticleType')->where(array('id'=>array('in',$ext_id)))->select();
                    foreach ($ext_type as $key => $value) {
                        $copy = $article;
                        unset($copy['type_id']);
                        unset($copy['id']);
                        $copy['special_id'] = 0;
                        if( $value['special_id'] ){
                            $copy['type_id'] = $value['id'];
                            $copy['special_id'] = $value['special_id'];
                        }else{
                            $copy['type_id'] = $value['id'];
                        }
                        $_res = M('Article')->add($copy);
                        if( !$_res ){
                            $fail .= $fail ? ','.$value['name'] : $value['name'];
                        }else{
                            $success++;
                        }
                    }
                    $res['info'] .= '，且额外共有'.$success.'个栏目成功发布';
                    $res['info'] .= $fail ? '。其中'.$fail.'发布失败' : '' ;
                }
                $res['url'] = U('User/Prefer',array('id'=>$article_id));
            }
            $this->ajaxReturn($res);
        }

        $pid = I('pid');

        if(!empty($pid)){
            $type = M('ArticleType')->where(array('id'=>array('not in',array(1480,1481,1482,1483,1484)),'special_id'=> $special))->select($pid);
        }else{
            $type = array();
            get_category2('ArticleType','id','name','pid',0,$type,array('id'=>array('not in',array(1480,1481,1482,1483,1484)),'special_id'=> $special));
        }

        $this->assign('type',$type);
        $group = getGroup();
        $this->assign('group',$group);
        $this->display('Article/ArticleEdit');
    }

    public function Single(){
        $this->assign('list',M('Article')->where(array('static'=>1))->select());
        $this->assign('static',1);
        $this->display('Article');
    }

    public function Recommend(){
        $id = I('get.id');
        $article = M('Article')->find($id);
        if( $article['status'] != '1' ){
            $this->error('文章当前处于无法申请推荐的状态');
        }
        $info = M('ArticleRecommend')->where(array('article_id'=>$article['id']))->find();
        $this->assign('info',$info);
        if(IS_AJAX){
            $recommend = array(
                'id'                  => $info['id'],
                'article_id'          => $id,
                'article_title'       => $article['title'],
                'article_type_id'     => $article['type_id'],
                'article_create_date' => $article['create_date'],
                'reason'              => I('post.reason'),
                'level'               => I('post.level'),
            );
            $res = $this->ActiveRecord( D('ArticleRecommend') , $recommend , '' );
            if( $res['status'] == '1' ){
                @M('Article')->where(array('id'=>$id))->save(array('recommend'=>1));
            }
            $this->ajaxReturn( $res );
        }
        $this->assign('SITE_TITLE',$article['title'].'——申请推荐');
        $this->display();
    }

}