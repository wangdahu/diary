<?php
$title = "团队日志";
$setDefault = 'index';

$uid = $diary->uid;
$corpId = $diary->corpId;

// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
if($forward){
    $forwardDays = $forward + 1;
    $backwardDays = $forward - 1;
    $currentDate = date('Y-m-d',time() - $forward*86400);
}else{
    $forwardDays = 1;
    $backwardDays = -1;
    $currentDate = date('Y-m-d',time());
}
$startTime = strtotime($currentDate);
$endTime = $startTime + 86400 - 1;

// 查看的日期
$object = date('Y-m-d', $endTime);
$type = "daily";

include dirname(dirname(__FILE__))."/class/DiarySet.php";
include dirname(dirname(__FILE__))."/class/User.php";
// 日报汇报给我的和我订阅的用户
$teamShowObject = DiarySet::teamShowObject($diary, 1);
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/top.php"; ?>
<?php include "main.php"; ?>
<?php include "views/layouts/footer.php"; ?>
