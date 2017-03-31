<?php
namespace Sky\Controller;
class LetterController extends PublicController {

    public function __construct(){
        parent::__construct();
        $status = array(
            '-1' => '待审核' ,
            '0'  => '待接收' ,
            '1'  => '转交相关部门处理' ,
            '2'  => '处理中' ,
            '3'  => '已处理完' ,
            );
        $this->assign(array(
            'status' => $status,
        ));
    }

    public function Index(){

        $get = I('get.');
        if($get['start_date'] || $get['end_date']){
            $map[] = "  datetime between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }
        if(strlen($get['keyword'])){
            $map['title'] = array('like','%'.$get['keyword'].'%');
        }
        $this->assign('get',$get);

        $status               = I('get.status');
        if(!empty($status))
        $map['status']        = array('in',$status);
        $group                = getGroup();
        $group_idArr          = array_column($group,'id');
        if(strlen($get['group'])){
            $map['department_id'] = $get['group'];
        }else{
            $map['department_id'] = array('in',$group_idArr);
        }

        $this->assign('group',$group);
        $this->ListRecord( M('Letter') , $map  , 'datetime desc');
        $this->display();
    }

    public function LetterEdit(){
        // 144:信访局
        $info = D('Letter')->get_detail(I('get.id'));
        $ids = I('post.ids');
        (empty($info) && empty($ids)) && $this->redirect('Letter/Index');
        $last_history = end($info['history']);
        $group = getGroup();
        $group_idArr = array_column($group,'id');
        if(IS_AJAX){
            /*if (IS_GET) {
                M('Letter')->where(array('department_id'=>array('in',$group_idArr),  'id'=>$info['id']))->setField('is_public',($info['is_public']==1?0:1));
                $this->success('改变公开状态成功');
                exit;
            }
            if(!empty($ids)){
                M('Letter')->where(array('id'=>array('in',$ids)))->setField('is_public',1);
                $this->success('改变公开状态成功');
                exit;
            }*/
            $post = I('post.');
            if($info['status'] < 3 ){
                $data = array(
                     'letter_id'       => $info['id'],
                     'department_id'   => $info['department_id'],
                     'admin_name'      => get_field($info['department_id'],'AuthGroup','title'),
                     'update_time'     => NOW_TIME,
                     'status'          => $post['status'],
                     'remark'          => $post['remark'],
                     'old_target_time' => $last_history['target_time'] > 0 ? $last_history['target_time'] : calc_workday($info['datetime']),
                );
                $letter_history = M('letterHistory');
                switch ($post['status']){
                    case '1':
                        if(empty($post['turn_over_department_id'])){
                            $this->error('请选择转交部门');
                        }
                        if(empty($post['target_time'])){
                            $this->error('选择办结时间');
                        }
                        $data['turn_over_department_id'] = $post['turn_over_department_id'];
                        $data['target_time']             = strtotime($post['target_time']);

                        $letter_history->add($data);
                        M('letter')->where(array('department_id'=>array('in',$group_idArr), 'id'=>$info['id']))->save(array(
                            'department_id' => $post['turn_over_department_id'],
                            'status'        => 1,
                        ));
                        break;

                    case '2':
                        $data['target_time'] = calc_workday();
                        $letter_history->add($data);
                        M('letter')->where(array('department_id'=>array('in',$group_idArr), 'id'=>$info['id']))->save(array(
                            'status'        => 2,
                        ));
                        break;

                    case '3':
                        $data['result'] = $post['result'];
                        $letter_history->add($data);
                        M('letter')->where(array('department_id'=>array('in',$group_idArr), 'id'=>$info['id']))->save(array(
                            'status'        => 3,
                            'is_public'        => $post['public'],
                        ));
                        break;

                    default:
                        $this->error('数据异常');
                        break;
                    }
                $this->success('操作成功');
            }else{
                $result = M('letterHistory')->where(array('id'=>$last_history['id']))->save($post);
                if($result)
                    $this->success('修改成功');
                else
                    $this->error('修改失败');
            }
            exit;
        }

        $Model = D('LetterComment');
        $map = array('comment_content' => array('like','%'.I('get.keyword','').'%'));
        $map['letter_id'] = $info['id'];

        $count  = $Model->where($map)->count();

        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $Model->where($map)->order('datetime desc')->relation(true)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);

        // 对应部门
        if( in_array('144', $group_idArr) || in_array(ADMIN_GROUP_ID, $group_idArr)){
            $group = M('AuthGroup')->field('id,title')->select();
        }else{
            $group = M('AuthGroup')->field('id,title')->select(144);
        }
        $this->assign(array(
            'info'         => $info,
            'type'         => $type,
            'group'        => $group,
            'target_time'  => calc_workday(NOW_TIME),
            'last_history' => $last_history,
            ));
        $this->display();

    }

