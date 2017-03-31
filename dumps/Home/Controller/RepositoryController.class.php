<?php
namespace Home\Controller;

class RepositoryController extends PublicController{

    public function Index(){


        $get     = I('get.');
        if (empty($_SERVER['QUERY_STRING']) ){
            $get['search'] = base64_decode(base64_decode($get['search']));
        }
        $order = $get['order'] == 'date' ? 'id' : 'click' ;
        $num   = 20;

        $type  = D('RepositoryType')->where(array('status'=>1))->relation(true)->select();
        $total = 0;
        $where = array();
    	foreach ($type as $key => $value) {
    		$total = $total + (int)$value['Repository'][0]['count'];
    		if($get['id'] == $value['id'])
    			$where['type_id'] = $value['id'] ;

            if ($get['search'])
                $where['title'] = array('like','%'.$get['search'].'%');
    	}
    	if(empty($where))
    		$get['id'] = 0;

		$model = D('Repository');
		$list  = $model->where($where)->page($get['p'],$num)->relation(true)->order(' is_top desc , '.$order.' desc ')->select();
		$count = $model->where($where)->count();

        $get['search'] = base64_encode(base64_encode($get['search']));
		$page  = new \Think\Bootpage2($count,$num,$get);
        $page->rollPage=5;
        $page->lastSuffix=false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("show_total",false);

    	$this->assign(array(
            'type'   => $type ,
            'total'  => $total ,
            'id'     => $id ,
            'search' => $get['search'] ,
            'order'  => $order ,
            'list'   => $list ,
            'page'   => $page->show() ,
    		));
        $this->display();
    }

    public function Details(){
    	$id = I('get.id',0);
    	$details = D('Repository')->relation(true)->find($id);
    	empty($details) && $this->redirect('Index');

        M('Repository')->where(array('id'=>$id))->setInc('click',1);
    	$type  = D('RepositoryType')->where(array('status'=>1))->relation(true)->select();
		$total = 0;
    	foreach ($type as $key => $value) {
    		$total = $total + (int)$value['Repository'][0]['count'];
    	}

    	$this->assign(array(
			'details' => $details,
			'type'    => $type,
			'total'   => $total,
    		));
        $this->display();
    }

}