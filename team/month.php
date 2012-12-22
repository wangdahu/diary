<?php
$title = "团队日志";
$setDefault = 'month';

$uid = $diary->uid;
$corpId = $diary->corpId;

// 向前向后翻月
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
if($forward){
    $forwardMonths = $forward + 1;
    $backwardMonths = $forward - 1;
    $currentMonth = date('y年m月', mktime(0, 0, 0, date("m")-$forward, 1, date("Y")));
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")-$forward+1,1,date("Y"));
}else{
    $forwardMonths = 1;
    $backwardMonths = -1;
    $currentMonth = date('y年m月');
    $startTime = mktime(0,0,0,date("m")-$forward,1,date("Y"));
    $endTime = mktime(0,0,0,date("m")+1,1,date("Y"));
}

include dirname(dirname(__FILE__))."/class/Set.php";
include dirname(dirname(__FILE__))."/class/User.php";
// 月报汇报给我的和我订阅的用户
$teamShowObject = Set::teamShowObject($diary, 3);
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/team/top.php"; ?>
<?php include "main.php"; ?>
<?php include "views/layouts/footer.php"; ?>
