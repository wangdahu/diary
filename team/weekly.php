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
$showDiary = $forward < 0 ? false : true;
$uid = (int) $_GET['uid'];
$showCommit = true;

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($showDiary):?>
<?php include "views/team/weekly.php"; ?>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
