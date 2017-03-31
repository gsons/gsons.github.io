<?php
namespace Home\Model;
use Sky\Logic\BaseLogic;

class PersonalModel extends BaseLogic{

    protected $connection = 'DB_OPEN';
    protected $tableName  = 'apply';
    protected $tablePrefix  = 'open_';

    protected $_validate = array(

        array('realname','require','请填写您的真实姓名'),
        array('realname','/^[\x{4e00}-\x{9fa5}]+$/u','请填写正确的姓名',BaseLogic::MUST_VALIDATE,'regex'),
        array('realname','0,10','姓名长度不能超过10个字',BaseLogic::MUST_VALIDATE,'length'),

        array('company','0,50','工作单位长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),

        array('identity_number','require','请填写身份证号码'),
        array('identity_number','/^[1-9]([0-9]{14}|[0-9]{17})$/','请填写正确的身份证号码'),
        array('identity_number','0,18','请填写正确的身份证号码',BaseLogic::MUST_VALIDATE,'length'),

        array('zipcode','require','请填写邮政编码'),
        array('zipcode','0,10','请填写正确的邮政编码',BaseLogic::MUST_VALIDATE,'length'),
        array('zipcode','is_numeric','请填写正确的邮政编码',BaseLogic::MUST_VALIDATE,'function'),

        array('telephone','require','请填写联系电话'),
        array('telephone','0,20','请填写正确联系电话',BaseLogic::MUST_VALIDATE,'length'),

        array('phone_number','require','请填写手机号码'),
        array('phone_number','0,20','请填写正确的手机号码',BaseLogic::MUST_VALIDATE,'length'),
        array('phone_number','is_numeric','请填写正确的手机号码',BaseLogic::MUST_VALIDATE,'function'),

        array('email','require','请填写电子邮箱'),
        array('email','email','请填写正确的电子邮箱',BaseLogic::MUST_VALIDATE),

        array('address','require','请填写通信地址'),
        array('address','0,55','通信地址长度不能超过55个字',BaseLogic::MUST_VALIDATE,'length'),

        array('file_name','0,50','文件名称长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),
        array('document_number','0,50','文号长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),

        array('description','require','请填写特征描述'),
        array('description','0,200','特征描述长度不能超过200个字',BaseLogic::MUST_VALIDATE,'length'),

        array('usage_attach','require','请上传自身特殊需要的相关证明'),

    );

    protected $_auto = array(

        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),

    );

}