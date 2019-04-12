<?php
/*CREATE TABLE `video` (
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
  KEY `tag` (`tag_name`) USING BTREE,
  KEY `content_url` (`content_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


*/


require_once './vendor/owner888/phpspider/core/selector.php';

use phpspider\core\selector;

$tagObj = [
    '欧美' => 40,
    '主播' => 40,
    '三级' => 40,
    '动图' => 40,
    '日韩' => 140,
    '国产' => 1400,
];

class Tag extends \Thread
{
    private $name;
    private $page_min;
    private $page_max;
    const INFO = 'INFO';
    const ERROR = "ERROR";
    const WARN = "WARNING";
    public static $file = 'log.txt';

    public function __construct($name, $page_min, $page_max)
    {
        $this->name = $name;
        $this->page_min = $page_min;
        $this->page_max = $page_max;
    }

    /**
     * @param $conn mysqli
     * @param $p
     * @return bool
     */
    public function add($conn, $p)
    {
        $content = file_get_contents('https://51kav.cc/tag/' . $this->name . '/page/' . $p);
        $content_url = selector::select($content, "//li[contains(@class,'i_list list_n1')]/a/@href");
        if (!$content_url) return false;
        if (!is_array($content_url)) $content_url = [$content_url];
        $this->gbk_echo("find content page:" . 'https://51kav.cc/tag/' . $this->name . '/page/' . $p);
        foreach ($content_url as $url) {
            $sql = /** @lang text */
                "INSERT INTO video_tag (tag_name, content_url)VALUES ('{$this->name}','{$url}')";
            $conn->query($sql);
        }
        return true;
    }

    public function log($msg, $msgType = self::INFO)
    {
        $info = '[' . date('Y-m-d H:i:s') . '] ';
        switch ($msgType) {
            case self::ERROR:
                $info .= 'ERROR:';
                break;
            case self::WARN:
                $info .= 'WARNING:';
                break;
            case self::INFO:
                $info .= 'INFO:';
                break;
            default:
                $info .= 'INFO:';
                break;
        }

        $info .= PHP_EOL;
        $info .= $msg;
        $info .= PHP_EOL;
        file_put_contents(self::$file, $info, FILE_APPEND);
    }

    public function run()
    {
        $pageArr = range($this->page_min, $this->page_max);
        $failPageArr = [];
        $conn = new mysqli('127.0.0.1', 'root', 'root', 'test');
        foreach ($pageArr as $page) {
            $res = $this->add($conn, $page);
            if (!$res) {
                break;
                $failPageArr[] = $page;
            }
        }
         foreach ($failPageArr as $page) {
             $res = $this->add($conn, $page);
             if (!$res) {
                 $this->gbk_echo("{$this->name}:{$page}抓取失败");
                 $this->log("{$this->name}:{$page}抓取失败", self::ERROR);
             }
         }
    }

    public function gbk_echo($msg)
    {
        echo iconv('UTF-8', 'gbk//IGNORE', $msg) . PHP_EOL;
    }

}

class Video extends \Thread
{
    private $page_min;
    private $page_max;
    const INFO = 'INFO';
    const ERROR = "ERROR";
    const WARN = "WARNING";
    public static $file = 'log.txt';
    private $url;
    // public function __construct($page_min, $page_max)
    // {
    //     $this->page_min = $page_min;
    //     $this->page_max = $page_max;
    // }
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function add($conn, $p)
    {
        $url_='https://51kav.cc/page/' . $p;

        $content = file_get_contents($url_);
        $content_url = selector::select($content, "//li[contains(@class,'i_list list_n1')]/a/@href");
        $num=0;
        while(!$content_url){
            $num++;
            $content = file_get_contents($url_);
            $content_url = selector::select($content, "//li[contains(@class,'i_list list_n1')]/a/@href");
            if($num>5) break;
        }

        if ($num==6){
            $this->log("LIST_URL:{$url_}下载失败!",self::ERROR);
            return false;
        }
        if (!is_array($content_url)) $content_url = [$content_url];
        $this->gbk_echo("find List page:" . 'https://51kav.cc/page/' . $p);
        foreach ($content_url as $url) {
            $video = $this->getVideo($url);
            $num=0;
            while(!$video){
                $num++;
                $video = $this->getVideo($url);
                if($num>5) break;
            }
            if($num!==6) $this->insert($conn, 'video', $video);
            else {
                $this->log("CONTENT_URL:{$url}下载失败!",self::ERROR);
            }
        }
        return true;
    }

