<?php
namespace Sky\Controller;

class CollectController extends PublicController {

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

        if($get['status'] == '1' ){
            $where['status'] = array('gt','0');
        }else if( $get['status'] == '0' ) {
            $where['status'] = array('elt','0');
        }

        if( $get['special_id'] ){
            $where['special_id'] = $get['special_id'];
            $type_where = array('special_id'=>$get['special_id']);
        }else{
            $where['special_id'] = 0;
            $type_where = array('special_id'=>0);
        }

        $where['group_id'] = $get['group'] > 0 ? $get['group'] : array('in',$groupArr);
        $where['type_id'] = 2104;
        $this->assign(array(
            'get'   => $get,
            'group' => $group,
            'type' => M('ArticleType')->where($type_where)->field('id,name')->select(),
        ));

        $this->ListRecord( M('Article') , $where , 'create_date desc');
        $this->display('Article');
    }

    public function ArticleEdit(){

        if( I('get.id') ){
            $info = M('Article')->where(array('id'=>I('get.id'),'type_id'=>2104))->find();

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

            $this->assign('info',$info);
        }

        if(IS_AJAX){
            if( $_POST['video_src'] && $_POST['video_name'] ){
                $_POST['video'] = json_encode(array(
                    'src'  => $_POST['video_src'],
                    'name' => $_POST['video_name'],
                ));
                unset( $_POST['video_src']);
                unset( $_POST['video_name']);
            }
            $data               = I('');
            $data['special_id'] = 0;
            $data['type_id']    = 2104;

            cookie('HTTP_PREFER',U('Collect/Article'));
            $res =  $this->ActiveRecord( D('Article') , $data  ) ;
            $article_id = $res['id'] ? $res['id'] : I('post.id');
            $res['url'] = U('User/Prefer',array('id'=>$article_id,'controller'=>'Collect'));

            $this->ajaxReturn($res);
        }

        $group = getGroup();
        $this->assign('group',$group);
        $this->display('ArticleEdit');

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


    public function comment(){
        $get = I('get.');
        if(IS_AJAX){
            $result = M('ArticleComment')->where(array('id'=>$get['id']))->setField('status',$get['status']);
            if($result)
                $this->success('操作成功');
            else
                $this->error('操作失败');
            exit;
        }

    }
}