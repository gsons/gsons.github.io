<?php

/**
 * 获取微信昵称
 * @param string $oepnid 用户OPENID
 * @return string 返回用户昵称
 */
function getWxNick($openid){
    return M('WechatUser')->where(array('openid'=>$openid))->getField('nickname');
}

/**
 * 转化本地素材路径
 * @param string $src 素材媒体ID
 * @return string 媒体文件真实路径
 */
function makeMediaSrc($src){
    if ( is_file('./Uploads/'.$src) ){
        return __ROOT__.'/Uploads/'.$src;
    }else{
        return __ROOT__.'/Uploads/Focus/'.$src;
    }
}

/**
 * 转化群发消息发送结果
 * @param string $msg
 * @return string 返回结果中文名称
 */
function turnMassStatus($msg){
    switch($msg){
        case 'send success':
        case 'SEND_SUCCESS':
            return '发送成功';
            break;
        case 'send fail':
            return '发送失败';
            break;
        case 'err(10001)':
            return '发送失败【涉嫌广告】';
            break;
        case 'err(20001)':
            return '发送失败【涉嫌政治】';
            break;
        case 'err(20004)':
            return '发送失败【涉嫌社会】';
            break;
        case 'err(20002)':
            return '发送失败【涉嫌色情】';
            break;
        case 'err(20006)':
            return '发送失败【涉嫌违法犯罪】';
            break;
        case 'err(20008)':
            return '发送失败【涉嫌欺诈】';
            break;
        case 'err(20013)':
            return '发送失败【涉嫌版权】';
            break;
        case 'err(22000)':
            return '发送失败【涉嫌互推(互相宣传)】';
            break;
        case 'err(21000)':
            return '发送失败【涉嫌其他】';
            break;
        case '-1':
            return '已删除';
            break;
        default:
            return '未查询到结果或未知';
    }
}

/**
 * 转化素材类型名称
 * @param int $type 类型ID
 * @return string 类型中文名称
 */
function turnMsgType($type){
    switch($type){
        case '1':
            return '文本';
        case '2':
            return '图片';
        case '3':
            return '音频';
        case '4':
            return '视频';
        case '5':
            return '图文消息';
    }
}

/**
 * 根据消息类型以及消息ID返回相应的发送内容
 * @param int $type 类型id
 * @param string $content 消息
 * @return string 内容
 */
function makeMsgContent( $content , $mass_type){
    switch($mass_type){
        case '1':
            return $content ;
            break;
        case '2':
            $media = M('WechatMedia')->find($content);
            return '<img src="'.__ROOT__.'/Upload/'.$media['src'].'" >';
        case '3':
            $media = M('WechatMedia')->find($content);
            return '<voice src="'.__ROOT__.'/Upload/'.$media['src'].'" ></voice>';
        case '4':
            $media = M('WechatMedia')->find($content);
            return '<video src="__UPLOAD__/'.$media['src'].'" controls="controls" height="100px" width="200px"></video>';
        case '5':
            $news = M('WechatNewsRelation')->find($content);
            return $news['title'];
        default:
            exit;
    }
}

/**
 * 是否具有该权限
 * @param $rules 权限节点
 */
function checkPoint($rules,$id){
    if( $id ) {
        foreach ($rules as $key => $value) {
            if ($value == $id) {
                return 'checked';
            }
        }
    }
    return 'false';
}