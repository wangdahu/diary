<?php
$title = "周报";
$type = 'weekly';

// 向前向后翻天
$forward = isset($_GET['forward']) ? $_GET['forward'] : 0;
if($forward){
    $forwardWeeks = $forward + 1;
    $backwardWeeks = $forward - 1;
    $startTime = strtotime("this Monday") - $forward*86400*7;
}else{
    $forwardWeeks = 1;
    $backwardWeeks = -1;
    $startTime = strtotime("this Monday");
}
$endTime = $startTime + 7*86400 - 1;
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>
<?php include "views/layouts/footer.php"; ?>
