<?php
$weekarray=array("日","一","二","三","四","五","六");
?>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <?php if($setDefault == 'index'):?>
    <div class="pags fl clearfix"><a href="index?forward=<?php echo $forwardDays;?>" class="up" title="上一天"></a><a href="index?forward=<?php echo $backwardDays;?>" class="down" title="下一天"></a></div>
    <p class="fl"><?php echo date('Y年m月d日', $startTime);?>（周<?php echo $weekarray[date("w", $startTime)];?>）</p>
    <?php elseif($setDefault == 'week'):?>
    <div class="pags fl clearfix"><a href="week?forward=<?php echo $forwardWeeks;?>" class="up" title="上一周"></a><a href="week?forward=<?php echo $backwardWeeks;?>" class="down" title="下一周"></a></div>
    <p class="fl"><?php echo date('Y年m月d日', $startTime);?>--<?php echo date('Y年m月d日', $endTime);?></p>
    <?php elseif($setDefault == 'month'):?>
    <div class="pags fl clearfix"><a href="month?forward=<?php echo $forwardMonths;?>" class="up" title="上一月"></a><a href="month?forward=<?php echo $backwardMonths;?>" class="down" title="下一月"></a></div>
    <p class="fl"><?php echo $currentMonth;?></p>
    <?php endif;?>
    <a href="javascript:" class="fr dc ml10" style="margin-right: 18px;" id="js-selected_user"></a>
    <div class="data fr clearfix">
        <a href="index" class="<?php echo $setDefault == 'index' ? 'cur' : 'normal'?>">日报</a>
        <a href="week" class="<?php echo $setDefault == 'week' ? 'cur' : 'normal'?>">周报</a>
        <a href="month" class="<?php echo $setDefault == 'month' ? 'cur' : 'normal'?>">月报</a>
    </div>
</div>
<!--功能操作结束-->
<?php include 'plugins.php'; ?>

<script>
    $(function(){
        $('.normal').mouseover(function() {
            $(this).addClass('data-hover');
        }).mouseout(function() {
            $(this).removeClass('data-hover');
        });
    });
</script>
