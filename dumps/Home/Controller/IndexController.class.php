<?php
namespace Home\Controller;

class IndexController extends PublicController{

    public function Index(){
        set_time_limit(0);
        // 顶部推荐

        // 不包含过期推荐
        // code....
        // 首页头条:rank  = 99
        $list1 = get_list(array('type_id'=>array('not in',get_child_idArr('1440')),'recommend'=>array('in',array(/*0,*/2)),'status'=>array('GT',0),'rank'=>array('in',array(0,99)),'special_id'=>0),'1,7','Article','title,id','rank desc, sort desc , create_date desc');
        $list1_1 = $list1[0];
        unset($list1[0]);

        // 文章轮播图
        $list2 =  M('Adv')->where(array('status'=>1,'type_id'=>1,'expiration_time'=>array('GT',NOW_TIME)))->field('name,link,src')->order('sort desc')->limit(0,5)->select();
        if(count($list2) < 5){
            $model = M('article');
            $standby = $model->where(array('type_id'=>array('not in',get_child_idArr('1440')  ),'special_id'=>0,'src'=>array('NEQ',''),'status'=>array('GT','0')))->order(' sort desc ,create_date desc')->field('id,title as name,src,create_date')->limit(0, 5 - count($list2))->select();
            foreach ($standby as $key => $value) {
                $value['link'] = U('News/Details',array('id'=>$value['id']));
                unset($value['id']);
                $list2[] = $value;
            }
        }

        // 郁南新闻
         $list3 = get_list(array('recommend'=>array('in',array(0,2)),'type_id'=>array('in',get_child_idArr(19,',')),'rank'=>array('LT',99)),'1,8','Article','title,id,description,create_date','rank desc, create_date desc');

        // 部门动态
         $list4 = get_list(array('recommend'=>array('in',array(0,2)),'type_id'=>array('in',get_child_idArr(1378,',')),'rank'=>array('LT',99)),'1,8','Article','title,id,description,create_date','rank desc, create_date desc');

        // 乡镇动态
         $list5 = get_list(array('recommend'=>array('in',array(0,2)),'type_id'=>array('in',get_child_idArr(1071,',')),'rank'=>array('LT',99)),'1,8','Article','title,id,description,create_date','rank desc, create_date desc');

        // 公示公告
         $list6 = get_list(array('recommend'=>array('in',array(0,2)),'type_id'=>array('in',get_child_idArr(2072,',')),'rank'=>array('LT',99)),'1,8','Article','title,id,description,create_date','rank desc, create_date desc');


        // 广告轮播图
        $list7 = M('Adv')->where(array('status'=>1,'type_id'=>2))->field('name,link,src')->order('sort desc,create_date desc')->limit(0,2)->select();

        // 底部轮播图
        $list8 = M('Adv')->where(array('status'=>1,'type_id'=>3))->field('name,link,src')->order('sort desc,create_date desc')->limit(0,6)->select();

        // 项目建设
        $list9[] = get_list(array('type_id'=>array('in',get_child_idArr(2069,','))),'1,5');

        // 投资指南
        $list9[] = get_list(array('type_id'=>array('in',get_child_idArr(2068,','))),'1,5');

        // 招商动态
        $list9[] = get_list(array('type_id'=>array('in',get_child_idArr(2070,','))),'1,5');

        // 政策法规
        $list9[] = get_list(array('type_id'=>array('in',get_child_idArr(1565,','))),'1,5');


        /**************  各镇信息  **************/
        // 宝珠信息
        $town[0]['name'] = '平台';
        $town[0]['id'] = '1149';
        $town[0]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1149,','))),'1,2');

        // 宝珠信息
        $town[1]['name'] = '都城';
        $town[1]['id'] = '1148';
        $town[1]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1148,','))),'1,2');

        // 桂圩信息
        $town[2]['name'] = '桂圩';
        $town[2]['id'] = '1150';
        $town[2]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1150,','))),'1,2');

        // 建城信息
        $town[3]['name'] = '建城';
        $town[3]['id'] = '1152';
        $town[3]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1152,','))),'1,2');

        // 宝珠信息
        $town[4]['name'] = '宝珠';
        $town[4]['id'] = '1153';
        $town[4]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1153,','))),'1,2');

        // 南江口信息
        $town[5]['name'] = '南江口';
        $town[5]['id'] = '1162';
        $town[5]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1162,','))),'1,2');

        // 通门信息
        $town[6]['name'] = '通门';
        $town[6]['id'] = '1151';
        $town[6]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1151,','))),'1,2');

        // 大方信息
        $town[7]['name'] = '大方';
        $town[7]['id'] = '1154';
        $town[7]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1154,','))),'1,2');

        // 历洞信息
        $town[8]['name'] = '历洞';
        $town[8]['id'] = '1161';
        $town[8]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1161,','))),'1,2');

        // 连滩信息
        $town[9]['name'] = '连滩';
        $town[9]['id'] = '1160';
        $town[9]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1160,','))),'1,2');

        // 东坝信息
        $town[10]['name'] = '东坝';
        $town[10]['id'] = '1159';
        $town[10]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1159,','))),'1,2');

        // 千官信息
        $town[11]['name'] = '千官';
        $town[11]['id'] = '1155';
        $town[11]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1155,','))),'1,2');

        // 大湾信息
        $town[12]['name'] = '大湾';
        $town[12]['id'] = '1156';
        $town[12]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1156,','))),'1,2');

        // 河口信息
        $town[13]['name'] = '河口';
        $town[13]['id'] = '1157';
        $town[13]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1157,','))),'1,2');

        // 宋桂信息
        $town[14]['name'] = '宋桂';
        $town[14]['id'] = '1158';
        $town[14]['list'] = get_list(array('type_id'=>array('in',get_child_idArr(1158,','))),'1,2');

        // 重点领域信息公开专栏_子栏目
        $child = M('ArticleType')->where(array('pid'=>'1690'))->field('id,name')->limit(0,10)->select();

        $catalog = M('Catalog','open_','DB_OPEN')->where(array('status'=>1))->limit(5)->select();
        foreach ($catalog as $key => $value) {
            $catalog[$key]['list'] = M('Information','open_','DB_OPEN')->where(array('catalog_id'=>$value['id'],'status'=>2))->limit(8)->order('create_date desc')->select();
        }
        $this->assign('catalog',$catalog);

        $this->assign(array(
            'list1'   => $list1,
            'list1_1' => $list1_1,
            'list2'   => $list2,
            'list3'   => $list3,
            'list4'   => $list4,
            'list5'   => $list5,
            'list6'   => $list6,
            'list7'   => $list7,
            'list8'   => $list8,
            'list9'   => $list9,
            'child'   => $child,
            'town'    => $town,
            ));
        $this->display();
    }


    public function Preview(){
        $id = I('id');

        if (!is_admin()  || empty($id))
            redirect('/');

        $model = M('Article');
        $arc = $model->find($id);
        empty($arc) && redirect('/');

        $this->assign(array(
            'arc'   => $arc ,
            ));
        $this->display('Article/Preview');
    }

    public function GetArticle(){
        exit;
        set_time_limit(0);
        $url = 'http://localhost:8080/?ids=';
        $start = 78870;
        $max = 79245;
        while( $start < $max){
            $ids = array();
            for($i = $start ; $i < $start+10 ; $i++ ){
                $ids[] = $i;
            }
            $query = implode(',',$ids);
            $quest = $url.$query;

            $content = file_get_contents($quest);
            libxml_disable_entity_loader(true);
            $xmlstring = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = json_decode(json_encode($xmlstring),true);
            foreach ($data['Table'] as $key => $value) {
                var_dump($value['GeneralID']);
                $article = array(
                    'id'=>$value['GeneralID'],
                    'type_id'=>$value['NodeID'],
                    'title'=>$value['Title'],
                    'content'=>$value['Content'],
                    'writer'=>$value['Inputer'] ? $value['Inputer'] : '',
                    'click'=>$value['Hits'] ? $value['Hits'] : 0,
                    'description'=>msubstr(strip_tags($value['Content']),0,50),
                    'update_date'=>time(),
                    'create_date'=>strtotime($value['InputTime']),
                );
                $exist = M('Article')->where(array('id'=>$article['id']))->count();
                if( $exist > 0 ){
                    $res = M('Article')->where(array('id'=>$article['id']))->save($article);
                }else{
                    $res = M('Article')->data($article)->add();
                }
            }
            $start = $start + 10;
            var_dump($start);
        }
    }

    public function GetOpen(){
        set_time_limit(0);
        $url = 'http://localhost:8080/open.aspx?ids=';
        $start = 8220;
        $max = 8165;
        while( $start < $max){
            $ids = array();
            for($i = $start ; $i < $start+10 ; $i++ ){
                $ids[] = $i;
            }
            $query = implode(',',$ids);
            $quest = $url.$query;

            $content = file_get_contents($quest);
            libxml_disable_entity_loader(true);
            $xmlstring = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = json_decode(json_encode($xmlstring),true);
            foreach ($data['Table'] as $key => $value) {
                if( !is_array($value) ){
                    $value = $data['Table'];
                }
                $cn = $value['PublicCatalogName'];
                if( $cn == '领导和分工' ){
                    $cn = '领导信息';
                }
                $cid = M('Catalog','open_','DB_OPEN')->where(array('name'=>$cn))->find();
                $article = array(
                    'id'=>$value['ID'],
                    'department_id'=>$value['DepartmentId'],
                    'department_name'=>$value['Inputer'],
                    'catalog_id'=>$cid['id'] ? $cid['id'] : 0,
                    'theme_id'=>$value['ThemeId'],
                    'index_number'=>$value['IndexNumber'],
                    'title'=>$value['Title'],
                    'keyword'=>$value['Keyword'] ? $value['Keyword'] : '',
                    'document_number'=>$value['DocumentNumber'] ? $value['DocumentNumber'] : '',
                    'content'=>$value['Content'],
                    'description'=>msubstr(strip_tags($value['Content']),0,70),
                    'public_type'=>$value['PublicType'],
                    'attach'=> '',
                    'expired'=> $value['IsExpired'] ? 0 : 1,
                    'expired_date'=> 0,
                    'public_date'=>strtotime($value['PublicTime']),
                    'update_date'=>strtotime($value['InputTime']),
                    'create_date'=>strtotime($value['InputTime']),
                    'status'=>$value['Status'],
                );
                $exist = @M('Information','open_','DB_OPEN')->where(array('id'=>$article['id']))->count();
                if( $exist > 0 ){
                    $res = @M('Information','open_','DB_OPEN')->where(array('id'=>$article['id']))->save($article);
                }else{
                    $res = @M('Information','open_','DB_OPEN')->data($article)->add();
                }
                var_dump($res);
            }
            $start = $start + 10;
            var_dump($start);
        }
    }

    public function GetLetter(){
        exit;
        set_time_limit(0);
        $url = 'http://localhost:8080/Letter.aspx?ids=';
        $start = 3105;
        $max = 3495;
        while( $start < $max){
            $ids = array();
            for($i = $start ; $i < $start+10 ; $i++ ){
                $ids[] = $i;
            }
            $query = implode(',',$ids);
            $quest = $url.$query;

            $content = file_get_contents($quest);
            libxml_disable_entity_loader(true);
            $xmlstring = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = json_decode(json_encode($xmlstring),true);
            foreach ($data['Table'] as $key => $value) {
                $article = array(
                    'id'=>$value['LetterId'],
                    'type_id'=>$value['LetterTypeId'],
                    'title'=>$value['Title'],
                    'content'=>$value['Content'],
                    'department_id'=>$value['DepartmentId'],
                    'name'=>$value['Name'],
                    'sex'=> $value['Sex'],
                    'age'=>$value['Age'],
                    'profession'=>$value['Profession'],
                    'address'=>$value['Address'],
                    'email'=>$value['Email'],
                    'telphone'=>$value['Telphone'],
                    'certificate_name'=>$value['CertificateName'],
                    'identity_number'=>$value['IdentityNumber'],
                    'input_time'=> FormatDate(strtotime($value['InputTime'])),
                    'status'=>$value['Status'],
                    'index_number'=>$value['IndexNumber'],
                    'is_public'=> $value['IsPublic'] ? 1 : 0,
                    'accessory'=>$value['Accessory'],
                    'finish_time'=>FormatDate(strtotime($value['FinishTime'])),
                    'end_time'=>FormatDate(strtotime( $value['EndTime'])),
                    'ip'=> $value['IP'],
                    'feedback'=>$value['FeedBack'],
                    'datetime'=>strtotime($value['InputTime']),
                    'first_department_id'=> 0,
                );
                $exist = M('Letter')->where(array('id'=>$article['id']))->count();
                if( $exist > 0 ){
                    $res = M('Letter')->where(array('id'=>$article['id']))->save($article);
                }else{
                    $res = M('Letter')->data($article)->add();
                }
                var_dump($res);
            }
            $start = $start + 10;
            var_dump($start);
        }
    }

    public function GetHistory(){
        exit;
        set_time_limit(0);
        $url = 'http://localhost:8080/LetterHistory.aspx?ids=';
        $start = 3407;
        $max = 6099;
        while( $start < $max){
            $ids = array();
            for($i = $start ; $i < $start+10 ; $i++ ){
                $ids[] = $i;
            }
            $query = implode(',',$ids);
            $quest = $url.$query;

            $content = file_get_contents($quest);
            libxml_disable_entity_loader(true);
            $xmlstring = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = json_decode(json_encode($xmlstring),true);
            foreach ($data['Table'] as $key => $value) {
                if( !is_array($value) ){
                    $value = $data['Table'];
                }
                $article = array(
                    'id'=>$value['ID'],
                    'letter_id'=>$value['LetterId'],
                    'department_id'=>$value['DepartmentId'],
                    'admin_name'=>$value['AdminName'],
                    'remark'=>$value['Remark'],
                    'status'=>$value['Status'],
                    'result'=>$value['Result'],
                    'turn_over_department_id'=>$value['TurnOverDepartmentId'],
                    'target_time'=>strtotime($value['TargetDate']),
                    'update_time'=>strtotime($value['EndTime']),
                    'old_target_time'=>0,
                );
                $exist = M('LetterHistory')->where(array('id'=>$article['id']))->count();
                if( $exist > 0 ){
                    $res = M('LetterHistory')->where(array('id'=>$article['id']))->save($article);
                }else{
                    $res = M('LetterHistory')->data($article)->add();
                }
                var_dump($res);
            }
            $start = $start + 10;
            var_dump($start);
        }
    }

    public function json2s(){
        exit;
        $article = M('Article')->where(array('video'=>array('neq','')))->select();
        foreach ($article as $key => $value) {
            $where['id'] = $value['id'];
            $video = json_decode(htmlspecialchars_decode($value['video']),true);
            $save = array('video'=>serialize($video));
            $res = M('Article')->where($where)->save($save);
            var_dump($res);
        }
    }

    /*public function add_data(){
        set_time_limit(0);
        ini_set('memory_limit',-1);
        $temp_pk = 0 ;
        for ($i=1; $i < 999; $i++) {
            $data = M()->table('pe_gv_letters2017')->page($i,10)->select();
            // dump($data);exit;
            foreach ($data as $key => $value) {
                if($temp_pk == $value['letterid']){
                    break;
                }else{

                    $data[$key]['id']               = $value['letterid'];
                    $data[$key]['type_id']          = $value['lettertypeid'];
                    $data[$key]['department_id']    = $value['departmentid'];
                    $data[$key]['identity_number']  = $value['identitynumber'];
                    $data[$key]['index_number']      = $value['indexnumber'];
                    $data[$key]['certificate_name'] = $value['certificatename'];
                    $data[$key]['is_public']        = $value['ispublic'];
                    $data[$key]['datetime']               = strtotime($value['inputtime']);
                }
            }
            // dump($data);exit;
            if(empty($data)){
                exit('没了');
            }else{
                M('letter')->addall($data);
                // exit;
            }
        }

        exit;
    }*/
}