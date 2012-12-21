<?php
$pathInfo = substr($_SERVER['PATH_INFO'], 1);
if($pathInfo){
    $pathArr = explode('&', $pathInfo);
    $filePath = $pathArr[0];
    if(!file_exists($filePath.".php")){
        if(!file_exists($filePath."/index.php")){
            die('访问错误');
        }else{
            $file = $filePath."/index.php";
        }
    }else{
        $file = $filePath.".php";
    }
}else{
    $file = "my/index.php";
}
// 加载所有类
include "class/Diary.php";
$diary = new Diary();

include $file;
