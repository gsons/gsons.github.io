<?php
namespace Sky\Controller;
use Think\Controller;

class CronController extends Controller {

    public function __construct(){
        parent::__construct();
        session_write_close();
    }

    public function Cron(){
        $id = I('get.id');
        $cron = M('Cron')->find($id);
        do{
            set_time_limit(0);
            switch($cron['type']){
                case '1':
                    //每天定时任务
                    $distance = strtotime( date('Y-m-d') . $cron['cycle'] ) - time();
                    if ($distance <= 0) {
                        $distance = strtotime( date('Y-m-d') . $cron['cycle'] . '+1days' ) - time();
                    }
                    break;
                case '2':
                    //每日定时循环任务
                    $distance = $cron['cycle'] ;
                    break;
            }
            if( method_exists($this,$cron['src']) ){
                if ( S('CRON_'.$cron['src'], '', array('type' => 'Db')) ) {
                    exit('任务已运行');
                }
                if ( S('CRON_'.$cron['src'].'_Stop', '', array('type' => 'Db')) ) {
                    S('CRON_'.$cron['src'].'_Stop', NULL , array('type' => 'Db'));
                    $this->Msg('任务已中止');
                    exit;
                }
                S('CRON_'.$cron['src'], NOW_TIME , array('type' => 'Db', 'expire' => $distance-1 ));
                call_user_func( array($this,$cron['src']));
            }else{
                $this->Msg('任务无法执行');
                exit;
            }
            M('Cron')->where(array('id'=>$id))->save(array('last_run_date'=>time(),'next_run_date'=>time()+$distance));
            M('Cron')->where(array('id'=>$id))->setInc('times');
            if( !$distance ){
                S('CRON_'.$cron['src'], NULL, array('type' => 'Db'));
                $this->Msg('任务已停止');
                exit;
            }
            ignore_user_abort(true);
            $this->Msg('任务运行中');
            sleep($distance);
        }while($cron['status']);
    }

    public function Statistics(){
        $Log = D('Log');
        if (strtotime(date('Y-m-d') . ' 23:58:00') < NOW_TIME && NOW_TIME < strtotime(date('Y-m-d') . ' 23:59:59')) {
            $cycleList = $this->cycleList();
            $Group = M('AuthGroup')->field('id,title')->select();
            foreach ($Group as $key => $value) {
                $cycle = M('ArticleTypeGroup')->where(array('group_id' => $value['id']))->join('__ARTICLE_TYPE__ on __ARTICLE_TYPE__.id = __ARTICLE_TYPE_GROUP__.type_id')->select();
                foreach ($cycle as $k => $v) {
                    $cycleCur = $cycleList[$v['cycle']];
                    foreach ($cycleCur as $t => $p) {
                        if ($p[0] < time() && time() < $p[1]) {
                            $target = $p;
                            $step = $t;
                        }
                    }
                    $count = M('Article')->where(array('type_id' => $v['type_id'], 'group_id' => $value['id'], 'create_date' => array('between', $target)))->count();
                    if (!$count) {
                        $_r = $this->record($v['cycle'], $step, $v['type_id'], $target);
                        if ($_r) {
                            $res[] = $_r;
                        }
                    }
                }
            }
            $str = !empty($res) ? '本次任务新增编号为' . implode(',', $res) . '绩效记录' : '本次任务无新增记录';
        }
        $Log->LogAction(7, 0, '系统自动运行绩效审计统计任务。' . $str, CONTROLLER_NAME, ACTION_NAME, '绩效系统自动审计');
        return true;
    }

    public function test(){
        file_put_contents('./log.txt',FormatDate(time()).PHP_EOL,FILE_APPEND);
    }

