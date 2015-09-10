<?php

// 各种url	---------------------------------------------------------------------------------------------------
$config['domain']		=	'http://admin.project.com/';		// 网站域名
$config['image_url']	=	$config['domain'].'source/image/';	// 图片URL

// 各种路径	---------------------------------------------------------------------------------------------------
$config['image_path']	=	FCPATH.'source/image/';	// 图片存放路径

// session 的 key 值	---------------------------------------------------------------------------------------------------
$config['admin_sess']		=	'admin_user_info';	// 用户信息session的键名
$config['admin_auth']		=	'admin_auth_info';	// 用户权限session的键名
$config['admin_login_sess']	=	'admin_login_sess';	// 用户登录session的键名

// redis配置信息	-------------------------------------------------------------------------------------------------------
$config['redis']['host']	=	'10.13.0.12';	// redis服务器地址
$config['redis']['port']	=	6379;			// redis服务器端口
$config['redis']['auth']	=	'';				// redis服务器密码
$config['redis']['time']	=	300;			// 默认过期时间，单位:秒
$config['redis']['auto']	=	TRUE;			// 是否开启redis主动缓存
$config['redis']['remain']	=	10;				// 开始主动缓存的剩余时间，单位:秒，ttl少于此时间则开始执行主动缓存机制
$config['redis']['autokey']	=	'AutoCacheKey';	// 主动缓存队列的键名

// 系统变量
define( 'SUCCESS', 1 );				// 成功
define( 'PARAM_FALSE', 1001 );		// 参数错误
define( 'OPERATE_FALSE', 1002 );	// 操作失败
define( 'Auth_FALSE', 1003);		// 没有权限
define( 'FORBIDDEN', 1004 );		// 超级管理员也不能操作
define( 'VALIDATE_FALSE', 1005 );	// 验证码错误
define( 'BAN_ACCOUNT' , 1006 );		// 封号
define( 'PASS_WRONG', 1007 );		// 密码错误

?>