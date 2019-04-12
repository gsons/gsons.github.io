<?php

/*
 
CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_url` char(150) NOT NULL DEFAULT '' COMMENT '内容页链接',
  `title` varchar(100) DEFAULT '' COMMENT '标题',
  `video` char(255) NOT NULL DEFAULT '' COMMENT '视频链接 用,隔开',
  `images` text COMMENT '图片链接 用,隔开',
  `like` int(11) DEFAULT '0' COMMENT '点赞数',
  `view` int(11) DEFAULT '0' COMMENT '观看数',
  `add_time` int(11) DEFAULT '0' COMMENT '创建时间 时间戳',
  `add_time_text` char(30) DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_url` (`content_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `video_tag` (
  `content_url` char(150) NOT NULL COMMENT '内容页链接',
  `tag_name` char(50) NOT NULL COMMENT '标签名称',
  PRIMARY KEY (`tag_name`,`content_url`),
  KEY `tag` (`tag_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/




require_once './vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\db;
/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => '52kav',
    //'tasknum' => 8,
    'log_show' => true,
    'save_running_state' => false,
    'domains' => array(
        '51kav.cc'
    ),
    'log_show' => false,
    'scan_urls' => array(
       "https://51kav.cc/page/1"
    ),
    'list_url_regexes' => array(
        "https://51kav.cc/page/\d+"
    ),
    'content_url_regexes' => array(
        "https://51kav.cc/.*/\d+.html"
    ),
    'export' => array(
        'type' => 'db', 
        'table' => 'video',
    ),
    'db_config' => array(
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'root',
        'pass'  => 'root',
        'name'  => 'kav',
    ),
    'user_agent' => array(
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G34 Safari/601.1",
        "Mozilla/5.0 (Linux; U; Android 6.0.1;zh_cn; Le X820 Build/FEXCNFN5801507014S) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/49.0.0.0 Mobile Safari/537.36 EUI Browser/5.8.015S",
    ),
    'client_ip' => array(
        '192.168.0.2',
        '192.168.0.3',
        '192.168.0.4',
        '192.168.0.5',
        '192.168.0.7',
        '192.168.0.6',
        '192.168.0.8',
        '192.168.0.9',
        '192.168.0.14',
        '192.168.0.12',
        '192.168.0.13',
        '192.168.0.14'
    ),
    'fields' =>[
         // 标题
         [
            'name' => "title",
            'selector' => "//div[@class='item_title']//h1",
            'required' => false,
        ],
        // 图片
        [
            'name' => "images",
            'selector' => "//div[@class='content_left']//p//img/@src",
            'required' => false,
            'repeated' => true,
        ],
        // 视频
        [
            'name' => "video",
            'selector' => "//video/@src",
            'required' => true,
            'repeated' => true,
        ],
        // 点赞数
        [
            'name' => "like",
            'selector' => "//em[@class='ct_ding']",
            'required' => false
        ],
        // 浏览数
        [
            'name' => "view",
            'selector' => "//span[@class='cx-views']",
            'required' => false
        ],
        // 创建时间
        [
            'name' => "add_time_text",
            'selector' => '//span[contains(./text(), "发布于")]/following::text()[1]',
            'required' => false
        ],
              // 创建时间
              [
                'name' => "add_time",
                'selector' => '//span[contains(./text(), "发布于")]/following::text()[1]',
                'required' => false
            ],
        // 创建url
        [
            'name' => "content_url",
            'selector' =>"//span[@class='cx-views']",
            'required' => false
        ]
        ],
);

$spider = new phpspider($configs);


$spider->on_extract_field = function($fieldName, $data, $page)
{
    if(is_string($data)) $data=trim($data);
    if ($fieldName == 'title')
    {
        $data = strip_tags($data);
    }
    elseif ($fieldName == 'images'){
        if(is_array($data)&&!empty($data)){
            $data = implode(",",$data);
        }
    }
    elseif ($fieldName == 'video')
    {
        if(is_array($data)&&!empty($data)){
            foreach($data as &$v){
                $index=strpos($v,'?');
                if($index>0){$v=substr($v,0,$index);}
            }
            $data = implode(",",$data);
        }
    }
    elseif ($fieldName == 'add_time')
    {
        $data=strtotime($data);
    }
    elseif ($fieldName == 'content_url')
    {
        $data=$page['url'];
    }
    return $data;
};



$spider->start();
