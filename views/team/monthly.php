<?php
include dirname(dirname(dirname(__FILE__)))."/class/User.php";
$user = User::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 3 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$monthlys = array();
while($row = $result->fetch_assoc()){
    $monthlys = $row;
};

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
    // 上月第一天的时间戳
    $currentMonthPrev = mktime(0, 0, 0, date("m")-$forward-1, 1, date("Y"));
    $prevMonth = date('m', $currentMonthPrev);
    // 计算上月最后一周
    $weekArr = array($prevMonth.'月末周', '第一周','第二周','第三周','第四周','第五周');
}else{
    $weekArr = array('第一周','第二周','第三周','第四周','第五周','第六周');
}
$maxWeek = ceil(($endTime - $firstTime)/(7*86400));

// 当前月历中的开始时间和结束时间
$lastTime = $firstTime + $maxWeek*7*86400 - 1;
// 当前月历中哪些天有评论
$firstDate = date('Y-m-d', $firstTime);
$lastDate = date('Y-m-d', $lastTime);
include dirname(dirname(dirname(__FILE__)))."/class/DiaryComment.php";
$dateObject = DiaryComment::getWhichDate($diary, $uid, 'daily', $firstDate, $lastDate);
$weekObject = DiaryComment::getWhichDate($diary, $uid, 'weekly', date('Y-W', strtotime($firstDate)), date('Y-W', strtotime($lastDate)));

// 查询有内容的天并未汇报
include dirname(dirname(dirname(__FILE__)))."/class/DiaryDaily.php";
$noReportDaily = DiaryDaily::noReportDaily($diary, $firstTime, $lastTime, 1, $uid);
$noReportWeekly = DiaryDaily::noReportDaily($diary, $firstTime, $lastTime, 2, $uid);

?>
<div class="content">
    <!--本月总结开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>月报</p>
            <?php if($allowPay):?>
            <?php if($monthlys):?>
            <a href="javascript:" class="fr mr10 pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr mr10 pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if($uid == $diary->uid):?>
            <a href="javascript:;" class="fr write-monthly"></a>
            <?php endif;?>
        </h2>

        <?php if(!$monthlys):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <?php else:?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-daily_id="<?php echo $monthlys['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                    <div style="display:none;"><?php echo $monthlys['content'];?></div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif;?>
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
                    <?php for($w = 0; $w < $maxWeek; $w++):?>
                    <tr>
                        <?php
                             $thisWeek = date('Y-W',($firstTime + 7*$w*86400));
                             $weekForward = DiaryDaily::getForward($firstTime + 7*$w*86400, 2);
                        ?>
                        <td class="<?php echo in_array($thisWeek, $weekObject) ? 'comment' : ''?> <?php echo $currentWeek == $w ? 'td_blue' : 'td_l'?> <?php echo in_array($thisWeek, $noReportWeekly) ? 'no-report' : '';?>">

                            <a href="diary/index.php/my/index?forward=<?php echo $weekForward;?>">
                                <div>
                                    <?php echo $weekArr[$w]; ?>
                                </div>
                            </a>
                        </td>
                        <?php for($i = 0; $i < 7; $i++): ?>
                              <?php
                                   $thisTime = $firstTime + 7*$w*86400 + $i*86400;
                                   $j = date('j', $thisTime);
                                   $thisDate = date('Y-m-d', $thisTime);
                                   $dateForward = DiaryDaily::getForward($firstTime + 7*$w*86400 + $i*86400);
                              ?>
                        <td class="<?php echo ($currentWeek == $w && $j == $currentMonthDate) ? 'td_blue' : td_grey; ?> <?php echo in_array($thisDate, $noReportDaily) ? 'no-report' : '';?> <?php echo in_array($thisDate, $dateObject) ? 'comment' : '';?> <?php echo $thisTime > time() ? 'td_white' : '';?>">
                            <a href="diary/index.php/my/index?forward=<?php echo $dateForward;?>">
                                <div>
                                    <?php echo $j;?>
                                </div>
                            </a>
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
    <?php if($showCommit):?>
    <?php include dirname(dirname(dirname(__FILE__)))."/team/comment.php"; ?>
    <?php endif;?>
</div>