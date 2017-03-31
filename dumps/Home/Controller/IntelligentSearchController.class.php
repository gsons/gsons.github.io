<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 10:04
 */
namespace Home\Controller;

class IntelligentSearchController extends PublicController{
    /**
     * Index 显示主页。
     */
    public function Index(){
        echo "hello world!";
    }

    public function Seach(){/*搜索*/
        $keyword=I('keyword');
        if(empty($keyword)){
            $this->error("请输入搜索内容");
        }else{
            $keyword=$this->FilterSearch(stripslashes(htmlspecialchars(trim($keyword))));//过滤字符串
            if(mb_strlen($keyword,'utf-8')<2||strlen($keyword)<4)/*搜索内容不能过少*/
            {
                $this->error('搜索内容过于简陋');
            }
        }
        $cid=I('cid',0);
        $cid=$this->GetCategory($cid);
        $nokeyword=I('nokeyword');/*判断有无去掉搜索内容*/
        $nokeyword=(isset($nokeyword))? addslashes(htmlspecialchars(trim($nokeyword))) : "";
        $keyword=$this->GetKeywords($keyword);/*对搜索内容分词，获取搜索关键词。*/

    }

    /**
     * FilterSearch 过滤搜索字符串内容
     * @param $keyword
     * @return mixed|string
     */
    private function FilterSearch($keyword)
    {
        global $cfg_soft_lang;
        if($cfg_soft_lang=='utf-8')
        {
            $keyword = preg_replace("/[\"\r\n\t\$\\><']/", '', $keyword);
            if($keyword != stripslashes($keyword))
            {
                return '';
            }
            else
            {
                return $keyword;
            }
        }
        else
        {
            $restr = '';
            for($i=0;isset($keyword[$i]);$i++)
            {
                if(ord($keyword[$i]) > 0x80)
                {
                    if(isset($keyword[$i+1]) && ord($keyword[$i+1]) > 0x40)
                    {
                        $restr .= $keyword[$i].$keyword[$i+1];
                        $i++;
                    }
                    else
                    {
                        $restr .= ' ';
                    }
                }
                else
                {
                    if(preg_match("/[^0-9a-z@#\.]/",$keyword[$i]))
                    {
                        $restr .= ' ';
                    }
                    else
                    {
                        $restr .= $keyword[$i];
                    }
                }
            }
        }
        return $restr;
    }

    /**
     * GetCategory 获取category模型ID及子ID
     * @param int $cid
     * @return mixed|string
     */
    private function GetCategory($cid=0){
        $categoryFile=$_SERVER['DOCUMENT_ROOT'].C('TMPL_PARSE_STRING.__PUBLIC__').'/Home/data/cache/categoryitem.inc';
        if(!file_exists($categoryFile) || filemtime($categoryFile) < time()-(3600*24)){
            $fp = fopen($categoryFile, 'w');
            fwrite($fp, "<"."?php\r\n");
            $rst=M('Category')->field('id,reid,typename')->select();
            foreach ($rst as $item) {
                fwrite($fp, "\$typeArr[".$item['id']."] = array(".$item['reid'].",'".$item['typename']."');\r\n");
            }
            fwrite($fp, '?'.'>');
            fclose($fp);
        }
        require_once($categoryFile);
        if(isset($typeArr) && is_array($typeArr))
        {
            $this->GetSonId($cid,$typeArr);
            $rst=join(",",$GLOBALS['idArray']);
        }
        return $rst;
    }

    /**
     * GetSonId 获取CategoryID及子ID
     * @param $id
     * @param $sArr
     * @return array
     */
    private  function GetSonId($id,$sArr){
        if($id!=0){
            $GLOBALS['idArray'][$id] = $id;
        }
        foreach ($sArr as $key=> $item) {
            if($item[0]==$id){
                $this->GetSonId($key,$sArr);
            }
        }
    }

    /**
     * GetKeywords 获取搜索关键词
     * @param $keyword
     * @return bool|string
     */
    private function GetKeywords($keyword){
        if(empty($keyword)){
            return false;
        }
        $keyword = cn_substr($keyword,50);
        $row =M('Keyword')->where(array('keyword'=>$keyword))->find();
        if(!$row){
            if(strlen($keyword)>7) {
                import("Vendor.Pscws.pscws4");
                $pscws = new \PSCWS4('utf-8');

                $pscws->set_dict(CONF_PATH . 'etc/dict.utf8.xdb');
                $pscws->set_rule(CONF_PATH . 'etc/rules.utf8.ini');
                $pscws->set_ignore(true);
                $pscws->send_text($keyword);
                $words = $pscws->get_tops(10);
                $pscws->close();
                $keywords=$keyword;
                foreach ($words as $val) {
                    $keywords .=" ".$val['word'];
                }
                $data['keyword']=$keyword;
                $data['spwords']=$keywords;
                $data['count']=1;
                $data['result']=0;
                $data['lasttime']=time();
                M('Keyword')->add($data);
            }else{
                $keywords=$keyword;
            }
        }else{
            $data['id']=$row['id'];
            $data['count']=$row['count'];
            $data['lasttime']=$row['lasttime'];
            M('Keyword')->save($data);
            $keywords=$row['spwords'];
        }
        return $keywords;
    }
}