<?php
namespace Sky\Controller;

class AdvertController extends PublicController {

    protected $Model = 'Adv';
    protected $error_msg = '请选择要进行操作的广告图';

    public function Focus(){

        $where = array();
        $get = I('get.');
        if($get['start_date'] || $get['end_date']){
            $where[] = "  create_date between ".( empty($get['start_date']) ? 0 : strtotime($get['start_date']) )." and ".( empty($get['end_date']) ? NOW_TIME : strtotime($get['end_date']) ) ;
        }
        if(strlen($get['keyword'])){
            $where['name'] = array('like','%'.$get['keyword'].'%');
        }
        if( $get['type'] ){
            $where['type_id'] = $get['type'];
        }
        $this->assign('get',$get);

        $type = M('AdvType')->select();
        $this->assign('type',$type);
        // dump($where);exit;
        $Model = M('Adv');
        // $map['type'] = 0;
        $count  = $Model->count();
        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $Model->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function FocusEdit(){

        if(I('get.id')){
            $this->assign('info',M('Adv')->find(I('get.id')));
        }
        if(IS_AJAX){
            $this->ajaxReturn( $this->ActiveRecord(D('Adv') ,I('') ,U('Advert/Focus')) );
        }
        $type = M('AdvType')->where(array('status'=>1))->select();
        // print_r($type);exit;
        $this->assign('type',$type);
        $this->display();
    }


    public function Type(){
        $Model = M('AdvType');
        $count  = $Model->count();
        $Page = new \Think\Bootpage($count,$this->getPerpage());
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = 'id desc';
        $list = $Model->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function TypeEdit(){
        if(I('get.id')){
            $this->assign('info',M('AdvType')->find(I('get.id')));
        }
        if(IS_AJAX){
            $this->ajaxReturn( $this->ActiveRecord(D('AdvType') ,I('') ,U('Advert/Type')) );
        }
        $this->display();
    }
}