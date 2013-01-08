<?php
$currentModule = 'my';
$filePath = substr($_SERVER['PATH_INFO'], 1);
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
