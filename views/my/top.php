<?php
if($isReported){
    $reportCls = "top-reported";
    $reportStatus = "已汇报";
}else{
    if((!$forward && !$allowPay) || $forward < 0){
        $reportCls = "top-wait-report";
        $reportStatus = "等待汇报";
    }else{
        $reportCls = "top-unreport";
        $reportStatus = "未汇报";
    }
}
?>
<script src="../../../../diary/source/js/wordslimit.js"></script>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <span class="fl left-border"></span>
    <?php if($type == 'daily'):?>
    <div class="pags fl clearfix"><a href="index?forward=<?php echo $forwardDays;?>" class="up" title="上一天"></a><a href="index?forward=<?php echo $backwardDays;?>" class="down" title="下一天"></a></div>
    <p class="fl showObject"><?php echo date('Y年m月d日', $startTime);?> 周<?php echo $weekarray[date("w", $startTime)];?></p>
    <?php elseif($type == 'weekly'):?>
    <div class="pags fl clearfix"><a href="weekly?forward=<?php echo $forwardWeeks;?>" class="up" title="上一周"></a><a href="weekly?forward=<?php echo $backwardWeeks;?>" class="down" title="下一周"></a></div>
    <p class="fl showObject"><?php echo date('Y年m月d日', $startTime);?>--<?php echo date('Y年m月d日', $endTime);?></p>
    <?php elseif($type == 'monthly'):?>
    <div class="pags fl clearfix"><a href="monthly?forward=<?php echo $forwardMonths;?>" class="up" title="上一月"></a><a href="monthly?forward=<?php echo $backwardMonths;?>" class="down" title="下一月"></a></div>
    <p class="fl showObject"><?php echo $currentMonth;?></p>
    <?php endif;?>
    <p class="fl status mg15 <?php echo $reportCls?>" title="<?php echo $reportStatus;?>"><?php echo $reportStatus; ?></p>
    <div class="data fr clearfix">
        <a href="index" class="<?php echo $type == 'daily' ? 'cur' : 'normal'?>">日报</a>
        <a href="weekly" class="<?php echo $type == 'weekly' ? 'cur' : 'normal'?>">周报</a>
        <a href="monthly" class="<?php echo $type == 'monthly' ? 'cur' : 'normal'?>">月报</a>
    </div>
</div>
<!--功能操作结束-->
<script>
    $(function(){
        $('.normal').mouseover(function() {
            $(this).addClass('data-hover');
        }).mouseout(function() {
            $(this).removeClass('data-hover');
        });
    });

    // 补交
    $(document.body).on('click', '.js-pay_diary', function() {
        var type = '<?php echo $type;?>',
        currentDate = '<?php echo $object; ?>',
        showObject = $('.showObject').html();
        $.post('/diary/index.php/my/payDiary', {currentDate:currentDate, type:type, showObject:showObject}, function(json) {
            if(json == 0) {
                alert('补交失败，请设置汇报对象');
            }else {
                location.reload();
            }
        });
    });
</script>
