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

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($showDiary):?>
<?php
include dirname(dirname(__FILE__))."/class/User.php";
$user = User::getInfo($uid);
$corpId = $user['corp_id'];

// 查看当前第一天和最后一天是星期几，补全最开始和结尾的格子
?>
<div class="content">
    <!-- 本月工作开始 -->

    <!-- 本月工作结束 -->
    <?php include "comment.php"; ?>
</div>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
