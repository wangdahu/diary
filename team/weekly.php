<?php
$title = "周报-团队日志";
$forwardTitle = "周报";
$type = 'weekly';

// 当前周的周一时间戳
$mondayTime = date('w') == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
if(!$forward) {
    $startTime = isset($_GET['startTime']) ? (int)$_GET['startTime'] : 0;
    if($startTime) {
        $forward = floor(($mondayTime - $startTime)/(7*86400));
    }
}
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

$showDiary = $forward < 0 ? false : true;
$uid = (int) $_GET['uid'];

// 当前时间为 当前年的多少周
$object = date('Y-W', $startTime);
$weekDate = array();
// 用户设置的工作时间
$selected = DiarySet::workingTime($diary, $uid);
$weekarray = array("一","二","三","四","五","六","日");

// 当前周的所有工作天
for($i = 6; $i >= 0; $i--){
    $time = $startTime + 86400*$i;
    $weekDate[$weekarray[$i]] = date('y.m.d', $time);
    $dateForwards[date('y.m.d', $time)] = $time;
}

// 该企业该用户在选择时间内的日报
$dailySql = "select * from `diary_info` where `uid` = $uid and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
$result = $diary->db->query($dailySql);
$dailys = array();
$dailyNum = 0;
while($row = $result->fetch_array(MYSQLI_ASSOC)){
    // 是否汇报
    if(DiaryReport::checkReport($diary, 'daily', date('Y-m-d', $row['show_time']), $uid)) {
        $dailyNum ++;
        $dailys[date('y.m.d', $row['show_time'])][] = $row;
    }
};
$showCommit = false;
$allowPay = false;
$isReported = true;
// 判断是否为补交/未汇报/已汇报
if($forward == 0) { // 本周
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
<?php include "views/layouts/diary-header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($showDiary):?>
<?php include "views/team/weekly.php"; ?>
<?php else:?>
<?php include "views/team/forward.php"; ?>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
