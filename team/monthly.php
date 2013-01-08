<?php
$title = "月报-团队日志";
$type = 'monthly';

// 向前向后翻天
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
if($forward){
    $forwardMonths = $forward + 1;
    $backwardMonths = $forward - 1;
    $currentMonth = date('y年m月', mktime(0, 0, 0, date("m")-$forward, 1, date("Y")));
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")-$forward+1,1,date("Y")) - 1;
}else{
    $forwardMonths = 1;
    $backwardMonths = -1;
    $currentMonth = date('y年m月');
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")+1,1,date("Y")) - 1;
}
$showDiary = $forward < 0 ? false : true;
// 查看的年份和月份
$object = date('Y-m', $endTime);
$uid = (int) $_GET['uid'];

$from = 'team';
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($showDiary):?>
<?php include "views/team/monthly.php"; ?>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
