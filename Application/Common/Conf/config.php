<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL' => 2, // 1为pathinfo模式 2.rewrite
	'MODULE_ALLOW_LIST' => array('Home', 'Admin', 'Sys', 'Vip'), //允许访问的模块
	'DEFAULT_MODULE' => 'Home', // 默认访问模块
	'TMPL_TEMPLATE_SUFFIX' => '.html', // 模板后缀
	'URL_HTML_SUFFIX' => 'html', // URL后缀php|
	'DB_TYPE' => 'mysql', // 数据库类型

	'DB_HOST' => '134.175.70.67', // 服务器地址
	'DB_NAME' => 'qinghem', // 数据库名
	'DB_USER' => 'qinghem', // 用户名
	'DB_PWD' => 'zKwekLhwXrWZAXH3', // 密码
	'DB_PORT' => 39306, // 端口
	'DB_PREFIX' => 'app_', // 数据库表前缀
	'DB_CHARSET' => 'utf8mb4', // 数据库编码默认采用utf8

);