    public function AutoRemind(){
        $Log = D('Log');
        $cycleList = $this->cycleList();
        $Group = M('AuthGroup')->field('id,title,email')->select();
        $success = 0;
        $fail = '';
        foreach ($Group as $key => $value) {
            $cycle = M('ArticleTypeGroup')->where(array('group_id' => $value['id']))->join('__ARTICLE_TYPE__ on __ARTICLE_TYPE__.id = __ARTICLE_TYPE_GROUP__.type_id')->select();
            foreach ($cycle as $k => $v) {
                $cycleCur = $cycleList[$v['cycle']];
                foreach ($cycleCur as $t => $p) {
                    if ($p[0] < time() && time() < $p[1]) {
                        $target = $p;
                    }
                }
                $count = M('Article')->where(array('type_id' => $v['type_id'], 'group_id' => $value['id'], 'create_date' => array('between', $target)))->count();
                if (!$count) {
                    $remind[] = array(
                        'type_id' => $v['type_id'],
                        'cycle' => $v['cycle'],
                        'target' => $target[1],
                    );
                }
            }

            if( $cycle ) {
                $res = $this->remind($remind, $value);
                if ( $res != 'pass' && $res ) {
                    $success++;
                }else if( $res === false ){
                    $fail .= $fail ? ',' . $value['title'] : $value['title'];
                }
            }
        }
        $success = $success > 0 ? "成功提醒'.$success.'个单位。" : '';
        $fail = $fail ? '以下单位提醒失败['.$fail.']' : '' ;
        $Log->LogAction(7,0, '系统自动运行绩效审计自动提醒任务。'.$success.$fail ,CONTROLLER_NAME,ACTION_NAME,'绩效系统自动提醒');
    }

    public function Stop(){
        $id = I('get.id');
        $cron = M('Cron')->find($id);
        if(  S('CRON_'.$cron['src'].'_Stop', NOW_TIME , array('type' => 'Db'))  ){
            $this->success('正在中止');
        }else{
            $this->error();
        }
    }

    public function Restart(){
        $id = I('get.id');
        $cron = M('Cron')->find($id);
        S('CRON_'.$cron['src'], NULL , array('type' => 'Db'));
        $this->Cron();
    }

    private function Msg($msg){
        $id = I('get.id');
        M('Cron')->where(array('id'=>$id))->setField('msg',$msg);
    }

    public function remind($remind,$group){
        $list = '';
        foreach ($remind as $key => $value) {
            if( date('Y-m-d') == date('Y-m-d',strtotime(date('Y-m-d',$value['target']).'-1days')) || 1 ) {
                $value['name'] = M('ArticleType')->where(array('id' => $value['type_id']))->getField('name');
                $list[] = $value;
            }else{
                continue;
            }
        }
        if( $list ) {
            $this->assign('remind', $remind);
            $this->assign('group', $group);
            $content = $this->fetch('Cron/AutoRemind');
            if ($group['email']) {
                $res = sendMail($group['email'], C('WEB_TITLE') . '绩效系统提醒邮件。[请勿回复]', $content, $group['id']);
            }
            return $res;
        }else{
            return 'pass';
        }

    }

    public function record($type,$name,$article_type_id,$target){
        $data = array(
            'type' => $type,
            'year' => date('Y'),
            'start_date' => $target[0],
            'end_date' => $target[1],
            'name' => $name,
            'article_type_id' => $article_type_id,
        );
        $end = $target[1];
        if( date('Y-m-d') == date('Y-m-d',$end) ) {
            $exist = M('Statistics')->where($data)->count();
            if ($exist) {
                return true;
            } else {
                $data['create_date'] = time();
                $res = M('Statistics')->data($data)->add();
                return $res;
            }
        }

    }

    public function cycleList(){
        $data = S('cycleList');
        if( !$data ) {
            $date = C('CYCLE_DATE');
            $start_date = date('Y-1-1 00:00:00');
            $end_date = date('Y-12-31 23:59:59');
            $data[] = array();
            foreach ($date as $key => $value) {
                $cur_date = strtotime($start_date);
                $list = array();
                while (1) {
                    $_t = array();
                    $_t[] = $cur_date;
                    switch ($key) {
                        case '1':
                            $step = '+1weeks';
                            break;
                        case '2':
                            $step = '+2weeks';
                            break;
                        case '3':
                            $step = '+1months';
                            break;
                        case '4':
                            $step = '+3months';
                            break;
                        case '5':
                            $step = '+6months';
                            break;
                        case '6':
                            $step = '+1years';
                            break;
                        default:
                            break;
                    }
                    $cur_date = strtotime(date('Y-m-d H:i:s', $cur_date) . $step);
                    $_t[] = $cur_date - 1;
                    $list[] = $_t;
                    if ($cur_date > strtotime($end_date)) {
                        break;
                    }
                }
                $data[$key] = $list;
            }
            S('cycleList', $data , 3600*24);
        }
        return $data;
    }

}