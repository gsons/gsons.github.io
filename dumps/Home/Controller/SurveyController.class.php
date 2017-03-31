<?php
namespace Home\Controller;

class SurveyController extends PublicController{

    public function Index(){

		$p     = I('get.p');
		$num   = 15;
		$where = array('status'=>1);
		$model = M('questions');

		$list  = $model->where($where)->page($p,$num)->order('create_date desc')->select();
		$count = $model->where($where)->count();
		$page  = new \Think\Bootpage($count,$num);
        $page->rollPage=5;
        $page->lastSuffix=false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("show_total",false);

        $this->assign(array(
        	'list' => $list,
        	'page' => $page->show(),
        	));

        $this->display('List');
    }

    public function Details(){
		$id      = I('get.id');
		$details = D('questions')->relation(true)->find($id);
		empty($details) && $this->redirect('Index');
		if(IS_POST){
			$data = I('post.');
			//dump($data);exit;
			$ip = get_client_ip(1);
			if(M('voteHistory')->where(array('questions_id'=>$id,'ip'=>$ip))->find()){
				$this->error('你已经填过些问卷了~',U('Result',array('id'=>$id)));
			}

			$vote        = M('vote');
			$voteOption  = M('voteOption');
			$voteHistory = M('voteHistory');
			$vote->startTrans();
			$voteOption->startTrans();
			$voteHistory->startTrans();

			foreach ($details['Vote'] as $key => $value) {
				if( !$vote->where(array('id'=>$value['id']))->setInc('total') || !$voteOption->where(array('vote_id'=>$value['id'],'id'=>$data[$value['id']]))->setInc('total') ){
					$voteHistory->rollback();
	                $vote->rollback();
	                $voteOption->rollback();
	                $this->error('请完成完整问卷');
				}
			}

			$temp = array(
				'questions_id' => $id,
				'ip'          => $ip,
				'content'     => json_encode($data),
				'create_date' => NOW_TIME,
				);
			$result = $voteHistory->add($temp);
			if($result){
				$voteHistory->commit();
                $vote->commit();
                $voteOption->commit();
				$this->success('填写成功',U('Result',array('id'=>$id)));
			}else{
				$voteHistory->rollback();
				$vote->rollback();
				$voteOption->rollback();
				$this->error('填写失败，请重新再试');
			}
			exit;
		}

    	$this->assign(array(
			'details'  => $details,
			'alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    		));
    	$this->display();
    }

    public function Result(){
    	$id      = I('get.id');
		$details = D('questions')->relation(true)->find($id);
		empty($details) && $this->redirect('Index');

		$idArr = array_column($details['Vote'],'id');
		$countArr = M('voteOption')->where(array('vote_id'=>array('in',$idArr)))->group('vote_id')->field('sum(total) as c , vote_id')->select();
		$count = array();
		foreach ($countArr as $key => $value) {
			$count[$value['vote_id']] = $value['c'];
		}

		$total = M('voteHistory')->field('count(id) as c')->where(array('questions_id'=>$id))->select();

		$this->assign(array(
			'details'  => $details,
			'count'    => $count,
			'total'    => $total['0']['c'],
			'alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			));
		$this->display();
    }


}