<?php
$currentModule = 'my';
if(file_exists(dirname(dirname(__FILE__))."/vars.php")){
    include dirname(dirname(__FILE__))."/vars.php";
    $_session_arr = Session::instance()->get();
    if(!isset($_session_arr['userInfo'])){
        $url = '/index.php/admin/user/login';
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
            $url = '/diary/index.php/'.$filePath.'/index';
            header("Location: $url");
            exit;
        }
    }else{
        $file = $filePath.".php";
    }
    $currentModule = $pathArr[0];
}else{
    $url = '/diary/index.php/my/index';
    header("Location: $url");
    exit;
}
// 加载所有类
include "Init.php";

$diary = new Diary();
include $file;
