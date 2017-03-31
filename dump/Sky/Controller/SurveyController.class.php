<?php
namespace Sky\Controller;

class SurveyController extends PublicController {

    protected $Model = 'vote';
    protected $error_msg = '请选择要进行操作的调查';
    /**
     * 1   进行中
     * 0   未开始
     * 2   已过期
     */
    public function Index(){
        $type = I('get.status');
        switch ($type) {
            case '0':
                $where = array('start_time'=>array('GT',NOW_TIME));
                break;

            case '2':
                $where = array('end_time'=>array('LT',NOW_TIME));
                break;

            default:
                $where = array('start_time'=>array('LT',NOW_TIME),'end_time'=>array('GT',NOW_TIME));
                break;
        }
        // dump($where);exit;
        $this->ListRecord( M('Questions') , $where , 'create_date desc');
        $this->display();
    }

    public function Edit(){

        if( I('get.id') ){
            $info = D('questions')->relation(true)->find(I('get.id'));
            // dump($info);exit;
            $this->assign('info',$info);
        }

        if(IS_AJAX){



            // dump(I('post.'));exit;
            $data       = I('post.');
            $questions  = D('questions');
            $vote       = D('vote');
            $voteOption = D('voteOption');
            $questions->startTrans();
            $vote->startTrans();
            $voteOption->startTrans();
            if(IS_GET){
                $id = I('id');
                $questions->where(array('id'=>$id))->delete() ;
                $vote->where(array('questions_id'=>$id))->delete() ;
                $voteOption->where(array('questions_id'=>$id))->delete() ;

                $questions->commit();
                $vote->commit();
                $voteOption->commit();
                $this->success('删除成功');
                exit;
            }

            if(!$questions_data = $questions->create(I(''))){
                 $this->error($questions->getError());
            }
            if($questions_data['id']){
                $questions->save($questions_data);
                $questions_id = $questions_data['id'];
            }else{
                $questions_id = $questions->add($questions_data);
            }
            $option_result = true;
            $voteArr       = array();
            $optionArr     = array();

            if(empty($data['vote'])){
                $questions->rollback();
                $vote->rollback();
                $voteOption->rollback();
                $this->error('请提供至少1个题目');
            }
            foreach ($data['vote'] as $key => $value) {
                if(!$vote_data = $vote->create($value)){
                    $questions->rollback();
                    $vote->rollback();
                    $voteOption->rollback();
                    $this->error($vote->getError());
                }

                $vote_data['questions_id'] = $questions_id;
                if($vote_data['id']){
                    $vote->save($vote_data);
                    $voteArr[] = $vote_id =$vote_data['id'];
                }else{
                    $voteArr[] = $vote_id = $vote->add($vote_data);
                }

                if( count($value['VoteOption']) < 2 ){
                    $questions->rollback();
                    $vote->rollback();
                    $voteOption->rollback();
                    $this->error('请提供至少2个选项');
                }

                foreach ($value['VoteOption'] as $k => $v) {
                    if(empty($v['content'])){
                        $option_result = false;
                        break 2;
                    }
                    $v['vote_id']      = $vote_id;
                    $v['questions_id'] = $questions_id;
                    if($v['id']){
                        $voteOption->save($v);
                        $optionArr[] = $v['id'];
                    }else{
                        $optionArr[] = $voteOption->add($v);
                    }

                }

            }
            if($option_result == false){
                $questions->rollback();
                $vote->rollback();
                $voteOption->rollback();
                $this->error('问卷新增/修改失败，请重新再试');
            }else{

                $vote->where(array('questions_id'=>$questions_id,'id'=>array('not in',$voteArr)))->delete();
                $voteOption->where(array('questions_id'=>$questions_id,'id'=>array('not in',$optionArr)))->delete();
                $questions->commit();
                $vote->commit();
                $voteOption->commit();
                $this->success('问卷新增/修改成功');
            }

            exit;
        }

        $this->display();
    }

}