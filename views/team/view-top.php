<?php
$from = 'team';
if($isReported){
    $url = "../../../../diary/source/images/already-report.png";
    $reportStatus = "已汇报";
}else{
     $url = "../../../../diary/source/images/no-report.png";
     $reportStatus = "未汇报";
}
?>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <?php if($type == 'daily'):?>
    <div class="pags fl clearfix">
        <a href="daily?forward=<?php echo $forwardDays;?>&uid=<?php echo $uid;?>" class="up" title="上一天"></a>
        <a href="daily?forward=<?php echo $backwardDays;?>&uid=<?php echo $uid;?>" class="down" title="下一天"></a>
    </div>
    <p class="fl">
        <?php echo date('Y年m月d日', $startTime);?>（周<?php echo $weekarray[date("w", $startTime)];?>）
    </p>
    <?php elseif($type == 'weekly'):?>
    <div class="pags fl clearfix">
        <a href="weekly?forward=<?php echo $forwardWeeks;?>&uid=<?php echo $uid;?>" class="up" title="上一周"></a>
        <a href="weekly?forward=<?php echo $backwardWeeks;?>&uid=<?php echo $uid;?>" class="down" title="下一周"></a>
    </div>
    <p class="fl">
        <?php echo date('Y年m月d日', $startTime);?>--<?php echo date('Y年m月d日', $endTime);?>
    </p>
    <?php elseif($type == 'monthly'):?>
    <div class="pags fl clearfix">
        <a href="monthly?forward=<?php echo $forwardMonths;?>&uid=<?php echo $uid;?>" class="up" title="上一月"></a>
        <a href="monthly?forward=<?php echo $backwardMonths;?>&uid=<?php echo $uid;?>" class="down" title="下一月"></a>
    </div>
    <p class="fl"><?php echo $currentMonth;?></p>
    <?php endif;?>
    <p class="fl mg10" title="<?php echo $reportStatus;?>">
        <img src="<?php echo $url; ?>" alt="<?php echo $reportStatus;?>" />
    </p>
    <p class="fl">
        <?php
             if($type == 'daily') {
                 $forward = $forwardDays - 1;
                 $backUrl = "index?forward=$forward";
             }else if($type == 'weekly') {
                 $forward = $forwardWeeks - 1;
                 $backUrl = "week?forward=$forward";
             }else {
                 $forward = $forwardMonths - 1;
                 $backUrl = "month?forward=$forward";
             }
        ?>
    </p>
    <a href="javascript:" class="fr dc mg10" id="js-selected_user" >组织架构</a>
    <div class="data fr clearfix">
        <a href="daily?uid=<?php echo $uid;?>" class="<?php echo $type == 'daily' ? 'cur' : ''?>">今日</a>
        <a href="weekly?uid=<?php echo $uid;?>" class="<?php echo $type == 'weekly' ? 'cur' : ''?>">本周</a>
        <a href="monthly?uid=<?php echo $uid;?>" class="<?php echo $type == 'monthly' ? 'cur' : ''?>">本月</a>
    </div>
</div>
<!--功能操作结束-->
<?php include 'plugins.php';?>