    public function setPublic(){
        if(IS_AJAX){
            $group = getGroup();
            $group_idArr = array_column($group,'id');
            if (IS_GET) {
                $id = I('get.id',0);
                $info = M('Letter')->find($id);
                empty($info) && $this->error('未选择任何数据');
                M('Letter')->where(array('department_id'=>array('in',$group_idArr),  'id'=>$info['id']))->setField('is_public',($info['is_public']==1?0:1));
                $this->success('改变公开状态成功');
                exit;
            }
            $ids = I('post.ids');
            if(!empty($ids)){
                M('Letter')->where(array('id'=>array('in',$ids),array('department_id'=>array('in',$group_idArr))))->setField('is_public',1);
                $this->success('改变公开状态成功');
                exit;
            }
        }
    }

    public function del(){

        if(IS_POST){
            $ids = I('post.ids');
            if(empty($ids)){
                $this->error('请选择数据');
                exit;
            }
            $where = array('in',$ids);
        }else{
            $id = I('get.id',0);
            if(empty($id)){
                $this->error('删除失败');
                exit;
            }
            $where = $id;
        }
        // dump($where);exit;
        if(I('get.letter') == '1'){
            M('letter')->where(array('id'=>$where))->delete();
            M('letterComment')->where(array('letter_id'=>$where))->delete();
        }else{
            M('letterComment')->where(array('id'=>$where))->delete();
        }

        $this->success('删除成功');
    }



    public function check(){
        if(IS_AJAX){
            if(IS_POST){
                $post = I('post.');
                if(!empty($post['ids'])){
                    $where = array('id'=>array('in',$post['ids']));
                    M('letter')->where($where)->setField('status',0);
                    $this->success('审核成功',U(''));
                    exit;
                }
                $content = M('letter')->where(array('id'=>$post['id']))->getField('content');
                $this->ajaxReturn(htmlspecialchars_decode($content));
            }else{
                $get = I('get.');
                $result = M('Letter')->where(array('id'=>$get['id']))->setField('status',$get['status']);
                if($result)
                    $this->success('操作成功');
                else
                    $this->error('操作失败');
            }
            exit;
        }

        $this->ListRecord( M('Letter') , array('title' => array('like','%'.I('get.keyword','').'%') , 'status'=>-1) , 'datetime desc');
        $this->display();
    }


    public function comment(){
        $get = I('get.');
        if(IS_AJAX){
            $result = M('LetterComment')->where(array('id'=>$get['id']))->setField('status',$get['status']);
            if($result)
                $this->success('操作成功');
            else
                $this->error('操作失败');
            exit;
        }

        $Model = D('LetterComment');
        $map = array('comment_content' => array('like','%'.I('get.keyword','').'%'));
        if($get['letter_id']){
            $map['letter_id'] = $get['letter_id'];
        }

        $count  = $Model->where($map)->count();

        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = $order ? $order : 'id desc';
        $list = $Model->where($map)->order('datetime desc')->relation(true)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }


}