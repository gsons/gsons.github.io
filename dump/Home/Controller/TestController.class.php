<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller{

	public function test(){
		$res=$this->tree(0);
		$this->makeTree($res);
		echo $this->html;
	}

	function tree($pid){
		$data=M("article_type")->field("id,pid,name")->where("pid=".$pid)->select();
		if(!empty($data)){
			foreach ($data as $k=>$value) {
			$data[$k]["_child"]=$this->tree($value['id']);
		}
		}
		return $data;
	}

  protected function makeTree($tree, $level = 0, $tpl = 'Tpl/tree'){
    foreach ($tree as $key => $value) {
        $this->assign('vo',$value);
        $this->assign('level',$level);
        $this->html .= $this->fetch($tpl);
        if( !empty($value['_child']) ){
            $this->html .= '<div class="level">';
            $level++;
            $this->makeTree($value['_child'] , $level , $tpl);
            $level--;
            $this->html .= '</div>';
        }
    }
}

}