<?php
namespace Home\Controller;
use Org\Net\Http;
use Think\Controller;
use Think\Verify;

class PublicController extends Controller
{

    public function __construct(){
        parent::__construct();

        // 郁南概况描述
        $description1 = M('ArticleType')->where(array('id'=>1372))->getfield('description');

        // 最新动态
        $public_list[0]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1367,','),'recommend'=>0)),'1,5');
        $public_list[0]['arc'] = $public_list[0]['list'][0] ;
        unset($public_list[0]['list'][0]);

        // 最新推荐
        $public_list[1]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1367,','),'recommend'=>1)),'1,5');
        $public_list[1]['arc'] = $public_list[1]['list'][0] ;
        unset($public_list[1]['list'][0]);

        // 郁南新闻
        $public_list[2]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(19,','))),'1,5');
        $public_list[2]['arc'] = $public_list[2]['list'][0] ;
        unset($public_list[2]['list'][0]);

         // 公示公告
        $public_list[3]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,5');
        $public_list[3]['arc'] = $public_list[3]['list'][0] ;
        unset($public_list[3]['list'][0] );

        // 招商动态
        $public_list2[0]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(2070,','))),'1,5');
        $public_list2[0]['arc'] = $public_list2[0]['list'][0] ;
        unset($public_list2[0]['list'][0] );

        // 项目建设
        $public_list2[1]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(2069,','))),'1,5');
        $public_list2[1]['arc'] = $public_list2[1]['list'][0] ;
        unset($public_list2[1]['list'][0] );

        // 投资政策
        $public_list2[2]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(2071,','))),'1,5');
        $public_list2[2]['arc'] = $public_list2[2]['list'][0] ;
        unset($public_list2[2]['list'][0] );

        //工作动态
        $work_activity = M('Information','open_','DB_OPEN')->where(array('catalog_id'=>'3'))->order('public_date desc')->limit(5)->select();
        $this->assign('work_activity',$work_activity);

        //相关法规
        $relation_law = M('Information','open_','DB_OPEN')->where(array('catalog_id'=>'6'))->order('public_date desc')->limit(5)->select();
        $this->assign('relation_law',$relation_law);

        $Interact  = D('Letter')->get_list(array("is_public"=>1,'status'=>array('GT',0)),1,10);
        $Interview = M('Interview')->field("id,title,picture,interview_time,interview_guest,interview_description")->order('interview_time desc')->find();
        $Interactstatus = array(
            '-1' => '待审核' ,
            '0'  => '待接收' ,
            '1'  => '转交相关部门处理' ,
            '2'  => '处理中' ,
            '3'  => '已处理完' ,
            );

        $friend_link = D('FriendLinkType')->relation(true)->limit(0,7)->select();
        // print_r($friend_link);exit;

        // 视频新闻
        $video = get_list(array('type_id'=>array('in',get_child_idArr(1379,','))),'1,4','Article','id,title,src');
        $this->assign(array(
            'description1'   =>$description1,
            'public_list'    =>$public_list,
            'public_list2'   =>$public_list2,
            'Interact'       =>$Interact,
            'Interview'      =>$Interview,
            'Interactstatus' =>$Interactstatus,
            'friend_link'    =>$friend_link,
            'video'          =>$video,
            ));

    }


    public function Lists($id = 0){

        $id = I('id',$id);
        empty($id) && redirect('/');

        $idArr = array(
            '1366'=> 'About/Index',
            '1367'=> 'News/Index',
            '1368'=> 'Open/Index',
            '1370'=> 'Interact/Index',
            '1540'=> 'Economy/Index',
            '2091'=> 'Hall/Index',
            );
        if($idArr[$id])
            $this->redirect($idArr[$id]);
        else if($link=M("Article_type")->where("other_link like '%http%' and id=".$id)->getField("other_link")){
            echo "<script>window.open('{$link}','_blank');window.history.back(-1);</script>";

        }
        $type = M('ArticleType')->where(array('id'=>$id))->field('pid,templets,name')->find();
        $pid = $type['pid'];
        $child_tree = get_child_category('ArticleType','id','name','pid',$pid,array('special_id'=>0));
        $p = I('p',1);
        $num = 20;
        $where = array('status'=>array('GT',0),'type_id'=>array('in',get_child_idArr($id,',')));
        $model = M('Article');
        $list = $model->where($where)->page($p,$num)->order('sort desc,create_date desc')->select();

        $count = $model->where($where)->count();
        $page   = new \Think\Bootpage($count,$num);
        $page->rollPage=5;
        $page->lastSuffix=false;
        $page->setConfig("prev","<<");
        $page->setConfig("next",">>");
        $page->setConfig("first","首页");
        $page->setConfig("last","尾页");

        // 公示公告
        $list2 = get_list(array('type_id'=>array('in',get_child_idArr(2072,','))),'1,8');

        $this->assign(array(
            'id'         => $id ,
            'pid'        => $pid ,
            'p_title'    => M('ArticleType')->where(array('id'=>$pid))->getfield('name') ,
            'title'      => $type['name'] ,
            'child_tree' => $child_tree ,
            'list'       => $list ,
            'list2'      => $list2 ,
            'page'       => $page->show() ,
            'meta_title' => $type['name'] ,
            ));

        $this->assign('cid',I('get.cid'));
        $this->display('Article/'.( empty($type['templets']) ? 'Lists' : $type['templets'] )  );
    }

    public function Details(){

        $id = I('id');
        empty($id) && redirect('/');


        $model = M('Article');
        $arc = $model->where(array('id'=>$id,'status'=>array('GT',0)))->find();

        empty($arc) && redirect('/');

        $model->where(array('id'=>$id))->setInc('click',1);

        $prev = $model->where(array('id'=>array('LT',$id),'type_id'=>$arc['type_id']))->order('id desc')->field('id,title,create_date')->find();
        $next = $model->where(array('id'=>array('GT',$id),'type_id'=>$arc['type_id']))->order('id')->field('id,title,create_date')->find();

        // 最新推荐
        $recommend = get_list(array('type_id'=>array('in',get_child_idArr($arc['type_id'],','),'recommend'=>1)),'1,6');

        // 热门文章
        $hot = get_list(array('type_id'=>array('in',get_child_idArr($arc['type_id'],','))),'1,6','Article','id,title,create_date','click desc');

        $url = base64_encode(urlencode('http://'.$_SERVER['SERVER_NAME'].modelUrl(U('Index/Detail',array('id'=>$id)),'mobile')));


        $type = M('ArticleType')->where(array('id'=>$arc['type_id']))->field('pid,detail_template,name')->find();

        $this->assign(array(
            'arc'        => $arc ,
            'prev'       => $prev ,
            'next'       => $next ,
            'recommend'  => $recommend ,
            'hot'        => $hot ,
            'meta_title' => $arc['title'] ,
            'qrcode_url' => U('qrcode',array('url'=>$url)),
            ));

        $this->display('Article/'.( empty($type['detail_template']) ? 'Details' : $type['detail_template'] )  );
    }



    /**
     * @GetKeyword 根据用户输入内容对应获取关键词的ID以及对应信息类型
     * @word 参数：用户输入的内容
     * @wid 参数: 微信号ID
     */
    public function GetKeywordID($word,$wid) {
        $KeywordModel = D('WechatKeyword');
        $KeywordData = $KeywordModel->where(array('keyword'=>$word,'wechat_id'=>$wid,'status'=>1))->find();
        $KeywordModel->where(array('keyword'=>$word,'wechat_id'=>$wid))->setInc('times');
        return $KeywordData['id'];
    }

    /**
     * @GetKeywordContent 根据传入的关键字ID以及信息类型，查询相应的表获取相应的回复内容
     * @data 参数: 传入的包含关键词ID以及回复信息类型的id
     * @wid 参数: 微信号ID
     *      关键字ID => id
     *      回复消息类型ID => type_id
     */
    public function ReplyKeywordContent($id,$wid){

        $reply = M('WechatReplyKeyword')->where(array('keyword_id'=>$id))->select();
        $reply_id = '';
        foreach ($reply as $key=>$value) {
            $reply_id .= $reply_id ? ','.$value['reply_id'] : $value['reply_id'];
        }
        $reply = M('WechatReply')->where(array('id'=>array('in',$reply_id)))->select();
        foreach ($reply as $key=>$value) {
            switch($value['type_id']){
                case '1':
                    $replycontent[] = array(
                        'content' => $value['content'],
                        'type_id' => $value['type_id']
                    );
                    break;
                case '5';
                    /**
                     * 设置回复图文
                     * @param array $newsData
                     * 数组结构:
                     *  array(
                     *  	"0"=>array(
                     *  		'Title'=>'msg title',
                     *  		'Description'=>'summary text',
                     *  		'PicUrl'=>'http://www.domain.com/1.jpg',
                     *  		'Url'=>'http://www.domain.com/1.html'
                     *  	),
                     *  	"1"=>....
                     *  )
                     */

                    $NewsModel = D('Wenews');
                    $reply = $NewsModel->where(array('keyword'=>$id,'wechat_id'=>$wid))->select();

                    $data = '' ;
                    $i = 0 ;
                    foreach($reply as $item ){
                        $data[$i]['Title'] = $item['title'] ;
                        $data[$i]['Description'] = $item['imgdesc'] ;
                        $data[$i]['PicUrl'] = "http://".$_SERVER['HTTP_HOST'].get_image($item['corver']) ;
                        $data[$i]['Url'] = $item['url'] ;
                        $i++;
                    }

                    $replycontent['content'] = $data ;
                    $replycontent['type'] = 3;
                    break;
                default:
                    $media = M('WechatMedia')->find($value['content']);
                    $replycontent[] = array(
                        'media_id' => $media['media_id'] ,
                        'type_id' => $value['type_id'] ,
                        'title' => $media['title'] ,
                        'description' => $media['description'] ,
                    );
            }
        }
        return $replycontent ;

    }

    /*上传文件*/
    public function UploadFile(){
        $type = I('get.type');
        if(!empty($type)){
            $map['id|name'] = $type;
        }else{
            $map['id'] = 1;
        }
        $format = M('Uploadformat')->where($map)->find();
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = $format['max'];// 设置附件上传大小
        $upload->exts = json_decode($format['ext'],1);// 设置附件上传类型
        $upload->rootPath = './Uploads/';// 设置附件上传目录
        $upload->savePath = 'File/';// 设置附件上传目录
        $info = $upload->upload();
        if (!$info) {
            $this->ajaxReturn(array('status'=>0,'info'=>$upload->getError()));
        } else {
            $this->ajaxReturn(array('status'=>1,'info'=>$info));
        }
    }

    public function Download(){
        $file = './Uploads/'.base64_decode(I('get.fileName'));
        \Org\Net\Http::download($file);
    }

    function ListRecord($Model ,$map = '' ,$order = ''){
        $count  = $Model->where($map)->count();
        $Page = new \Think\Bootpage($count,15);
        $Page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%');
        $show = $Page->show();
        $order = $order ? $order : 'id desc';
        $list = $Model->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
    }

    function ActiveRecord($model, $data, $jump = ''){
        if (IS_GET) {
            $id = $data['id'];
            $res = $model->SelfDel($id);
        }
        if (IS_POST) {
            if($data['ids']){
                $res = $model->SelfDel($data);
            }else {
                if ($data['id']) {
                    $res = $model->SelfUpdate($data);
                } else {
                    $res = $model->SelfAdd($data);
                }
            }
        }
        if( is_array($res) &&  $jump ) {
            $res['url'] = $jump;
        }
        return $res ;
    }


    public function getArticle(){
        $p = I('get.p',1);
        $type = I('get.type');
        $Article = M('Article')->where(array('type'=>$type))->limit($p*10,10)->order('sort desc ,create_date desc')->select();
        $this->assign('list',$Article);
        $html = $this->fetch();
        $this->ajaxReturn($html);
    }


    public function Verify(){
        $config =    array(
            'fontSize'    =>    40,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
        );
        $verify = new Verify($config);
        $verify->entry();
    }

    protected function check_code($code){
        $verify = new Verify();
        return $verify->check($code);
    }

    public function qrcode(){
        Vendor('Qrcode.code');
        $class = new \QRcode();
        $value = I('get.url');
        $value = urldecode(base64_decode($value));
        // echo $value;exit;
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 6;

        $temp = '.'.C('TMPL_PARSE_STRING.__PUBLIC__').'/Home/img/temp.png';

        // $class->png($value,$temp, $errorCorrectionLevel, $matrixPointSize, 2);
        $class->png($value);
        exit;
        $logo = '.'.C('TMPL_PARSE_STRING.__PUBLIC__').'/Home/img/big_code.png';

        $QR             = imagecreatefromstring(file_get_contents($temp));
        $logo           = imagecreatefromstring(file_get_contents($logo));
        $QR_width       = imagesx($QR);
        $QR_height      = imagesy($QR);
        $logo_width     = imagesx($logo);
        $logo_height    = imagesy($logo);
        $logo_qr_width  = $QR_width / 5;
        $scale          = $logo_width/$logo_qr_width;
        $logo_qr_height = $logo_height/$scale;
        $from_width     = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        Header("Content-type: image/png");
        imagepng($QR);
    }
}