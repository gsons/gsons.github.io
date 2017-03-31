<?php
namespace Sky\Controller;
use Common\Controller\BaseController;
use Think\Auth;

class PublicController extends BaseController
{

    protected $Model ;
    protected $error_msg;

    public function __construct()
    {

        parent::__construct();
        $this->cmsversion = C('SKYCMS_VERSION');

        $auid = is_admin() ? is_admin() : I('post.auid');
        if ($auid < 1) {
             header('Location:' . U('User/login'));
        }

        if ($auid > 0) {
            $Admindata = M('Admin')->find($auid);
            $this->Admindata = $Admindata;
        }
        $auth = new Auth();
        if (!$auth->check(CONTROLLER_NAME . '/' . ACTION_NAME, is_admin() ? is_admin() : I('post.auid'),'Sky')) {
            exit('你没有该模块的权限，谢谢！');
        }else{
            $this->CurrentControl = CONTROLLER_NAME . '/' . ACTION_NAME;
        }

    }

    //递归删除目录
    public function delDir($dirName)
    {
        $dh = opendir($dirName);
        //循环读取文件
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                    $fullpath = $dirName . '/' . $file;
                    //判断是否为目录
                    if (!is_dir($fullpath)) {
                        //如果不是,删除该文件
                        if (!unlink($fullpath)) {
                            echo $fullpath . '无法删除,可能是没有权限!<br>';
                        }
                } else {
                    //如果是目录,递归本身删除下级目录
                    $this->delDir($fullpath);
                }
            }
        }
        //关闭目录
        closedir($dh);
        //删除目录
        if (!rmdir($dirName)) {
            $this->error('__目录删除失败');
        }
    }

    //一键清空缓存
    public function ClearCache(){
        //TODO：验证用户权限
        if (IS_AJAX) {
            $fileDel[] = RUNTIME_PATH ;
//            $fileDel[] = HTML_PATH  ;
            foreach ($fileDel as $key => $value) {
                if (file_exists($value)) {
                    $this->delDir($value);
                } else {
                    $this->error('缓存目录不存在');
                }
            }
            $this->success('缓存清空成功');
        } else {
            $this->error('非法请求');
        }
    }

    function ListRecord($Model ,$map = '' ,$order = '' , $join = '' , $field = true , $group = false,$perpage=0){
        $count  = $Model->field($field)->where($map)->join($join)->group($group)->count();
        $perpage=$perpage?$perpage:$this->getPerpage();
        $Page = new \Think\Bootpage($count,$perpage);
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = $order ? $order : 'id desc';
        $list = $Model->field($field)->where($map)->order($order)->join($join)->group($group)->limit($Page->firstRow.','.$Page->listRows)->select();
        // echo $Model->fetchSql(true)->field($field)->where($map)->order($order)->join($join)->group($group)->limit($Page->firstRow.','.$Page->listRows)->select();exit();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('count',$count);
    }
     function getPerpage(){
       $perpage=15;
       $admin_id=is_admin();
       if($admin_id) {
        $admin= M("admin")->field("perpage_num")->find($admin_id);
         $perpage=$admin["perpage_num"];
         if($perpage<5||$perpage>100){
            $perpage=15;
            M("admin")->where('id='.$admin_id)->setField("perpage_num",$perpage);
         }
       }
       return $perpage;
    }

}