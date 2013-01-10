<?php
$currentModule = 'my';
if(file_exists(dirname(dirname(__FILE__))."/vars.php")){
    include dirname(dirname(__FILE__))."/vars.php";
    $_session_arr = Session::instance()->get();
    if(!isset($_session_arr['userInfo'])){
        $url = 'http://113.106.88.164:14132/index.php/admin/user/login';
        header("Location: $url");
        exit;
    }
}

$filePath = isset($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'], 1) : '';
if($filePath){
    $pathArr = explode('/', $filePath);
    if(!file_exists($filePath.".php")){
        if(!file_exists($filePath."/index.php")){
            die('访问错误');
        }else{
            $file = $filePath."/index.php";
        }
    }else{
        $file = $filePath.".php";
    }
    $currentModule = $pathArr[0];
}else{
    $file = "my/index.php";
}
// 加载所有类
include "Init.php";

$diary = new Diary();
include $file;
