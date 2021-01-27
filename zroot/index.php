<?php
header('Access-Control-Allow-Origin: *');
header("Content-type:text/html;charset=utf-8");

error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

//define('BIND_MODULE','Admin');  //生成模块
//define('BUILD_CONTROLLER_LIST','Index,User');   //生成控制器
//define('BUILD_MODEL_LIST','User,Member');   //生成模型
//手动生成
//\Think\Build::buildController('Admin','Role');
//\Think\Build::buildModel('Admin','Role');

// 定义应用目录
// define('APP_PATH','./Application/');
// define('RUNTIME_PATH','./Runtime/');
// define('APP_ROOTPATH',__DIR__);d

define('APP_PATH', './../Application/'); //相对于应用入口的地方,application文件夹
define('RUNTIME_PATH', './../Runtime/');
define('DATAINFO_PATH', './../Datainfo/');

define('APP_ROOTPATH', __DIR__); //string(40) "D:\phpStudy\WWW\phpMyAdmin\ken\wzs\zroot"
define('WEBROOTURL', 'http://' . $_SERVER['HTTP_HOST']); //'http://'.$_SERVER['SERVER_NAME']. '/Index/paihang' HTTP_HOST = SERVER_NAME:SERVER_PORT
// header('location:http://abc.com/');
define('SYS_APPID', 'wx65f6aaa6eb3898d4'); //appid
define('SYS_APPSEC', '3befae689bda50c852d807e43aa49517'); //appsecret
// echo '<img src="/1.jpg?qwer=echo(4444)"/><img src="/aa.png?qwer=echo(4444)"/>';
// die;
// require './ThinkPHP/ThinkPHP.php';
require './../ThinkPHP/ThinkPHP.php';