    /**
     * @param $conn mysqli
     * @param string $table
     * @param null $data
     * @param bool $return_sql
     * @return bool|int|string
     */
    public static function insert($conn, $table = '', $data = null, $return_sql = false)
    {
        $items_sql = $values_sql = "";
        foreach ($data as $k => $v) {
            $v = stripslashes($v);
            $v = addslashes($v);
            $items_sql .= "`$k`,";
            $values_sql .= "\"$v\",";
        }
        $sql = /** @lang text */
            "Insert Ignore Into `{$table}` (" . substr($items_sql, 0, -1) . ") Values (" . substr($values_sql, 0, -1) . ")";
        if ($return_sql) {
            return $sql;
        } else {
            if ($conn->query($sql)) {
                return mysqli_insert_id($conn);
            } else {
                return false;
            }
        }
    }

    public function getVideo($url)
    {
        $content = file_get_contents($url);
        $fieldArr = [
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
            ]
        ];
        $data = [];
        foreach ($fieldArr as $vo) {
            $res = selector::select($content, $vo['selector']);
            if (!$res) return false;
            if (is_array($res)) {
                $res = implode(',', $res);
            }
            $res=trim($res);
            $data[$vo['name']] = $res;
        }
        $data['content_url'] = $url;
        $data['add_time'] = strtotime($data['add_time_text']);
        return $data;
    }

    // public function run()
    // {
    //     $pageArr = range($this->page_min, $this->page_max);
    //     $conn = new mysqli('127.0.0.1', 'root', 'root', 'test');
    //     foreach ($pageArr as $page) {
    //         $this->add($conn, $page);
    //     }
    // }
    public function run(){
        $conn = new mysqli('127.0.0.1', 'root', 'root', 'test');
        $num=0;$res=false;
        while(!$res){
           $res= $this->getVideo($this->url);
           if($res) break;
           $num++;
           if($num>99){
               break;
           }
        }
        if($num==100){
            $this->log("CONTENT_URL:{$this->url}下载失败!",self::ERROR);
        }else{
            $this->insert($conn,'video',$res);
        }
    }

    public function gbk_echo($msg)
    {
        echo iconv('UTF-8', 'gbk//IGNORE', $msg) . PHP_EOL;
    }

    public function log($msg, $msgType = self::INFO)
    {
        $info = '[' . date('Y-m-d H:i:s') . '] ';
        switch ($msgType) {
            case self::ERROR:
                $info .= 'ERROR:';
                break;
            case self::WARN:
                $info .= 'WARNING:';
                break;
            case self::INFO:
                $info .= 'INFO:';
                break;
            default:
                $info .= 'INFO:';
                break;
        }

        $info .= PHP_EOL;
        $info .= $msg;
        $info .= PHP_EOL;
        file_put_contents(self::$file, $info, FILE_APPEND);
    }
}
// function getVideoArr($pageNum, $count)
// {
//    $videoArr = [];
//    $num = ceil($pageNum / $count);
//    for ($i = 0; $i < $num; $i++) {
//        $videoArr[] = new Video($i * $count + 1, $i * $count + $count);
//    }
//    return $videoArr;
// }
// $videos=getVideoArr(845,2);
// foreach ($videos as $video) {
//    $video->start();
// }
$content=file_get_contents('err.txt');
preg_match_all('/CONTENT_URL:(.*)下载失败!/',$content,$matchs);
$matchs=$matchs[1];
$videoArr = [];
foreach($matchs as $url){
    $videoArr[]=new Video($url); 
}
foreach ($videoArr as $video) {
   $video->start();
}

// function getTagArr($tagName, $pageNum, $count)
// {
//     $tagArr = [];
//     $num = ceil($pageNum / $count);
//     for ($i = 0; $i < $num; $i++) {
//         $tagArr[] = new Tag($tagName, $i * $count + 1, $i * $count + $count);
//     }
//     return $tagArr;
// }

// $tags = [];
// foreach ($tagObj as $k => $v) {
//     $tagList = getTagArr($k, $v, 20);
//     $tags = array_merge($tags, $tagList);
// }
// $c=count($tags);
// echo '线程总数:'.$c;

// foreach ($tags as $tag) {
//     $tag->start();
// }
