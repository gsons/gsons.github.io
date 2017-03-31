<?php
namespace Sky\Model;
use Sky\Logic\BaseLogic;

class ArticleRecommendModel extends BaseLogic {

    protected $_validate = array(
        array('reason','require','请填写原因'),
        array('level','require','请选择推荐位置'),
    );

    protected $_auto = array(

        array('admin_id','is_admin',BaseLogic::MODEL_INSERT,'function'),
        array('dealer_id','is_admin',BaseLogic::MODEL_BOTH,'function'),
        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),

    );

}