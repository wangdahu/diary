<?php
$title = "月报";
$type = 'monthly';

// 向前向后翻月
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

// 查看当前第一天和最后一天是星期几，补全最开始和结尾的格子
$maxDays = date('t', $startTime);
// 今天的日
$currentMonthDate = date('j');
if($maxDays < $currentMonthDate){
    $currentMonthDate = $maxDays;
}
// 当前月的今日的时间戳
$currentTime = $startTime + ($currentMonthDate - 1)*86400;
$currentWeekth = date('w', $currentTime); // 星期几

// 第一天是星期几，最后一天是星期几
$firstDayWeekth = date('w', $startTime);
$endDayWeekth = date('w', $endTime);
// 第一个格子的日期
if($firstDayWeekth == 0){ // 星期天
    $firstTime = $startTime - (7-1)*86400;
}else{
    $firstTime = $startTime - ($firstDayWeekth-1)*86400;
}
$currentWeek = floor(($currentTime - $firstTime)/(7*86400));
if($firstDayWeekth != 1){
    // 计算上月最后一周
    $weekArr = array('', '一','二','三','四','五');
}else{
    $weekArr = array('一','二','三','四','五');
}
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/my/top.php"; ?>

<div class="content">
    <!--本月总结开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>今月工作：<em>20项</em></p>
        </h2>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <h2><a href="#">月总结</a></h2>
                <p>1、通知公告：查阅统计功能 （已完成20%）<br />2、授权管理：新增最大并发量（已完成100%）</p>
            </div>
        </div>
        <div class="c_b"></div>
    </div>
    <!--本月总结结束-->
    <!--日历开始-->
    <div class="content_bar">
        <div class="c_t mt10"></div>
        <div class="c_c">
            <table class="calendar">
                <thead>
                    <tr>
                        <td><?php echo $currentMonth;?></td>
                        <td style="<?php echo $currentWeekth == 1 ? 'color:blue;' : ''?>">星期一</td>
                        <td style="<?php echo $currentWeekth == 2 ? 'color:blue;' : ''?>">星期二</td>
                        <td style="<?php echo $currentWeekth == 3 ? 'color:blue;' : ''?>">星期三</td>
                        <td style="<?php echo $currentWeekth == 4 ? 'color:blue;' : ''?>">星期四</td>
                        <td style="<?php echo $currentWeekth == 5 ? 'color:blue;' : ''?>">星期五</td>
                        <td style="<?php echo $currentWeekth == 6 ? 'color:blue;' : ''?>">星期六</td>
                        <td style="<?php echo $currentWeekth == 0 ? 'color:blue;' : ''?>">星期日</td>
                    </tr>
                </thead>
                <tbody>
                    <?php for($w = 0; $w < 5; $w++):?>
                    <tr>
                        <td class="<?php echo $currentWeek == $w ? 'td_blue' : 'td_l'?>">第<?php echo $weekArr[$w]?>周</td>
                        <?php for($i = 0; $i < 7; $i++): ?>
                              <?php $j = date('j', $firstTime + 7*$w*86400 + $i*86400); ?>
                        <td class="<?php echo ($currentWeek == $w && $j == $currentMonthDate) ? 'td_blue' : td_grey; ?>">
                            <?php echo $j;?>
                        </td>
                        <?php endfor;?>
                    </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>
        <div class="c_b"></div>
    </div>
    <!--日历结束-->
</div>
<?php include "views/layouts/footer.php"; ?>
    
<div id="dialog-form" title="写月报">
    <form>
        <fieldset>
            <textarea cols="60" rows="12" id="daily_content"></textarea>
        </fieldset>
    </form>
    <div></div>
</div>

<script>
    $(function() {
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            buttons: {
                "写日志": function(){
                    var content = $("#daily_content").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    var currentTime = <?php echo $startTime; ?>;
                    $.post('createDaily', {content:content, currentTime:currentTime}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                allFields.val("").removeClass("ui-state-error");
            }
        });
        $(".write-dialy").button().click(function(){$("#dialog-form").dialog("open");});
    });
</script>

