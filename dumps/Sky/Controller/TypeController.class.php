<?php
namespace Sky\Controller;

class TypeController extends ArticleController {

    protected $Model = 'ArticleType';
    protected $error_msg = '请选择要进行操作的文章分类';
    protected $html = '';

    public function Type($special = array(),$tree_tpl = 'Type/Tree'){

        if(IS_AJAX){
            $ids = I('post.ids');
            $key = I('post.key');
            for($i = 0 ; $i < count($ids); $i++){
                $data[] = array(
                    'id' => $ids[$i],
                    'key' => $key[$i],
                    'count' => 0
                );
            }
            $_i = implode(',',$ids);
            $Article = M('Article');
            $res = $Article->field('type_id,count(*) as count')->where(array('type_id'=>array('in',$_i)))->group('type_id')->select();
            foreach ($res as $key => $value) {
                foreach ($data as $k => $v) {
                    if( $v['id'] == $value['type_id'] ){
                        $data[$k]['count'] = $value['count'];
                    }
                }
            }
            $this->ajaxReturn($data);
            exit();
        }

        $type = M('ArticleType')->where(array_merge(array('special_id'=>0),$special))->order('sort desc,id asc')->select();
        $tree = list_to_tree($type);
        $this->makeTree($tree,0,$tree_tpl);
        $this->assign('tree',$this->html);
        $this->display('Type/Type');
    }

    public function sort(){
        if(IS_AJAX){
            $id = I('post.id');
            $sort = I('post.sort');
            $res = M('ArticleType')->where(array('id'=>$id,'pid'=>array('neq',0)))->save(array('sort'=>$sort));
            if( $res ){
                $this->success();
            }else{
                $this->error('修改失败');
            }
        }
    }

    protected function makeTree($tree, $level = 0, $tpl = 'Type/Tree'){
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

    public function TypeEdit(){
        $special_id = I('get.special');
        if( I('get.id') ){
            $info = M('ArticleType')->find(I('get.id'));
            $this->assign('info',$info);
            $this->assign("count",M('ArticleType')->where(array('pid'=>I('get.id')))->count());
            $this->assign("_link",M('ArticleType')->where(array('id'=>I('get.id')))->getField("other_link"));
        }
        if(IS_AJAX){

            if(IS_GET && I('get.id')){
                $id = I('get.id');
                $count = M('Article')->where(array('type_id'=>$id))->count();
                if( $count > 0 ){
                    $this->error('该栏目尚有文章，无法删除，请清空文章后再删除栏目');
                }
                $count = M('ArticleType')->where(array('pid'=>$id))->count();
                if( $count > 0 ){
                    $this->error('该栏目尚有子级分类，无法删除');
                }
            }
            elseif (IS_POST) {
                 M("ArticleType")->where("id=".I("post.pid"))->setField("other_link","");
            }

            $data = I('');
            $data['special_id']  = empty($special_id) ? 0 : $special_id;
            $this->ajaxReturn( $this->ActiveRecord(D('ArticleType') , I('') , $_SERVER['HTTP_REFERER'] ) );
        }
        if (empty($special_id)) {
            $type = array();
            $pid = I('get.pid',0);
            $this->assign('pid',$pid);
            get_category2('ArticleType','id','name','pid','',$type);
            $this->assign('type',$type);
        }else{
            $this->assign('special_id',$special_id);
        }
        $this->display('Type/TypeEdit');

    }

    public function Move(){
        $id = I('get.id');
        if(IS_AJAX){
            $pid = I('post.pid');
            $res = M('ArticleType')->where(array('id'=>$id))->save(array('pid'=>$pid,'update_date'=>time()));
            if( $res ){
                $this->success('移动成功');
            }else{
                $this->error('移动失败');
            }
        }
        $type = array();
        $where = array('id'=>array('neq',$id));
        get_category2('ArticleType','id','name','pid',0,$type,$where);
        $this->assign('type',$type);
        $this->display();
    }

    public function Combine(){
        $id = I('get.id');

        if(IS_AJAX){
            $pid = I('post.pid');
            $res1 = M('Article')->where(array('type_id'=>$id))->save(array('type_id'=>$pid,'update_date'=>time()));
            $res2 = M('ArticleType')->where(array('pid'=>$id))->save(array('pid'=>$pid,'update_date'=>time()));
            $res3 = M('ArticleType')->where(array('id'=>$id))->delete();
            if( $res1 && $res2 !== false && $res3 ){
                $this->success('合并成功');
            }else{
                $this->error('合并失败');
            }
        }
        $type = array();

        $_t = M('ArticleType')->find($id);
        if( $_t['special_id'] ){
            $where['special_id'] =  $_t['special_id'];
        }

        $where['id'] = array('neq',$id);
        get_category2('ArticleType','id','name','pid',0,$type,$where);
        $this->assign('type',$type);
        $this->display('Type/Combine');
    }

    public function Copy(){

        if(IS_AJAX){
            $fail = '';
            $success = 0;
            $article = M('Article')->where(array('id'=>array('in',I('post.ids'))))->select();
            $ext_id = I('post.type_id');
            $ext_type = M('ArticleType')->where(array('id'=>array('in',$ext_id)))->select();
            foreach ($article as $k => $v) {
                foreach ($ext_type as $key => $value) {
                    $copy = $v;
                    unset($copy['type_id']);
                    unset($copy['id']);
                    $copy['special_id'] = 0;
                    if( $value['special_id'] ){
                        $copy['type_id'] = $value['id'];
                        $copy['special_id'] = $value['special_id'];
                    }else{
                        $copy['type_id'] = $value['id'];
                    }
                    $_res = M('Article')->add($copy);
                    if( !$_res ){
                        $fail .= $v['title'].'添加至'.$value['name'].'栏目失败<br />';
                    }else{
                        $success++;
                    }
                }
            }
            $info = '共'.$success.'篇文章发布成功。<br />'.$fail;
            if( $fail ){
                $this->error($info);
            }else{
                $this->success($info);
            }
        }

        $type = M('ArticleType')->field('id,name,pid,sort,special_id,status')->where(array('special_id'=>0))->select();

        $special_type = M('ArticleType')->where(array('special_id'=>array('neq',0)))->select();
        $special = M('Special')->where(array('status'=>1))->select();
        foreach ($special as $key => $value) {
            $special[$key]['name'] = $value['title'];
            $special[$key]['id'] = 's'.$value['id'];
            $special[$key]['pid'] = 0;
        }
        foreach ($special_type as $key=>$value) {
            $special_type[$key]['pid'] = 's'.$value['special_id'];
        }

        $type = array_merge($type,$special);
        $type = array_merge($type,$special_type);

        $tree = list_to_tree($type);
        $this->makeTree($tree,0,'Type/CopyTree');
        $this->assign('tree',$this->html);
        $this->assign('copy',I('get.copy'));
        $this->display('Type/Copy');
    }

}