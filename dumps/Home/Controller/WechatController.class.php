<?php
namespace Home\Controller;
use Think\Wechat;
use Think\Jssdk;

class WechatController extends PublicController
{

    protected $Welist;
    protected $token;
    protected $apid;
    protected $apsec;
    protected $status;
    protected $tips = '开发中!';

    public function __construct(){

        parent::__construct();
        $this->wid = I('get.wid');
        $this->Welist = D('WechatOpen');

        $Welist = $this->Welist;
        $Wedata = $Welist->find($this->wid);

        $this->token = $Wedata['token'];
        $this->apid = $Wedata['apid'];
        $this->apsec = $Wedata['apsec'];
        $this->status = $Wedata['status'];

        $this->options = array(
            'token' => $this->token, //填写你设定的key
            'appid' => $this->apid, //填写你设定的key
            'appsecret' => $this->apsec, //填写你设定的key
            'debug' => true,
            'logcallback' => 'logdebug'
        );

    }

    public function index()
    {

        $Welist = $this->Welist;
        $weObj = new Wechat($this->options);

        //获取AccessToken
        $weObj->checkAuth();
        //验证Token
        $weObj->valid();
        //更新接入状态
        $Welist->where(array('id'=>$this->wid))->save(array('status'=>1,'update_date'=>time()));
        $Welist->where(array('id'=>$this->wid))->setInc('apply_times');

        $type = $weObj->getRev()->getRevType();

        /*
         * debug模式
         */
        $debug = false;
        if($debug){
            $file = './log.txt';
            $param = I('');
            $str = '';
            foreach ($param as $key=>$item) {
                $str .= $key.'=>'.$item.PHP_EOL;
            }
            $str .= '--------------'.PHP_EOL;
            $rev = $weObj->getRevData();
            foreach ($rev as $key=>$item) {
                $str .= $key.'=>'.$item.PHP_EOL;
            }
            $str .= '--------------'.PHP_EOL;
            file_put_contents($file , $str , FILE_APPEND);
        }


        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $text = $weObj->getRev()->getRevContent();   //获取用户输入的文本内容
                $this->SendReply($text,$weObj);
                break;
            case Wechat::MSGTYPE_EVENT:
                $event = $weObj->getRevEvent();
                if ($event['event'] == 'subscribe') {
                    $rec = $weObj->getRevData();
                    $subdata = $weObj->getUserInfo($rec['FromUserName']);
                    $subdata['wechat_id'] = $this->wid;
                    D('Sky/WechatUser')->sub($subdata);

                    $SubscribeReply = M('WechatSpecial')->where(array('is_subscribe' => 1, 'wechat_id' => $this->wid))->find();
                    $replytype = $SubscribeReply['type_id'];  //默认回复内容
                    $replycontent = $SubscribeReply['content'];  //默认回复内容
                    switch ($replytype) {
                        case '1':
                            //文本回复
                            $weObj->text($replycontent)->reply();
                            break;
                        case '2':
                            //图片回复功能
                            $media = M('WechatMedia')->find($replycontent);
                            $weObj->image($media['media_id'])->reply();
                            break;
                        case '3':
                            //图片回复功能
                            $media = M('WechatMedia')->find($replycontent);
                            $weObj->voice($media['media_id'])->reply();
                            break;
                        case '4':
                            //视频回复功能
                            $media = M('WechatMedia')->find($replycontent);
                            $weObj->video($media['media_id'])->reply();
                            break;
						case '5':
                            //图文回复功能
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
                            $newsData = array();
                            $media = M('WechatNewsRelation')->find($replycontent);
                            $article = M('WechatNews')->where(array('id'=>array('in',$media['ids'])))->select();
                            foreach ($article as $key => $value) {
                                $thumb = M('WechatMedia')->where(array('media_id'=>$value['thumb_media_id']))->getField('url');
                                $newsData[] = array(
                                    'Title'=> $value['title'] ,
                                    'Description'=> trim(msubstr(strip_tags(str_replace('&nbsp;','',htmlspecialchars_decode($value['content']))),0,50)),
                                    'PicUrl'=>$thumb,
                                    'Url'=>$value['content_source_url']
                                );
                            }

                            $weObj->news($newsData)->reply();
                            break;
                    }

                }else if($event['event'] == 'unsubscribe') {
                    $rec = $weObj->getRevData();
                    D('Sky/WechatUser')->unsub($rec['FromUserName']);
                }else if($event['event'] ==  'CLICK' ){
                    $rec = $weObj->getRevData();
                    $key = $event['key'];
                    switch($key){
                        case '客服':
                            $weObj->transfer_customer_service()->reply();
                            $wait = $weObj->getKFSessionWait();
                            $data = array(
                                'touser'=> $rec['FromUserName'],
                                'msgtype'=> 'text',
                                'text'=>array(
                                    'content'=> '当前有'.$wait['count'].'人正在排队，请耐心等待'
                                )
                            );
                            $weObj->sendCustomMessage($data);
                            break;
                        default:
                            $this->SendReply($key, $weObj);
                            break;
                    }
                }else if($event['event'] == 'MASSSENDJOBFINISH'){
                    $rec = $weObj->getRevData();
                    $msg_id = $rec['MsgID'];
                    $data = array(
                        'total' => $rec['TotalCount'],
                        'filter' => $rec['FilterCount'],
                        'sent' => $rec['SentCount'],
                        'error' => $rec['ErrorCount'],
                        'status' => $rec['Status'],
                        'update_date' => time(),
                    );
                    M('WechatMass')->where(array('msg_id'=>$msg_id))->save($data);
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                $imgUrl = $weObj->getRev()->getRevPic();
                $weObj->text($this->tips)->reply();
                break;
            default:
                $weObj->text('error')->reply();
        }
    }

