<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <?php if($type == 'daily'):?>
    <div class="pags fl clearfix"><a href="index?forward=<?php echo $forwardDays;?>" class="up" title="上一天"></a><a href="index?forward=<?php echo $backwardDays;?>" class="down" title="下一天"></a></div>
    <p class="fl"><?php echo date('Y年m月d日', $startTime);?>（周<?php echo $weekarray[date("w", $startTime)];?>）</p>
    <?php elseif($type == 'weekly'):?>
    <div class="pags fl clearfix"><a href="weekly?forward=<?php echo $forwardWeeks;?>" class="up" title="上一周"></a><a href="weekly?forward=<?php echo $backwardWeeks;?>" class="down" title="下一周"></a></div>
    <p class="fl"><?php echo date('Y年m月d日', $startTime);?>--<?php echo date('Y年m月d日', $endTime);?></p>
    <?php elseif($type == 'monthly'):?>
    <div class="pags fl clearfix"><a href="monthly?forward=<?php echo $forwardMonths;?>" class="up" title="上一月"></a><a href="monthly?forward=<?php echo $backwardMonths;?>" class="down" title="下一月"></a></div>
    <p class="fl"><?php echo $currentMonth;?></p>
    <?php endif;?>
    <div class="data fr clearfix">
        <a href="index" class="<?php echo $type == 'daily' ? 'cur' : ''?>">今日</a>
        <a href="weekly" class="<?php echo $type == 'weekly' ? 'cur' : ''?>">本周</a>
        <a href="monthly" class="<?php echo $type == 'monthly' ? 'cur' : ''?>">本月</a>
    </div>
    <a href="javascript:" class="a_02 fr mr10 js-write-dialy"></a>
</div>
<!--功能操作结束-->
