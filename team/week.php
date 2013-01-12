<?php
$title = "团队日志";
$setDefault = 'week';

$uid = $diary->uid;
$corpId = $diary->corpId;

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
// 查看的年份和周
$object = date('Y-W', $startTime);
$type = "weekly";

// 周报汇报给我的和我订阅的用户
$teamShowObject = DiarySet::teamShowObject($diary, 2);
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/top.php"; ?>
<?php include "main.php"; ?>
<?php include "views/layouts/footer.php"; ?>
