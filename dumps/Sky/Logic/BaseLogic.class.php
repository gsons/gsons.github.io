<?php
namespace Sky\Logic;
use Think\Controller;
use Think\Model;

class BaseLogic extends Model{

    function SelfAdd($data){

        $data = $this->create($data);
        if($data){
            $id = $this->add();
            if($id){
                $res['id'] = $id;
                $res['status'] = 1;
                $res['info'] = '添加成功';
                $this->LogAction(2,$id);
            }else{
                $res['status'] = 0;
                $res['info'] = '添加失败';
            }
        }else{
            $res = $this->getError();
        }
        return $res;
    }

    function SelfUpdate($data){

        $id = $data['id'];
        $Logid = $data['id'];
        unset($data['id']);
        $data = $this->create($data,self::MODEL_UPDATE);
        if($data){
            $this->id = $id ;
            $id = $this->save();
            if($id !== false){
                $res['status'] = 1;
                $res['info'] = '修改成功';
                $this->LogAction(4,$Logid);
            }else{
                $res['status'] = 0;
                $res['info'] = '修改失败';
            }
        }else{
            $res = $this->getError();
        }

        return $res;

    }

    function SelfDel($id){

        if(!$id && !is_numeric($id)){
            return '参数或者数据异常！';
        }

        if(is_array($id)){
            $map['id'] = array('in',implode(',',$id['ids']));
            $Logid = implode(',',$id['ids']);
            $id = $this->where($map)->delete();
        }else{
            $id = $this->where('id='.$id)->delete();
            $Logid = $id;
        }

        if($id){
            $res['status'] = 1;
            $res['info'] = '删除成功';
            $this->LogAction(4,$Logid);
        }else{
            $res['status'] = 0;
            $res['info'] = '删除失败';
        }
        return $res;

    }

    function SelfSetField($id,$field,$value){
        if(!$id && !is_numeric($id)){
            return '参数或者数据异常！';
        }

        $map['id'] = array('in',$id);
        $result = $this->where($map)->setField($field,$value);
        @$this->where($map)->setField('admin_id',is_admin());

        if(is_array($id)){
            $Logid = implode(',', $id);
        }else{
            $Logid = $id;
        }

        if($result !== false){
            $res['status'] = 1;
            $res['info'] = '操作成功';
            $this->LogAction(4,$Logid);
        }else{
            $res['status'] = 0;
            $res['info'] = '操作失败';
        }
        return $res;
    }

    /*
     * 日志记录函数：针对数据库增删查改
     * @data Array
     *    'controller':当前控制器名称
     *    'action':当前操作名称
     *    'type': 当前增删查改操作类型对应标识
     *    'create_date': 操作时间
     *    'name': 操作页面
     *    'ip': 操作IP
     *    'uid': 操作用户编号
     */
    function LogAction($type,$id=0,$content='',$controller='',$action='',$name = ''){

        if(strtolower(MODULE_NAME) != strtolower('Sky') ){
            return ;
        }

        $data['controller'] = $controller?$controller:CONTROLLER_NAME ;
        $data['action'] = $action?$action:ACTION_NAME;
        $data['type'] = $type;
        $data['create_date'] = time();
        $data['uid'] = is_admin();
        $data['ip'] = get_client_ip();
        $data['name'] = $name;

        //$ActionNames = S('ActionNames');
        if( !$ActionNames ){
            $ActionNames = M('Skymenu')->field('id,name,src')->select();
            S('ActionNames',$ActionNames);
        }

        $_Actionname = strtolower($data['controller'].'/'.$data['action']);
        foreach ($ActionNames as $key=>$value) {
            if( strtolower($value['src']) == $_Actionname ){
                $data['name'] = $value['name'];
            }
        }

        switch( $type ){
            case 1:
                $data['logdetails'] = '登录成功';
                break;
            case 2:
                $data['logdetails'] = '增加了编号'.$id.'的记录';
                break;
            case 3:
                $data['logdetails'] = '删除了编号'.$id.'的记录';
                break;
            case 4:
                $data['logdetails'] = '修改了编号'.$id.'的记录';
                break;
            case 5:
                $data['logdetails'] = $content;
                break;
            case 6:
                $data['logdetails'] = "导入了名为".$content."的数据备份文件";
                break;
            case 7:
                $data['logdetails'] = $content;
                break;
        }

        M('Log')->data($data)->add();

    }

}