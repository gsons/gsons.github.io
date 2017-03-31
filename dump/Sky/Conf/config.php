<?php
return array(
	//'配置项'=>'配置值'

    'AUTH_CONFIG'=>array(
        'AUTH_ON'           => true, //认证开关
        'AUTH_TYPE'         => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP'        => 'sky_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'sky_auth_group_access', //用户组明细表
        'AUTH_RULE'         => 'sky_skymenu', //权限规则表
        'AUTH_USER'         => 'sky_admin'//用户信息表
    ),

    'LOG_TYPE' =>  'File', // 日志记录类型 默认为文件方式
);