<?php

namespace Sky\Controller;

class AddonsController extends PublicController {

    public function Modules(){
        $Addons = M('Addons')->select();
        $this->assign('list',$Addons);
        $this->assign('SITE_TITLE','模块列表');
        $this->display('Modules');
    }

    public function Hook(){
        $Hook = M('Hooks')->select();
        $this->assign('list',$Hook);
        $this->assign('SITE_TITLE','钩子列表');
        $this->display('Hook');
    }

    public function Skymenu(){

        $module = I('get.module');
        if( $module ){
            $map['module'] = $module;
        }
        $map['pid'] = 0;

        $Skymenu = M('Skymenu')->where($map)->order('sort desc')->select();
        $Child = M('Skymenu')->where(array('pid'=>array('neq',0)))->order('sort desc')->select();
        foreach ($Child as $key=>$value) {
            foreach ($Skymenu as $k=>$v) {
                if($value['pid'] == $v['id'] ){
                    $Skymenu[$k]['child'][]  = $value;
                }
            }
        }
        $this->assign('list',$Skymenu);
        $this->display();
    }

    public function SkymenuEdit(){

        if(I('get.id')){
            $info = M('Skymenu')->find(I('get.id'));
            $this->assign('info',$info);
            $Skymenu = M('Skymenu')->where(array('pid'=>0,'usage'=>1,'module'=>$info['module']))->order('sort desc')->select();
        }

        if(IS_AJAX){
            if(IS_POST) {
                $this->ajaxReturn($this->ActiveRecord(D('Skymenu'), I(''), U('Addons/Skymenu')));
            }else{
                if( I('get.id') ){
                    $this->ajaxReturn($this->ActiveRecord(D('Skymenu'), I(''), U('Addons/Skymenu')));
                }else{
                    $this->ajaxReturn(M('Skymenu')->where(array('pid'=>0,'usage'=>1,'module'=>I('get.module')))->order('sort desc')->select());
                }
            }
        }

        $this->assign('Skymenu',$Skymenu);
        $this->display();

    }

    public function Menu(){
        $Menu = M('Menu')->order('sort desc')->select();
        $this->assign('list',$Menu);
        $this->display();
    }

    public function MenuEdit(){
        if(I('get.id')){
            $this->assign('info',M('Menu')->find(I('get.id')));
        }
        if(IS_AJAX){
            $this->ajaxReturn(  $this->ActiveRecord( D('Menu') ,I(''), U('Addons/Menu') ) );
        }
        $this->display();
    }

    public function Cron(){
        $this->ListRecord( M('Cron') );
        $this->display();
    }

	public function _initialize(){
		/* 读取数据库中的配置 */
        $config = S('DB_CONFIG_DATA');
        if(!$config){
            $config = api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置
	}

	protected $addons = null;

	public function execute($_addons = null, $_controller = null, $_action = null){

		if(C('URL_CASE_INSENSITIVE')){
			$_addons = ucfirst(parse_name($_addons, 1));
			$_controller = parse_name($_controller,1);
		}

	 	$TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . "/Addons/{$_addons}";
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

		if(!empty($_addons) && !empty($_controller) && !empty($_action)){
			$Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
		} else {
			$this->error('没有指定插件名称，控制器或操作！');
		}
	}

}
