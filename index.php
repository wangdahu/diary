<?php
$pathInfo = substr($_SERVER['PATH_INFO'], 1);
$currentModule = 'my';
if($pathInfo){
    $pathArr = explode('/', $pathInfo);
    if(!file_exists($pathInfo.".php")){
        if(!file_exists($pathInfo."/index.php")){
            die('访问错误');
        }else{
            $file = $pathInfo."/index.php";
        }
    }else{
        $file = $pathInfo.".php";
    }
    $currentModule = $pathArr[0];
}else{
    $file = "my/index.php";
}
// 加载所有类
include "class/Diary.php";
$diary = new Diary();

include $file;
