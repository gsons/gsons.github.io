<?php
namespace Home\Model;
use Sky\Logic\BaseLogic;

class OrganizationModel extends BaseLogic{

    protected $connection = 'DB_OPEN';
    protected $tableName  = 'apply';
    protected $tablePrefix  = 'open_';

    protected $_validate = array(

        array('company_name','require','请填写您的单位名称'),
        array('company_name','0,50','单位名称长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),

        array('address','require','请填写通信地址'),
        array('address','0,55','通信地址长度不能超过55个字',BaseLogic::MUST_VALIDATE,'length'),

//        array('organization_number','require','请填写组织机构代码'),

//        array('license_number','require','请填写营业执照注册号'),

        array('legal_representative','require','请填写法人代表'),

        array('identity_number','require','请填写法人身份证号码'),
        array('identity_number','/^[1-9]([0-9]{14}|[0-9]{17})$/','请填写正确的法人身份证号码'),
        array('identity_number','0,18','请填写正确的法人身份证号码',BaseLogic::MUST_VALIDATE,'length'),

        array('contact','require','请填写联系人'),
        array('contact','0,20','请填写正确联系人',BaseLogic::MUST_VALIDATE,'length'),

        array('telephone','require','请填写联系电话'),
        array('telephone','0,20','请填写正确联系电话',BaseLogic::MUST_VALIDATE,'length'),

        array('phone_number','require','请填写手机号码'),
        array('phone_number','0,20','请填写正确的手机号码',BaseLogic::MUST_VALIDATE,'length'),
        array('phone_number','is_numeric','请填写正确的手机号码',BaseLogic::MUST_VALIDATE,'function'),

        array('email','require','请填写电子邮箱'),
        array('email','email','请填写正确的电子邮箱',BaseLogic::MUST_VALIDATE),

        array('file_name','0,50','文件名称长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),
        array('document_number','0,50','文号长度不能超过50个字',BaseLogic::MUST_VALIDATE,'length'),

        array('description','require','请填写特征描述'),
        array('description','0,200','特征描述长度不能超过200个字',BaseLogic::MUST_VALIDATE,'length'),

    );

    protected $_auto = array(

        array('create_date','time',BaseLogic::MODEL_INSERT,'function'),
        array('update_date','time',BaseLogic::MODEL_BOTH,'function'),

    );

}