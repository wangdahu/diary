<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title;?></title>
    <script src="../../../../diary/source/jqueryUI/js/jquery-1.9.0.min.js"></script>
    <script src="../../../../diary/source/jqueryUI/js/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="../../../../diary/source/jqueryUI/js/jquery.ui.dialog.js"></script>
    <link rel="stylesheet" href="../../../../diary/source/jqueryUI/css/smoothness/jquery-ui-1.10.0.custom.min.css">
    <link rel="stylesheet" href="../../../../diary/source/css/base.css">
    <link rel="stylesheet" href="../../../../diary/source/css/module.css">
    <link rel="stylesheet" href="../../../../diary/source/css/popup.css">
    <script type="text/javascript">

        $(document).ready(function(){
            $("#appall").mouseover(function(){
                $("#applicationBox").addClass('app_b');
            }).mouseout(function(){
                $("#applicationBox").removeClass('app_b');
            });
        });

    </script>
<!--[if IE 6]>
<script src="/diary/source/js/DD_belatedPNG-min.js"></script>
<script>$(function() { DD_belatedPNG.fix('*'); });</script>
<![endif]-->
</head>

<body>
    <!--头部开始-->
    <div class="topBar">
        <div class="topBarInner">
            <h1><a href="index.html"><img src="../../../../diary/source/images/logo.png" alt="logo" /></a></h1>
            <div class="application" id="appall">
                <a href="#" class="app" id="app"><span>应用</span></a>
                <div id="applicationBox" class="applicationBox">
                    <ul>
                        <li><a href="#" class="app_icon01">工作流</a></li>
                        <li><a href="index.php"><span class="app_icon02">工作日志</span></a></li>
                        <li><span class="app_icon03">任务管理</span></li>
                    </ul>
                </div>
            </div>
            <span><a href="/admin/user/logout">退出</a>|<a href="javascript:setMainUrl('/admin/webmail/index')">邮箱配置</a>|<a href="#">帮助</a></span>
            <em>欢迎您 <b>黄斌</b></em>
            <!--导航开始-->
            <ul class="nav">
                <li><a href="/diary/index.php/my/index" class="<?php echo $currentModule == 'my' ? 'current' : ''?>">我的日志</a></li>
                <li><a href="/diary/index.php/team/index" class="<?php echo $currentModule == 'team' ? 'current' : ''?>">团队日志</a></li>
                <li><a href="/diary/index.php/set/diary" class="<?php echo $currentModule == 'set' ? 'current' : ''?>">日志设置</a></li>
            </ul>
            <!--导航结束-->
        </div>
    </div>
    <!--头部结束-->
    <div id="wrapper">
