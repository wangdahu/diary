<?php
$title = "月报";
$type = 'monthly';

// 向前向后翻天
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


$uid = (int) $_GET['uid'];
include dirname(dirname(__FILE__))."/class/User.php";
$user = User::getInfo($uid);
$corpId = $user['corp_id'];

// 查看当前第一天和最后一天是星期几，补全最开始和结尾的格子

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php include "views/layouts/footer.php"; ?>
