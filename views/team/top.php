<?php
$weekarray=array("日","一","二","三","四","五","六");
?>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="" class="a_01 fl">刷新</a>
    <div class="pags fl clearfix"><a href="index?forward=<?php echo $forwardDays;?>" class="up" title="上一天"></a><a href="index?forward=<?php echo $backwardDays;?>" class="down" title="下一天"></a></div>
     <p class="fl"><?php echo date('Y年m月d日', $startTime);?>（周<?php echo $weekarray[date("w", $startTime)];?>）</p>
    <a href="javascript:" class="fr" style="margin: 0px 10px;">组织架构</a>
    <div class="data fr clearfix">
        <a href="index" class="cur">今日</a>
        <a href="javascript:">本周</a>
        <a href="javascript:">本月</a>
    </div>
</div>
<!--功能操作结束-->
