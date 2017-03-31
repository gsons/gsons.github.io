<?php
namespace Sky\Controller;

class ConfigController extends PublicController
{

    public function web(){

        $this->EditConfig('web');
        $this->assign('SITE_TITLE','网站设置');
        $this->display('Web');

    }

    public function Cms(){

        $file = CONF_PATH . '/config.php';
        if (!is_writable($file)) {
            $this->error('配置文件不可写入！');
        }
        $this->assign('SITE_TITLE','后台设置');
        $this->display('Cms');
    }


    public function Log(){

        $count = M('Log')->order('id desc')->count();
        $Page       = new \Think\Bootpage($count,$this->getPerpage());
        $show       = $Page->show();
        $Admins = M('Admin')->select();
        $Logs = M('Log')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($Logs as $key=>$value){
            switch( $value['type'] ){
                case 1:
                    $Logs[$key]['action_name'] = '登录';
                    break;
                case 2:
                    $Logs[$key]['action_name'] = '数据写入';
                    break;
                case 3:
                    $Logs[$key]['action_name'] = '数据删除';
                    break;
                case 4:
                    $Logs[$key]['action_name'] = '数据更新';
                    break;
                case 5:
                    $Logs[$key]['action_name'] = '数据库导出';
                    break;
                case 6:
                    $Logs[$key]['action_name'] = '数据库恢复';
                    break;
                case 7:
                    $Logs[$key]['action_name'] = '接口调用';
                    break;
            }
            foreach ($Admins as $k=>$v) {
                if( $value['uid'] == $v['id'] ){
                    $Logs[$key]['admin'] = $v['admin'];
                }
            }
        }
        $this->assign('list',$Logs) ;
        $this->assign('page',$show);
        $this->assign('SITE_TITLE','行为日志');
        $this->display('Log');

    }

    public function EditConfig($file = ''){
        if (IS_AJAX) {

            if( I('get.file') ){
                $file = I('get.file');
            }

            $file = CONF_PATH . '/'.$file.'.php';
            $conf = require $file;
            $data = I('post.');
            $conf = array_merge($conf,$data);
            $settingstr = "<?php \n return ".var_export($conf,true)." \n ?>";
            $size = file_put_contents($file,$settingstr); //通过file_put_contents保存
            if($size){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }

        }
    }

}