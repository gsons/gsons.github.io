<?php
namespace Sky\Controller;
use Think\Controller;

class IndexController extends PublicController
{

    public function index(){

        $uid = is_admin();
        $mid = I('get.mid');
        $mid = $mid ? $mid: 1;
        $TopAccess = 0 ;
        $_Group = M('AuthGroupAccess')->where('uid = ' . $uid)->field('group_id')->select();
        foreach ($_Group as $item) {
            if($item['group_id'] == ADMIN_GROUP_ID ){
                $TopAccess = 1;
            }
            $exist = M('AuthGroup')->find($item['group_id']);
            if( !$exist ){
                continue;
            }
            $nowGroup = $nowGroup?$nowGroup.',':'' . $item['group_id'];
        }
        if($TopAccess!=1) {
            $rm['id'] = array('in', $nowGroup);
            $_Rules = M('AuthGroup')->where($rm)->field('rules')->select();
            foreach ($_Rules as $key => $value) {
                $Rules[] = explode(',', $value['rules']);
            }
            $VisibleMenu = $Rules[0];
            array_unique($VisibleMenu);
            foreach ($Rules as $key => $value) {
                array_merge_recursive($VisibleMenu, $value);
            }

            $map['id'] = array('in', $VisibleMenu);
        }

        $map['type'] = $mid;
        $map['usage'] = 1;
        $map['module'] = MODULE_NAME;
        $Skymenu = D('Skymenu');
        $Skymenu = $Skymenu->where($map)->order(array('sort'=>'desc'))->select();
        // print_r($Skymenu);exit;
        foreach($Skymenu as $key =>$value){
            foreach ($value as $k=>$v) {
                if($k=='src'){
                    $tc = explode('/',$v);
                    $Skymenu[$key]['controller'] = $tc[1];
                }
                if(!empty($Skymenu[$key]['ext'])){
                    $ext = explode("\r\n",$value['ext']);
                    foreach ($ext as $s) {
                        $_e = explode(':',$s);
                        $extension[$_e[0]] = $_e[1];
                    }
                    $Skymenu[$key]['extension'] = $extension;
                    $extension = array();
                }
            }
        }

        $default = $Skymenu[0];

        $top = '' ;
        $child = '' ;
        foreach($Skymenu as $key=>$value){
            foreach($value as $k=>$v){
                if($k=='pid' && $v == 0){
                    $top[$Skymenu[$key]['id']] = $value;
                }else if($k=='pid' && $v!=0){
                    $child[$Skymenu[$key]['id']] = $value;
                }
            }
        }

        foreach($child as $key=>$value){
            foreach($value as $k=>$v){
                if($k=='pid'){
                    if($v==$top[$v]['id']) {
                        $top[$v]['child'][] = $value;
                    }
                }
            }
        }

        $cron = M('Cron')->select();
        $this->assign('cron',$cron);

        $this->assign('root', __ROOT__);
        $this->assign('default', $default);
        $this->assign('mid', $mid);
        $this->assign('Skymenu', $top);
        $this->display('Index');

    }

    public function overall(){
        $_g = getGroup();
        $id = I('get.id',$_g[0]['id']);
        $date = C('CYCLE_DATE');
        $group = M('AuthGroup')->find($id);
        $cycleList = R('Responsibility\cycleList');
        foreach ($date as $key => $value) {
            $type = M('ArticleTypeGroup')->where(array('group_id'=>$id))->join('__ARTICLE_TYPE__ on __ARTICLE_TYPE__.id = __ARTICLE_TYPE_GROUP__.type_id and __ARTICLE_TYPE__.cycle = '.$key)->select();
            $cycleCur = $cycleList[$key];
            foreach ($cycleCur as $k => $v) {
                if( $v[0] < time() && time() < $v[1] ){
                    $target = $v;
                }
            }
            $map['status'] = array('gt',0);
            $map['create_date'] = array('between',$target);
            foreach ($type as $k => $v) {
                $map['type_id'] = $v['id'];
                $type[$k]['exist'] = M('Article')->where($map)->count();
            }
            $list[$key]['type'] = $type;
            $list[$key]['name'] = $value;
        }
        $this->assign('resp',$list);
        $this->assign('date',$date);
        $this->assign('group',$group);
        $this->assign('_g',$_g);
        $this->assign('id',$id);

        $map['department_id'] = array('in',$id);
        $map['status']        = array('in','0,1');
        $this->ListRecord( M('Letter') , $map  , 'datetime desc');

        $status = I('get.status');
        $group_id = I('get.group_id');
        $keyword = I('get.keyword');
        $this->assign(array(
            'group_id' => $group_id,
            'keyword' => $keyword
        ));

        $map['department_id'] = $id;
        $map['status'] = $status;
        $Apply = M('Apply','open_','DB_OPEN')->where($map)->select();
        $this->assign('apply',$Apply);

        $this->display('Overall');
    }

    public function sysinfo()
    {
        $this->sysinfo = array(
            'os'            => php_uname() , //获取服务器标识的字串
            'version'       => PHP_VERSION, //获取PHP服务器版本
            'action'        => php_sapi_name() , //获取Apache服务器版本
            'time'          => date("Y-m-d H:i:s", time()), //获取服务器时间
            'max_upload'    => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            'max_ex_time'   => ini_get("max_execution_time") . "秒", //脚本最大执行时间
            'mysql_version' => $this->_mysql_version(),
            'mysql_size'    => $this->_mysql_db_size(),
        );

        $this->assign('SITE_TITLE','系统信息');
        $this->display('Sysinfo');

    }

    private function _mysql_version()
    {
        $Model = D('Skymenu');
        $version = $Model->query("select version() as ver");
        return $version[0]['ver'];
    }

    private function _mysql_db_size()
    {
        $Model = D('Skymenu');
        $sql = "SHOW TABLE STATUS FROM " . C('DB_NAME');
        $tblPrefix = C('DB_PREFIX');
        if ($tblPrefix != null) {
            $sql .= " LIKE '{$tblPrefix}%'";
        }
        $row = $Model->query($sql);
        $size = 0;
        foreach ($row as $value) {
            $size += $value["Data_length"] + $value["Index_length"];
        }
        return round(($size / 1048576), 2) . 'M';
    }

    public function getMid(){

        if(IS_POST){

            $mname = I('post.mname');

            $module = D('Module');
            $data = $module->where(array('module'=>$mname))->select();

            echo $data[0]['id'];

        }

    }

}