    public function Jssdk(){
        $weObj = new Wechat($this->options);
        $appid = $this->apid;
        $apsec = $this->apsec;
        $actoken = $weObj->checkAuth();
        $Jssdk = new Jssdk($appid, $apsec, $actoken);
        $this->signPackage = $Jssdk->getSignPackage();
        $this->display();
    }

    public function GetPassport(){
        $weObj = new Wechat($this->options);
        if (I('get.state') == 'simple') {
            $AuthAccessToken = S('AuthAccessToken');
            $is_right = $weObj->getOauthAuth($AuthAccessToken['access_token'],$AuthAccessToken['openid']);
            if(!$is_right) {
                $AuthAccessToken = $weObj->getOauthAccessToken();
                $userinfo = $weObj->getOauthUserinfo($AuthAccessToken['access_token'], $AuthAccessToken['openid']);
            }

            $this->assign('userinfo',$userinfo);
            $this->display('GetPassport');
            exit;
        }
        if (!session('openid') || !cookie('auto_login_openid')) {
            $selfUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $url = $weObj->getOauthRedirect($selfUrl, 'simple');
            header("location: ".$url);
        }
    }

    private function LinkKefu($openId){
        $weObj = new Wechat($this->options);
        /*$OnlineKf = $weObj->getCustomServiceOnlineKFlist();
        $OnlineKf = $OnlineKf['kf_online_list'];
        foreach ($OnlineKf as $key => $value) {
            $OnlineKf[$key]['match'] = $value['auto_accept'] - $value['accepted_case'];
        }
        foreach ($OnlineKf as $item) {
            $match[] = $item['match'];
        }
        array_multisort($match, SORT_DESC, $OnlineKf);
        $LinkKf = $OnlineKf[0];
        $weObj->createKFSession($openId,$weObj);*/
        $weObj->transfer_customer_service();
    }


    private function SendReply($text , $weObj){
        $key_id = $this->GetKeywordID($text, $this->wid);
        if (empty($key_id)) {
            //获取不到对应关键字ID时，$keydata为空，则回复用户默认的文本信息
            /*if ($text == '绑定') {
                $uid = $weObj->getRev()->getRevFrom();
                $uinfo = $weObj->getUserInfo($uid);
                //TODO:未完成，未测试
                if ($uinfo) {
                    $weObj->text(implode('-', $uinfo) . ':' . $actoken)->reply();
                } else {
                    $weObj->text($weObj->errCode . '--' . $weObj->errMsg)->reply();
                }
                exit;
            }*/
            $replycontent = D('WechatSpecial')->where(array('is_subscribe' => 0, 'wechat_id' => $this->wid))->select();
        }else {
            $replycontent = $this->ReplyKeywordContent($key_id, $this->wid);   //获取回复内容
        }
        $step = 0;
        foreach ($replycontent as $key => $value) {
            if($step == 0) {
                switch ($value['type_id']) {
                    case '1':
                        //文本回复
                        $weObj->text($value['content'])->reply();
                        break;
                    case '2':
                        //图片回复功能
                        $media = M('WechatMedia')->find($value['content']);
                        $weObj->image($media['media_id'])->reply();
                        break;
                    case '3':
                        //语音回复功能
                        $media = M('WechatMedia')->find($value['content']);
                        $weObj->voice($media['media_id'])->reply();
                        break;
                    case 4:
                        //视频回复功能
                        $media = M('WechatMedia')->find($value['content']);
                        $weObj->video($media['media_id'])->reply();
                        break;
                    case 5:
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

                        $new = M('WechatNewsRelation')->find($value['content']);
                        $news = M('WechatNews')->where(array('id'=>array('in',$new['ids'])))->select();
                        foreach ($news as $k => $v) {
                            $newsData[] = array(
                                'Title' => $v['title'],
                                'Description' => $v['digest'],
                                'PicUrl' => 'http://'.C('DOMAIN').'/'.getMediaSrc($v['thumb_media_id']),
                                'Url' => $v['content_source_url']
                            );

                        }
                        $weObj->news($newsData)->reply();
                        //图文回复功能
                        break;
                }
            }else{
                $rec = $weObj->getRevData();
                $subdata = $weObj->getUserInfo($rec['FromUserName']);
                $open_id = $subdata['openid'];
                switch ($value['type_id']) {
                    case '1':
                        $data = array(
                            'touser'=> $open_id,
                            'msgtype'=> 'text',
                            'text'=>array(
                                'content'=> $value['content']
                            )
                        );
                        break;
                    case '2':
                        $data = array(
                            'touser'=> $open_id,
                            'msgtype'=> 'image',
                            'image'=>array(
                                'media_id'=> $value['media_id']
                            )
                        );
                        break;
                    case '3':
                        $data = array(
                            'touser'=> $open_id,
                            'msgtype'=> 'voice',
                            'voice'=>array(
                                'media_id'=> $value['media_id']
                            )
                        );
                        break;
                    case '4':
                        $data = array(
                            'touser'=> $open_id,
                            'msgtype'=> 'video',
                            'video'=>array(
                                'media_id'=> $value['media_id'],
                                'thumb_media_id'=> $value['media_id'],
                                "title" => $value['title'],
                                "description" => $value['description'],
                            )
                        );
                        break;
                    case 5:
                        break;
                }
                $weObj->sendCustomMessage($data);
            }
            $step++;
        }
    }


}