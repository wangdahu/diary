<?php
$title = "周报-团队日志";
$type = 'weekly';

// 当前周的周一时间戳
$mondayTime = date('w') == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
if($forward){
    $forwardWeeks = $forward + 1;
    $backwardWeeks = $forward - 1;
    $startTime = $mondayTime - $forward*86400*7;
}else{
    $forwardWeeks = 1;
    $backwardWeeks = -1;
    $startTime = $mondayTime;
}
$endTime = $startTime + 7*86400 - 1;
// 查看的年份和月份
$object = date('Y-W', $startTime);
$showDiary = $forward < 0 ? false : true;
$uid = (int) $_GET['uid'];

$showCommit = false;
$allowPay = false;
$isReported = true;
// 判断是否为补交/未汇报/已汇报
if($forward == 0) { // 本月
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary, $uid);
    $w = date('w') ? date('w') : 7; // 周日转换成7
    $weeklyTime = $reportTime['weeklyReport']['hour'].":".$reportTime['weeklyReport']['minute'];
    if($w > $reportTime['weeklyReport']['w'] || ($w == $reportTime['weeklyReport']['w'] && time() > strtotime(date('Y-m-d')." ".$weeklyTime))) { // 已过汇报时间
        $showCommit = true;
        $isReported = DiaryReport::checkReport($diary, $type, $object, $uid);
    }else {
        $isReported = false;
    }
}else { // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object, $uid);
    $showCommit = true;
}

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($showDiary):?>
<?php include "views/team/weekly.php"; ?>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
