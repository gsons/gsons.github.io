<?php
namespace Home\Widget;
use Think\Controller;

class ComponentWidget extends Controller{

    public function OpenLeft(){
        $this->display('Component/OpenLeft');
    }

    public function LeaderLeft(){
        $cur = I('get.cur',1480);
        $aid = I('get.aid');
        if( $aid ){
            $cur = M('Article')->where(array('id'=>$aid))->getField('type_id');
        }

        $leader = get_child_category('ArticleType','id','name','pid', 1440 ,array('special_id'=>0));
        $model = M('Article');
        foreach ($leader as $key => $value) {
            $where = array('type_id'=>array('in',get_child_idArr($value['id'],',')),'status'=>1);
            $leader[$key]['child'] = $model->where($where)->field('id,title,status')->order('sort desc,create_date desc')->select();
        }
        $this->assign('leader',$leader);
        $this->assign('cur',$cur);
        $this->assign('aid',$aid);
        $this->display('Component/LeaderLeft');
    }

    public function SingleDetail(){
        $type_id = I('get.id');
        $arc = M('Article')->where(array('type_id'=>$type_id,'static'=>1))->find();
        $this->assign('arc',$arc);
        $url = base64_encode(urlencode('http://'.$_SERVER['SERVER_NAME'].modelUrl(U('Index/Detail',array('id'=>$arc['id'])),'mobile')));
        $this->assign('qrcode_url', U('qrcode',array('url'=>$url)));
        $this->display('Component/SingleDetail');
    }

}