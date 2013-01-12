<?php
if($isReported){
    $url = "../../../../diary/source/images/already-report.png";
    $reportStatus = "已汇报";
}else{
    if((!$forward && !$allowPay) || $forward < 0){
        $url = "../../../../diary/source/images/wait-report.png";
        $reportStatus = "等待汇报";
    }else{
        $url = "../../../../diary/source/images/no-report.png";
        $reportStatus = "未汇报";
    }
}
?>
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
    <p class="fl mg10" title="<?php echo $reportStatus;?>"><img src="<?php echo $url; ?>" alt="<?php echo $reportStatus;?>" /></p>
    <div class="data fr clearfix">
        <a href="index" class="<?php echo $type == 'daily' ? 'cur' : ''?>">今日</a>
        <a href="weekly" class="<?php echo $type == 'weekly' ? 'cur' : ''?>">本周</a>
        <a href="monthly" class="<?php echo $type == 'monthly' ? 'cur' : ''?>">本月</a>
    </div>
</div>
<!--功能操作结束-->
<script>
    // 补交
    $('.js-pay_diary').live('click', function() {
        var type = '<?php echo $type;?>',
        currentDate = '<?php echo $object; ?>';
        $.post('/diary/index.php/my/payDiary', {currentDate:currentDate, type:type}, function(json) {
            if(json != 0) {
                location.reload();
            }
        });
    });

$.fn.extend({
    wordLimit: function() {
        this.each(function() {
            var textarea = $(this), limit = textarea.data('limit'), wordIndicator;
            if(textarea.data('init')) {
                return textarea.trigger('input');
            }
            textarea.data('init', 1);
            textarea.parent().css('position', 'relative');
            wordIndicator = $('<span class="word-limit"><span>0</span> / <span>' + limit + '</span><input id="word_valid" type="hidden"/></span>').insertAfter(textarea);
            textarea.bind('input keyup', function() {
                var len = this.value.length;
                wordIndicator.find('span:first').text(len);
                wordIndicator.toggleClass('word-exceed', len > limit);
                wordIndicator.find('input[type=hidden]').val(len > limit ? 1 : '');
            }).trigger('input');
        });
        return this;
    }
});
</script>

